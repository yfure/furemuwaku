<?php

namespace Yume\Fure\CLI\Command;

use Yume\Fure\CLI;
use Yume\Fure\Config;
use Yume\Fure\Error;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\Reflect;

/*
 * Commands
 *
 * @package Yume\Fure\CLI\Command
 *
 * @extends Yume\Fure\Support\Data\Data
 */
class Commands extends Data\Data
{
	
	/*
	 * @inherit Yume\Fure\Support\Data\Data
	 *
	 */
	final public function __construct()
	{
		CLI\CLI::config( fn( Config\Config $configs ) => $configs->commands->map(
			
			/*
			 * Handle mapping command will be register.
			 *
			 * @params Int $i
			 * @params String $class
			 * @params Yume\Fure\Config\Config
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
					$this->data[] = $reflect->newInstance( $configs );
				}
				else {
					throw new Error\ClassImplementationError([ $class, CommandInterface::class ]);
				}
			}
		));
	}
	
}

?>