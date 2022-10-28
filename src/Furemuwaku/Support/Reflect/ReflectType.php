<?php

namespace Yume\Fure\Support\Reflect;

use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionType;
use ReflectionUnionType;

use Yume\Fure\Support;
use Yume\Fure\Util;

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
	public static function allowsNull(): Bool
	{
		// ...
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params $
	 * @params Mixed $reflect
	 *
	 * @return Mixed
	 */
	public static function binding( ? ReflectionType $type, Mixed $value = Null, Mixed &$reflect = Null ): Mixed
	{
		// ...
		if( $type Instanceof ReflectionIntersectionType )
		{
			
		}
		else {
			
			if( $type === Null )
			{
				return( Null );
			}
			
			// Split Split type with |.
			$name = explode( "|", str_replace( "?", "null|", $type->__toString() ) );
			
			//
		}
		return( $value );
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
	public static function getName(): String
	{
		// ...
	}
	
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
	public static function getTypes(): Array
	{
		// ...
	}
	
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
	public static function isBuiltin(): Bool
	{
		// ...
	}
	
}

?>