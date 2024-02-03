<?php

namespace Yume\Fure\Util\Reflect;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use ReflectionType;

use Yume\Fure\Error;

/*
 * ReflectProperty
 *
 * @package Yume\Fure\Util\Reflect
 */
final class ReflectProperty {
	
	/*
	 * Gets Attributes
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params String $name
	 * @params Int $flags
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getAttributes( Object | String $class, String $property, ? String $name = Null, Int $flags = 0, Mixed &$reflect = Null ): Array {
		return( $reflect = self::reflect( $class, $property, $reflect ) )->getAttributes( $name, $flags );
	}
	
	/*
	 * Gets declaring class
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $reflect
	 *
	 * @return ReflectionClass
	 */
	public static function getDeclaringClass( Object | String $class, String $property, Mixed &$reflect = Null ): ReflectionClass {
		return( $reflect = self::reflect( $class, $property, $reflect ) )->getDeclaringClass();
	}
	
	/*
	 * Returns the default value declared for a property
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $reflect
	 *
	 * @return Mixed
	 */
	public static function getDefaultValue( Object | String $class, String $property, Mixed &$reflect = Null ): Mixed {
		return( $reflect = self::reflect( $class, $property, $reflect ) )->getDefaultValue();
	}
	
	/*
	 * Gets the property doc comment
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $reflect
	 *
	 * @return False|String
	 */
	public static function getDocComment( Object | String $class, String $property, Mixed &$reflect = Null ): False | String {
		return( $reflect = self::reflect( $class, $property, $reflect ) )->getDocComment();
	}
	
	/*
	 * Gets the property modifiers
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $reflect
	 *
	 * @return Int
	 */
	public static function getModifiers( Object | String $class, String $property, Mixed &$reflect = Null ): Int {
		return( $reflect = self::reflect( $class, $property, $reflect ) )->getModifiers();
	}
	
	/*
	 * Gets property name.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $reflect
	 *
	 * @return String
	 */
	public static function getName( Object | String $class, String $property, Mixed &$reflect = Null ): String {
		return( $reflect = self::reflect( $class, $property, $reflect ) )->getName();
	}
	
	/*
	 * Gets a property's type.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $reflect
	 *
	 * @return ReflectionType
	 */
	public static function getType( Object | String $class, String $property, Mixed &$reflect = Null ): ? ReflectionType {
		return( $reflect = self::reflect( $class, $property, $reflect ) )->getType();
	}
	
	/*
	 * Gets value.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $reflect
	 *
	 * @return Mixed
	 */
	public static function getValue( Object | String $class, String $property, Mixed &$reflect = Null ): Mixed {
		return( $reflect = self::reflect( $class, $property, $reflect ) )->getValue();
	}
	
	/*
	 * Checks if property has a default value declared.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function hasDefaultValue( Object | String $class, String $property, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $class, $property, $reflect ) )->hasDefaultValue();
	}
	
	/*
	 * Checks if property has a type.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function hasType( Object | String $class, String $property, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $class, $property, $reflect ) )->hasType();
	}
	
	/*
	 * Checks if property is a default property.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isDefault( Object | String $class, String $property, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $class, $property, $reflect ) )->isDefault();
	}
	
	/*
	 * Checks whether a property is initialized.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isInitialized( Object | String $class, String $property, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $class, $property, $reflect ) )->isInitialized( is_object( $class ) ? $class : Null );
	}
	
	/*
	 * Checks if property is private.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isPrivate( Object | String $class, String $property, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $class, $property, $reflect ) )->isPrivate();
	}
	
	/*
	 * Checks if property is promoted.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isPromoted( Object | String $class, String $property, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $class, $property, $reflect ) )->isPromoted();
	}
	
	/*
	 * Checks if property is protected.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isProtected( Object | String $class, String $property, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $class, $property, $reflect ) )->isProtected();
	}
	
	/*
	 * Checks if property is public.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isPublic( Object | String $class, String $property, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $class, $property, $reflect ) )->isPublic();
	}
	
	/*
	 * Checks if property is readonly.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isReadOnly( Object | String $class, String $property, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $class, $property, $reflect ) )->isReadonly();
	}
	
	/*
	 * Checks if property is static.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isStatic( Object | String $class, String $property, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $class, $property, $reflect ) )->isStatic();
	}
	
	/*
	 * Set property accessibility.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Bool $accessible
	 * @params Mixed $reflect
	 *
	 * @return Void
	 */
	public static function setAccessible( Object | String $class, String $property, Bool $accessible, Mixed &$reflect = Null ): Void {
		$reclass = Null;
		if( ReflectClass::isSingleton( $class, $reclass ) ) {
			throw new Error\PropertyError( [ $reclass->name, $property ], Error\PropertyError::ACCESS_ERROR );
		}
		$reflect = self::reflect( $class, $property, $reflect );
		$reflect->setAccessible( $accessible );
	}
	
	/*
	 * Set property value.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $value
	 * @params Mixed $reflect
	 *
	 * @return Void
	 */
	public static function setValue( Object | String $class, String $property, Mixed $value, Mixed &$reflect = Null ): Void {
		$reclass = Null;
		if( ReflectClass::isSingleton( $class, $reclass ) ) {
			throw new Error\PropertyError( [ $reclass->name, $property ], Error\PropertyError::ACCESS_ERROR );
		}
		if( self::isStatic( $class, $property, $reflect ) ) {
			$reflect->setValue( $value );
		}
		else {
			$reflect->setValue( $class, $value );
		}
	}
	
	/*
	 * Create ReflectionProperty instance.
	 *
	 * @access Private Static
	 *
	 * @params Object|String $class
	 * @params String $property
	 * @params Mixed $reflect
	 *
	 * @return ReflectionProperty
	 */
	private static function reflect( Object | String $class, String $property, Mixed $reflect ): ReflectionProperty {
		if( $reflect Instanceof ReflectionProperty ) {
			$object = is_object( $class ) ? $class::class : $class;
			if( $reflect->getDeclaringClass()->getName() === $object && $reflect->getName() === $property ) {
				return( $reflect );
			}
		}
		try {
			return( new ReflectionProperty( $class, $property ) );
		}
		catch( ReflectionException $e ) {
			if( preg_match( "/^Property\s[^\s]*\sdoes\snot\sexist$/i", $e->getMessage() ) ) {
				$e = new Error\PropertyError( [ $class, $property ], Error\PropertyError::NAME_ERROR, $e );
			}
			else {
				$e = new Error\MethodError( $e->getMessage(), previous: $e );
			}
			throw $e;
		}
	}
	
}

?>