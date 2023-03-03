<?php

namespace Yume\Fure\CLI\Command;

use Yume\Fure\CLI;
use Yume\Fure\CLI\Argument;
use Yume\Fure\Config;
use Yume\Fure\Error;
use Yume\Fure\Logger;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\Reflect;
use Yume\Fure\Util;

/*
 * Commands
 *
 * @package Yume\Fure\CLI\Command
 *
 * @extends Yume\Fure\Support\Data\Data
 */
class Commands
{
	
	/*
	 * Command Collections.
	 *
	 * @access Private Readonly
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	private Readonly Data\DataInterface $commands;
	
	/*
	 * Construct method of class Commands.
	 *
	 * @access Public Instance
	 *
	 * @params Protected Readonly Yume\Fure\Logger\LoggerInterface $logger
	 *
	 * @return Void
	 */
	public function __construct( protected Readonly Logger\LoggerInterface $logger )
	{
		// Make command collections.
		$this->commands = new Data\Data;
		
		// Mapping all defined commands.
		config( "app" )->commands->map(
			
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
	
	/*
	 * Get all available commands.
	 *
	 * @access Public
	 *
	 * @return Yume\Fure\Support\Data\DataInterface
	 */
	public function getAll(): Data\DataInterface
	{
		return( $this )->commands->copy();
	}
	
	/*
	 * Return if command is exists.
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
		if( $optional !== Null )
		{
			return( $this )->has( $command ) === $optional;
		}
		return( $this )->commands->__isset( $command );
	}
	
	/*
	 * Running command.
	 *
	 * @access Public
	 *
	 * @params String|Yume\Fure\CLI\Argument\ArgumentValue $command
	 * @params Yume\Fure\CLI\Argument\Argument $argument
	 *
	 * @return Void
	 */
	public function run( String | Argument\ArgumentValue $command, Argument\Argument $argument ): Void
	{
		// Copy command.
		$name = $command;
		
		// When command value is ArgumentValue class.
		if( $command Instanceof Argument\ArgumentValue )
		{
			// Get command name.
			$name = $command->name;
			
			// When command is not option e.g (-x|--xx)
			if( is_int( $command->name ) ) $name = $command->value;
		}
		
		// Check command is exists.
		if( $this->has( $name ) )
		{
			// Get command class.
			$command = $this->commands[$name];
			
			// If command has options defined.
			if( $command->hasOptions() )
			{
				// Mapping all required options.
				foreach( $command->getRequiredOptions() ?: [] As $option )
				{
					// If argument has no required option.
					if( $argument->has( $option->name, False ) )
					{
						// If command has Alias name and argument has required option by alias name.
						if( $option->alias && $argument->has( $option->alias ) )
						{
							continue;	
						}
						puts( "{}: {}: Required option {}\n", $argument->file, $name, $option );
						exit;
					}
				}
				
				// Mapping all defined options.
				foreach( $command->getOptions() As $option )
				{
					// If argument has no option, skip/ continue.
					if( $argument->has( $option->name, False ) ) continue;
					
					// If option has defined Type.
					if( $option->hasType() )
					{
						// If option type is Mixed or Null, skip/ continue.
						if( $option->type === Util\Types::MIXED ||
							$option->type === Util\Types::NULL ) continue;
						
						// If option type is doesn't valid with argument option.
						if( $option->type !== $argument[$option->name]->type )
						{
							CLI\CLI::puts( "\x1b[1;32msasayaki: \x1b[1;38;5;190m{}: \x1b[1;38;5;194m{}: Option value must be type {}, {}({}) given{}", "\x1b[1;37m", $command->getName(), $option->name, $option->type->name, $argument[$option->name]->type->name, $argument[$option->name]->value ?? "Null", PHP_EOL );
							exit;
						}
					}
				}
			}
			$command->run( $argument );
		}
		else {
			CLI\CLI::puts( "\x1b[1;32msasayaki: \x1b[1;31m{}: Command not found{}", "\x1b[1;37m", $command, PHP_EOL );
		}
	}
	
}

?>