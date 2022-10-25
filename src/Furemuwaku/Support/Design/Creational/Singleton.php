<?php

namespace Yume\Fure\Support\Design\Creational;

use Yume\Fure\Error;
use Yume\Fure\Support;

/*
 * Singleton
 *
 * @package Yume\Fure\Support\Design\Creational
 */
abstract class Singleton
{
	
	/*
	 * Singleton class instance stack.
	 *
	 * @access Static Private
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	static private ? Support\Data\DataInterface $instances = Null;
	
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
		throw new Error\RuntimeError( f( "Cannot unserialize {}.", $this::class ) );
	}
	
	/*
	 * Gets the instance via lazy initialization.
	 *
	 * @access Public Static
	 *
	 * @return Yume\Fure\Support\Design\Creational\Singleton
	 */
	final public static function self(): Singleton
	{
		// If singleton stack is not created.
		if( static::$instances Instanceof Support\Data\DataInterface === False )
		{
			static::$instances = new Support\Data\Data([
				static::class => new Static( ...func_get_args() )
			]);
		}
		else {
			
			// Current singleton class is not exists.
			if( static::$instances->__isset( static::class ) === False )
			{
				static::$instances->__set( static::class, new Static( ...func_get_args() ) );
			}
		}
		return( static::$instances )->__get( static::class );
	}
	
}

?>