<?php

namespace Yume\Fure\Util\Reflect;

use ReflectionClass;
use ReflectionClassConstant;
use ReflectionEnumUnitCase;
use ReflectionException;

use Yume\Fure\Error;

/*
 * ReflectConstant
 *
 * @package Yume\Fure\Util\Reflect
 */
abstract class ReflectConstant {
	
	/*
	 * Gets Attributes.
	 * 
	 * @access Private Static
	 * 
	 * @params Object|String $class
	 * @params String $constant
	 * @params String $name
	 * @params Int $flags
	 * @params Mixed &$reflect
	 * 
	 * @return Array<ReflectionAttribute>
	 */
	public static function getAttributes( Object | String $class, String $constant, ? String $name = Null, Int $flags = 0, Mixed &$reflect = Null ): Array {
		return( $reflect = self::reflect( $class, $constant, $reflect ) )->getAttributes( $name, $flags );
	}
	
	/*
	 * Gets declaring class.
	 * 
	 * @access Private Static
	 * 
	 * @params Object|String $class
	 * @params String $constant
	 * @params Mixed &$reflect
	 * 
	 * @return ReflectionClass
	 */
	public static function getDeclaringClass( Object | String $class, String $constant, Mixed &$reflect = Null ): ReflectionClass {
		return( $reflect = self::reflect( $class, $constant, $reflect ) )->getDeclaringClass();
	}
	
	/*
	 * Gets doc comments.
	 * 
	 * @access Private Static
	 * 
	 * @params Object|String $class
	 * @params String $constant
	 * @params Mixed &$reflect
	 * 
	 * @return False|String
	 */
	public static function getDocComment( Object | String $class, String $constant, Mixed &$reflect = Null ): False | String {
		return( $reflect = self::reflect( $class, $constant, $reflect ) )->getDocComment();
	}
	
	/*
	 * Gets the class constant modifiers.
	 * 
	 * @access Private Static
	 * 
	 * @params Object|String $class
	 * @params String $constant
	 * @params Mixed &$reflect
	 * 
	 * @return Int
	 */
	public static function getModifiers( Object | String $class, String $constant, Mixed &$reflect = Null ): Int {
		return( $reflect = self::reflect( $class, $constant, $reflect ) )->getModifiers();
	}
	
	/*
	 * Get name of the constant.
	 * 
	 * @access Private Static
	 * 
	 * @params Object|String $class
	 * @params String $constant
	 * @params Mixed &$reflect
	 * 
	 * @return String
	 */
	public static function getName( Object | String $class, String $constant, Mixed &$reflect = Null ): String {
		return( $reflect = self::reflect( $class, $constant, $reflect ) )->getName();
	}
	
	/*
	 * Gets value.
	 * 
	 * @access Private Static
	 * 
	 * @params Object|String $class
	 * @params String $constant
	 * @params Mixed &$reflect
	 * 
	 * @return Mixed
	 */
	public static function getValue( Object | String $class, String $constant, Mixed &$reflect = Null ): Mixed {
		return( $reflect = self::reflect( $class, $constant, $reflect ) )->getValue();
	}
	
	/*
	 * Checks if class constant is an Enum case.
	 * 
	 * @access Private Static
	 * 
	 * @params Object|String $class
	 * @params String $constant
	 * @params Mixed &$reflect
	 * 
	 * @return Bool
	 */
	public static function isEnumCase( Object | String $class, String $constant, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $class, $constant, $reflect ) )->isEnumCase();
	}
	
	/*
	 * Checks if class constant is final.
	 * 
	 * @access Private Static
	 * 
	 * @params Object|String $class
	 * @params String $constant
	 * @params Mixed &$reflect
	 * 
	 * @return Bool
	 */
	public static function isFinal( Object | String $class, String $constant, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $class, $constant, $reflect ) )->isFinal();
	}
	
	/*
	 * Checks if class constant is private.
	 * 
	 * @access Private Static
	 * 
	 * @params Object|String $class
	 * @params String $constant
	 * @params Mixed &$reflect
	 * 
	 * @return Bool
	 */
	public static function isPrivate( Object | String $class, String $constant, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $class, $constant, $reflect ) )->isPrivate();
	}
	
	/*
	 * Checks if class constant is protected.
	 * 
	 * @access Private Static
	 * 
	 * @params Object|String $class
	 * @params String $constant
	 * @params Mixed &$reflect
	 * 
	 * @return Bool
	 */
	public static function isProtected( Object | String $class, String $constant, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $class, $constant, $reflect ) )->isProtected();
	}
	
	/*
	 * Checks if class constant is public.
	 * 
	 * @access Private Static
	 * 
	 * @params Object|String $class
	 * @params String $constant
	 * @params Mixed &$reflect
	 * 
	 * @return Bool
	 */
	public static function isPublic( Object | String $class, String $constant, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $class, $constant, $reflect ) )->isPublic();
	}
	
	/*
	 * Constructs a ReflectionClassConstant.
	 * 
	 * @access Private Static
	 * 
	 * @params Object|String $class
	 * @params String $constant
	 * @params Mixed $reflect
	 * 
	 * @return ReflectionClassConstant|ReflectionEnumUnitCase
	 */
	private static function reflect( Object | String $class, String $constant, Mixed $reflect ): ReflectionClassConstant | ReflectionEnumUnitCase {
		if( $reflect Instanceof ReflectionClassConstant &&
			$reflect->getName() === $constant ) {
			return( $reflect );
		}
		try {
			return( new ReflectionClassConstant( $class, $constant ) );
		}
		catch( ReflectionException $e ) {
			if( preg_match( "/^Constant\s[^\s]+\sdoes\snot\sexist$/", $e->getMessage() ) ) {
				$e = new Error\ConstantError( [ $class, $constant ], Error\ConstantError::NAME_ERROR, $e );
			}
			else {
				$e = new Error\ConstantError( $e->getMessage(), $e->getCode(), $e );
			}
			throw $e;
		}
	}

}

?>