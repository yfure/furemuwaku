<?php

namespace Yume\Fure\Util\Reflect;

use Fiber;
use ReflectionFiber;

/*
 * ReflectFiber
 *
 * @package Yume\Fure\Util\Reflect
 */
class ReflectFiber {
	
	use \Yume\Fure\Util\Reflect\ReflectDecorator;
	
	/*
	 * Gets the callable used to create the Fiber.
	 *
	 * @access Public Static
	 *
	 * @params Fiber $fiber
	 * @params Mixed &$reflect
	 *
	 * @return Callable
	 */
	public static function getCallable( Fiber $fiber, Mixed &$reflect = Null ): Callable {
		return( $reflect = self::reflect( $fiber, $reflect ) )->getCallable();
	}
	
	/*
	 * Create ReflectionFiber instance.
	 *
	 * @access Private Static
	 *
	 * @params Fiber $fiber
	 * @params Mixed $reflect
	 *
	 * @return ReflectionFiber
	 */
	private static function reflect( Fiber $fiber, Mixed $reflect ): ReflectionFiber {
		if( $reflect Instanceof ReflectionFiber ) {
			if( $reflect->getFiber() === $fiber ) {
				return( $reflect );
			}
		}
		return( new ReflectionFiber( $fiber ) );
	}
	
}

?>