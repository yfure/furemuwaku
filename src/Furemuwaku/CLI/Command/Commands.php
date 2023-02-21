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
					$command = $reflect->newInstance( $this, $this->logger, $config );
					$commandName = $command->name;
					
					// Push command.
					$this->commands[$commandName] = $command;
				}
				else {
					throw new Error\ClassImplementationError([ $class, CommandInterface::class ]);
				}
			}
		);
	}
	
	public function has( String $command ): Bool
	{
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
		// When command value is ArgumentValue class.
		if( $command Instanceof Argument\ArgumentValue ) $command = $command->name;
		
		// Check command is exists.
		if( $this->exists( $command ) )
		{
			
		}
		else {
			// ...
		}
	}
	
}

?>