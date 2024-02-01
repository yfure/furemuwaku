<?php

namespace Yume\Fure\Util\Reflect;

use ReflectionEnumBackedCase;

/*
 * ReflectEnumBacked
 *
 * @package Yume\Fure\Util\Reflect
 * 
 * @extends Yume\Fure\Util\Reflect\ReflectEnumUnit
 */
final class ReflectEnumBacked extends ReflectEnumUnit {
	
	/*
	 * Gets the scalar value backing this Enum case.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $constant
	 * @params Mixed &$reflect
	 *
	 * @return Int|String
	 */
	public static function getBackingValue( Object | String $class, String $constant, Mixed &$reflect = Null ): Int | String {
		return( $reflect = self::reflect( $class, $constant, $reflect ) )->getBackingValue();
	}
	
	/*
	 * Create ReflectionEnumBackedCase instance.
	 *
	 * @access Private Static
	 *
	 * @params Object|String $class
	 * @params String $constant
	 * @params Mixed $reflect
	 *
	 * @return ReflectionEnumBackedCase
	 */
	private static function reflect( Object | String $class, String $constant, Mixed $reflect ): ReflectionEnumBackedCase {
		if( $reflect Instanceof ReflectionEnumBackedCase ) {
			if( $reflect->name === $constant &&
				$reflect->class === is_object( $class ) ? $class::class : $class ) {
				return( $reflect );
			}
		}
		return( new ReflectionEnumBackedCase( $class, $constant ) );
	}
	
}

?>