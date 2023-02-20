<?php

namespace Yume\Fure\CLI\Command;

use Yume\Fure\Support\Reflect;

/*
 * Command
 *
 * @package Yume\Fure\CLI\Command
 */
abstract class Command implements CommandInterface
{
	
	/*
	 * Command name.
	 *
	 * @access Protected
	 *
	 * @values String
	 */
	protected String $command;
	
	/*
	 * Construct method of class Command.
	 *
	 * @access Public Instance
	 *
	 * @return Void
	 */
	public function __construct()
	{
		if( Reflect\ReflectProperty::isInitialized( $this, "command" ) === False )
		{
			throw new CommandUnitializedCNameError( $this::class );
		}
	}
	
	/*
	 * @inherit Yume\Fure\CLI\Command\CommandInterface
	 *
	 */
	abstract public function run( Array $args ): Void;
	
}

?>