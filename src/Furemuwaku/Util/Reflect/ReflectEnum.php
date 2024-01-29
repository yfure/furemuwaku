<?php

namespace Yume\Fure\Util\Reflect;

use ReflectionEnum;
use ReflectionEnumUnitCase;
use ReflectionNamedType;

use Yume\Fure\Error;

/*
 * ReflectEnum
 *
 * @package Yume\Fure\Util\Reflect
 * 
 * @extends Yume\Fure\Util\Reflect\ReflectClass
 */
final class ReflectEnum extends ReflectClass {
	
	/*
	 * Gets the backing type of an Enum, if any.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $enum
	 * @params Mixed &$reflect
	 *
	 * @return ReflectionNamedType
	 */
	public static function getBackingType( Object | String $enum, Mixed &$reflect = Null ): ? ReflectionNamedType {
		return( $reflect = self::reflect( $enum, $reflect ) )->getBackingType();
	}
	
	/*
	 * Returns a specific case of an Enum.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $enum
	 * @params String $name
	 * @params Mixed &$reflect
	 *
	 * @return Int|String
	 */
	public static function getCase( Object | String $enum, String $name, Mixed &$reflect = Null ): ReflectionEnumUnitCase {
		return( $reflect = self::reflect( $enum, $reflect ) )->getCase( $name );
	}
	
	/*
	 * Returns a list of all cases on an Enum.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $enum
	 * @params Mixed &$reflect
	 *
	 * @return Array
	 */
	public static function getCases( Object | String $enum, Mixed &$reflect = Null ): Array {
		return( $reflect = self::reflect( $enum, $reflect ) )->getCases();
	}
	
	/*
	 * Checks for a case on an Enum.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $enum
	 * @params String $name
	 * @params Mixed &$reflect
	 *
	 * @return Bool
	 */
	public static function hasCase( Object | String $enum, String $name, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $enum, $reflect ) )->hasCase( $name );
	}
	
	/*
	 * Determines if an Enum is a Backed Enum.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $enum
	 * @params Mixed &$reflect
	 *
	 * @return Bool
	 */
	public static function isBacked( Object | String $enum, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $enum, $reflect ) )->isBacked();
	}
	
	/*
	 * Create ReflectionEnum instance.
	 *
	 * @access Private Static
	 *
	 * @params Object|String $enum
	 * @params Mixed $reflect
	 *
	 * @return ReflectionEnum
	 */
	private static function reflect( Object | String $enum, Mixed $reflect ): ReflectionEnum {
		throw new Error\MethodError( [ ReflectEnum::class, "reflect" ], Error\MethodError::IMPLEMENTS_ERROR );
	}
	
}

?>