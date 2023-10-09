<?php

namespace Yume\Fure\CLI\Command;

use Yume\Fure\CLI\Argument;
use Yume\Fure\Config;
use Yume\Fure\Error;
use Yume\Fure\Logger;
use Yume\Fure\Util;
use Yume\Fure\Util\Arr;
use Yume\Fure\Util\Reflect;

/*
 * Commands
 *
 * Command class container.
 *
 * @package Yume\Fure\CLI\Command
 */
final class Commands
{
	
	/*
	 * Commands container.
	 * 
	 * @access Protected
	 * 
	 * @values Yume\Fure\Util\Arr\Associative
	 */
	protected Arr\Associative $commands;

	/*
	 * Construct method of class Commands.
	 * 
	 * @access Public Initialize
	 * 
	 * @params Yume\Fure\Logger\LoggerInterface $logger
	 * 
	 * @return Void
	 */
	public function __construct( protected Readonly Logger\LoggerInterface $logger )
	{
		$this->commands = new Arr\Associative([]);
		$this->prepare();
	}

	/*
	 * Execute command.
	 * 
	 * @access Public
	 * 
	 * @params String|Yume\Fure\CLI\Argumet\ArgumentValue $command
	 * @params Yume\Fure\CLI\Argument\Argument $argument
	 * 
	 * @return Void
	 */
	public function exec( String | Argument\ArgumentValue $command, Argument\Argument $argument ): Void
	{
		/*
		 * ...
		 * 
		 */
		$resolver = function( Callable $self, CommandInterface $command, Array $requires ) use( &$argument ): Void
		{};

		// If command name is object instance of class ArgumentValue.
		if( $command Instanceof Argument\ArgumentValue ) $command = is_int( $command->name ) ? $command->value : $command->name;

		// Check if command not found.
		if( $this->has( $command, False ) ) throw new CommandNotFoundError( $command );

		// Get command instance.
		$command = $this->commands[$command];

		// Command option requirements.
		$requires = [];

		// Mapping all defined options.
		foreach( $command->getOptions() As $option )
		{
			// If argument has option name.
			if( $argument->has( $option->name ) ) $value = $argument[$option->name];

			// If argument has option alias name.
			else if( $option->hasAlias() && $argument->has( $option->alias ) ) $value = $argument[$option->name] = $argument[$option->alias];

			// If option has default value.
			else if( $option->hasDefaultValue() )
			{
				if( $option->isIncluded( False ) )
				{
					if( $option->isRequired() )
					{
						throw new CommandOptionRequireError([ $command, $option->name ]);
					}
					continue;
				}
				$value = $argument[$option->name] = new Argument\ArgumentValue(
					$option->name,
					$option->default,
					$option->type,
					strlen( $option->name ) >= 2
				);
			}
			
			// If option is required but does not available in argument.
			else if( $option->isRequired() ) throw new CommandOptionRequireError([ $command, $option->name ]);
			else continue;
			
			// If option has defined Type.
			if( $option->hasType() )
			{
				// If option type is Mixed or Null, skip/ continue.
				if( $option->type === Util\Type::Mixed || $option->type === Util\Type::None ) continue;
				
				// If option available but not for value.
				if( $value->type === Util\Type::None && $option->hasDefaultValue() )
				{
					$value = $argument[$option->name] = new Argument\ArgumentValue(
						$option->name,
						$option->default,
						$option->type,
						$value->long
					);
				}

				// If option type is Numeric number.
				if(
					$option->type === Util\Type::Int && $value->type === Util\Type::Integer ||
					$option->type === Util\Type::Integer && $value->type === Util\Type::Int
				)
				continue;
				
				// If option type is doesn't valid with argument option.
				if( $option->type !== $value->type ) throw new CommandOptionValueError([ $option->name, $command, $option->type->name, $value->type->name ]);
			}
		}
		$command->exec( $argument );
	}

	/*
	 * Return command by command name.
	 * 
	 * @access Public
	 * 
	 * @params String $command
	 *  Command name
	 * 
	 * @return Yume\Fure\CLI\Command\CommandInterface
	 * 
	 * @throws Yume\Fure\CLI\Command\CommandInterface
	 *  Throw when the command not found.
	 */
	public function get( String $command ): CommandInterface
	{
		if( $this->has( $command ) )
		{
			return( $this )->commands[$command];
		}
		throw new CommandNotFoundError( $command );
	}

	/*
	 * Return available commands.
	 * 
	 * @access Public
	 * 
	 * @return Yune\Fure\Util\Arr\Associative<String,Yume\Fure\CLI\Command\CommandInterface>
	 */
	public function getAll(): Arr\Associative
	{
		return( new Arr\Associative( $this->commands ) );
	}

	/*
	 * Return if command is available.
	 * 
	 * @access Public
	 * 
	 * @params String $command
	 * @params Bool $optional
	 * 
	 * @return Bool
	 */
	public function has( String $command, ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $this->has( $command ) === $optional : isset( $this->commands[$command] ) );
	}

	/*
	 * Preparing commands.
	 * 
	 * @access Private
	 * 
	 * @return Void
	 */
	private function prepare(): Void
	{
		$commands = config( "app" )->commands;
		$commands->map(
			
			/*
			 * Handle mapping command will be register.
			 *
			 * @params Int $i
			 * @params String $class
			 * @params Yume\Fure\Config\Config $config
			 *
			 * @return Void
			 *
			 * @throws Yume\Fure\Error\ClassImplementationError
			 */
			function( Int $i, Int | String $class, String | Config\Config $config )
			{
				if( is_int( $class ) )
				{
					$class = $config;
					$config = new Config\Config( "Builder", False, [] );
				}
				// Check if CommandHandler has implement CommandInterface.
				if( Reflect\ReflectClass::isImplements( $class, CommandInterface::class, $reflect ) )
				{
					// Create new Command instance.
					$command = $reflect->newInstance( $this, $config, $this->logger );
					$commandName = $command->getName();
					
					// Push command.
					$this->commands[$commandName] = $command;
				}
				else {
					throw new Error\ClassImplementationError([ $class, CommandInterface::class ]);
				}
			}
		);
	}

	/*
	 * Set new command.
	 * 
	 * @access Public
	 * 
	 * @params Yume\Fure\CLI\Command\CommandInterface $command
	 * 
	 * @return Void
	 */
	public function set( CommandInterface $command ): Void
	{
		$this->commands[$command->getName()] = $command;
	}

}

?>
