<?php

namespace Yume\Fure\Support\Reflect;

use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionType;
use ReflectionUnionType;
use Reflector;

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
	public static function binding( Mixed $value = Null, ? ReflectionType $reflect = Null ): Mixed
	{
		if( $reflect Instanceof ReflectionType )
		{
			if( $reflect Instanceof ReflectionIntersectionType )
			{
				foreach( $reflect->getTypes() As $i => $type )
				{
					if( $type->allowsNull() )
					{
						
					}
				}
			}
			// ...
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