<?php

namespace Yume\Fure\CLI\Command;

use Yume\Fure\CLI\Argument;
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
class Commands
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
	final public function exec( String | Argument\ArgumentValue $command, Argument\Argument $argument ): Void
	{
		// If command name is object instance of class ArgumentValue.
		if( $command Instanceof Argument\ArgumentValue )
		{
			// Resolve command name.
			$command = is_int( $command->name ) ? $command->value : $command->name;
		}

		// Check if command not found.
		if( $this->has( $command, False ) )
		{
			throw new CommandNotFoundError( $command );
		}

		// Mapping all defined options.
		foreach( $this->commands[$command]->getOptions() As $option )
		{
			// If argument has no option, skip/ continue.
			if( $argument->has( $option->name, False ) )
			{
				// if option is required.
				if( $option->isRequired() )
				{
					throw new CommandOptionRequireError([ $command, $option->name ]);
				}
				continue;
			}
			
			// If option has defined Type.
			if( $option->hasType() )
			{
				// If option type is Mixed or Null, skip/ continue.
				if( $option->type === Util\Type::Mixed ||
					$option->type === Util\Type::None ) continue;
				
				// If option type is doesn't valid with argument option.
				if( $option->type !== $argument[$option->name]->type )
				{
					throw new CommandOptionValueError([
						$option->name,
						$command,
						$option->type->name,
						$argument[$option->name]->type->name
					]);
				}
			}
		}

		// Execute command.
		$this->commands[$command]->exec(
			$argument
		);
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
			function( Int $i, Int $index, String $class )
			{
				// Check if CommandHandler has implement CommandInterface.
				if( Reflect\ReflectClass::isImplements( $class, CommandInterface::class, $reflect ) )
				{
					// Create new Command instance.
					$command = $reflect->newInstance( $this, $this->logger );
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

}

?>