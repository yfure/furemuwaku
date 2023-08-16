<?php

namespace Yume\Fure\CLI\Command;

use Yume\Fure\Error;
use Yume\Fure\Logger;
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