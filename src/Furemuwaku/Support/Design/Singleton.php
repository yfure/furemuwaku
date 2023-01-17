<?php

namespace Yume\Fure\Support\Design;

use Yume\Fure\Error;

/*
 * Singleton
 *
 * @package Yume\Fure\Support\Design
 */
abstract class Singleton
{
	
	/*
	 * Singleton class instance stack.
	 *
	 * @access Static Private
	 *
	 * @values Array
	 */
	static private $instances = [];
	
	/*
	 * Construct method of class Singleton.
	 * Is not allowed to call from outside to
	 * prevent from creating multiple instances.
	 *
	 * @access Protected
	 *
	 * @return Void
	 */
	protected function __construct() {}
	
	/*
	 * Prevent the instance from being cloned.
	 *
	 * @access Protected
	 *
	 * @return Void
	 */
	final protected function __clone() {}

	/*
	 * Prevent from being unserialized.
	 * Or which would create a second instance of it.
	 *
	 * @access Public
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\Error\RuntimeError
	 */
	final public function __wakeup()
	{
		throw new Error\RuntimeError( f( "Cannot unserialize {}", $this::class ) );
	}
	
	/*
	 * Gets the instance via lazy initialization.
	 *
	 * @access Public Static
	 *
	 * @return Yume\Fure\Support\Design\Singleton
	 */
	final public static function self(): Singleton
	{
		// Current singleton class is not exists.
		if( isset( static::$instances[static::class] ) === False )
		{
			static::$instances[static::class] = new Static( ...func_get_args() );
		}
		return( static::$instances[static::class] );
	}
	
}

?>