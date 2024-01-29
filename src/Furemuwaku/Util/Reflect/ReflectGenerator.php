<?php

namespace Yume\Fure\Util\Reflect;

use Generator;
use ReflectionFunctionAbstract;
use ReflectionGenerator;

/*
 * ReflectGenerator
 *
 * @package Yume\Fure\Util\Reflect
 */
final class ReflectGenerator {
	
	use \Yume\Fure\Util\Reflect\ReflectDecorator;
	
	/*
	 * Gets the function name of the generator.
	 *
	 * @access Public Static
	 *
	 * @params Generator $generator
	 * @params Mixed &$reflect
	 *
	 * @return ReflectionFunctionAbstract
	 */
	public static function getFunction( Generator $generator, Mixed &$reflect = Null ): ReflectionFunctionAbstract {
		return( $relfect = self::reflect( $generator, $reflect ) )->getFunction();
	}
	
	/*
	 * Gets the $this value of the generator.
	 *
	 * @access Public Static
	 *
	 * @params Generator $generator
	 * @params Mixed &$reflect
	 *
	 * @return Object
	 */
	public static function getThis( Generator $generator, Mixed &$reflect = Null ): ? Object {
		return( $relfect = self::reflect( $generator, $reflect ) )->getThis();
	}
	
	/*
	 * Create ReflectionGenerator instance.
	 *
	 * @access Private Static
	 *
	 * @params Generator $generator
	 * @params Mixed $reflect
	 *
	 * @return ReflectionGenerator
	 */
	private static function reflect( Generator $generator, Mixed $reflect ): ReflectionGenerator {
		if( $reflect Instanceof ReflectionGenerator ) {
			if( $reflect->getExecutingGenerator() === $generator ) {
				return( $reflect );
			}
		}
		return( new ReflectionGenerator( $generator ) );
	}
	
}

?>