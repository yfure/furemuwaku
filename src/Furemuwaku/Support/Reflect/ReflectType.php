<?php

namespace Yume\Fure\Support\Reflect;

use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionType;
use ReflectionUnionType;
use Reflector;

use Yume\Fure\Support\Services;
use Yume\Fure\Util\RegExp;

/*
 * ReflectType
 *
 * @package Yume\Fure\Support\Reflect
 */
abstract class ReflectType
{
	
	/*
	 * Checks if null is allowed.
	 *
	 * @access Public Static
	 *
	 * @params $
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	abstract public static function allowsNull(): Bool;
	
	/*
	 * Value binding.
	 *
	 * @access Public Static
	 *
	 * @params Mixed $value
	 * @params ReflectionType $reflect
	 *
	 * @return Mixed
	 */
	public static function binding( Mixed $value = Null, ? ReflectionType $reflect = Null ): Mixed
	{
		// If `reflect` is ReflectionType instance.
		if( $reflect Instanceof ReflectionType )
		{
			// Split type name with |.
			$types = explode( "|", str_replace( [ "?", "&" ], [ "null|", "|" ], $reflect->__toString() ) );
			
			// Get type given.
			$given = ucfirst( gettype( $value ) );
			
			foreach( $types As $type )
			{
				// Remove interface name.
				$type = RegExp\RegExp::replace( "/Interface$/i", ucfirst( $type ), "" );
				
				// Check if the type name is not Mixed.
				if( $type !== "Mixed" )
				{
					// If the given type is the same as the required one.
					if( $type === $given || 
						$type === "Int" && $given === "Integer" || 
						$type === "Bool" && $given === "Boolean" || 
						$type === "True" && $given === True ||
						$type === "False" && $value === False || 
						$type === "Closure" && $given === "Callable" )
					{
						return( $value );
					}
					
					// If the type is String and the given type is Boolean, Integer, or Float.
					if( $type === "String" && $given === "Boolean" || $given === "Float" || $given === "Integer" ) return( $value );
					
					// If the given type is object and if the required
					// type is the same as the given instance name.
					if( $given === "Object" && $type === $value::class ) return( $value );
					
					// Check if the class name has been bound in the application.
					if( $binded = Services\Services::app()->binded( $type ) ) return( $binded );
				}
				else {
					return( $value );
				}
			}
		}
		return( Null );
	}
	
	/*
	 * Get the name of the type as a string.
	 *
	 * @access Public Static
	 *
	 * @params $
	 * @params Mixed $reflect
	 *
	 * @return String
	 */
	abstract public static function getName(): String;
	
	/*
	 * Returns the types included in the union type.
	 *
	 * @access Public Static
	 *
	 * @params $
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	abstract public static function getTypes(): Array;
	
	/*
	 * Checks if it is a built-in type.
	 *
	 * @access Public Static
	 *
	 * @params $
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	abstract public static function isBuiltin(): Bool;
	
}

?>