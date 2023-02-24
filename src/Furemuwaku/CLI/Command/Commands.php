<?php

namespace Yume\Fure\CLI\Command;

use Yume\Fure\CLI;
use Yume\Fure\CLI\Argument;
use Yume\Fure\Config;
use Yume\Fure\Error;
use Yume\Fure\Logger;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\Reflect;

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
		config( "cli" )->commands->map(
			
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
			function( Int $i, String $class, Config\Config $configs )
			{
				// Check if CommandHandler has implement CommandInterface.
				if( Reflect\ReflectClass::isImplements( $class, CommandInterface::class, $reflect ) )
				{
					// Create new Command instance.
					$command = $reflect->newInstance( $this, $this->logger, $configs );
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
			
		}
		else {
			puts( "{}: {}: Command not found\n", $argument->file, $command );
		}
	}
	
	public function surprice(): Void
	{}
	
}

?>