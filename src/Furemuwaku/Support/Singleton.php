<?php

namespace Yume\Fure\Support;

use RuntimeException;

/*
 * Singleton
 *
 * @package Yume\Fure\Support
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
	 * @access Protected Initialize
	 *
	 * @return Void
	 */
	protected function __construct()
	{}
	
	/*
	 * Prevent the instance from being cloned.
	 *
	 * @access Protected
	 *
	 * @return Void
	 */
	final protected function __clone(): Void
	{}
	
	/*
	 * Prevent from being unserialized.
	 * Or which would create a second instance of it.
	 *
	 * @access Public
	 *
	 * @return Void
	 *
	 * @throws RuntimeException
	 */
	final public function __wakeup(): Void
	{
		throw new RuntimeException( sprintf( "Cannot unserialize %s", $this::class ) );
	}
	
	/*
	 * Gets the instance via lazy initialization.
	 *
	 * @access Public Static
	 *
	 * @params Mixed ...$args
	 *
	 * @return Static
	 */
	final public static function self( Mixed ...$args ): Static
	{
		return( static::$instances[static::class] ??= new Static( ...$args ) );
	}
	
}

?>