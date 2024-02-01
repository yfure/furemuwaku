<?php

namespace Yume\Fure\Util\Reflect;

use ReflectionType;

use Yume\Fure\Service;
use Yume\Fure\Util;
use Yume\Fure\Util\RegExp;

/*
 * ReflectType
 *
 * @package Yume\Fure\Util\Reflect
 */
final class ReflectType {
	
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
	public static function binding( Mixed $value = Null, ? ReflectionType $reflect = Null ): Mixed {
		if( $reflect Instanceof ReflectionType ) {
			$types = explode( "|", str_replace( [ "?", "&" ], [ "null|", "|" ], $reflect->__toString() ) );
			$given = type( $value Instanceof Util\Value ? $value = $value->getValue() : $value, disable: True );
			foreach( $types As $type ) {
				$type = RegExp\RegExp::replace( "/Interface$/i", ucfirst( $type ), "" );
				if( $type !== "Mixed" ) {
					if( $type === $given || 
						$type === "Int" && $given === "Integer" || 
						$type === "Bool" && $given === "Boolean" || 
						$type === "True" && $given === True ||
						$type === "False" && $value === False || 
						$type === "Closure" && $given === "Callable" ) {
						return( $value );
					}
					if( $type === "String" && $given === "Boolean" || $given === "Float" || $given === "Integer" ) {
						return( $value );
					}
					
					// If the given type is object and if the required
					// type is the same as the given instance name.
					if( $given === "Object" ) {
						if( $type === $value::class ) {
							return( $value );
						}
					}

					// If class has binded in service container.
					if( Service\Service::available( $type ) ) {
						return( Service\Service::get( $type ) );
					}
				}
				else {
					return( $value );
				}
			}
		}
		return( Null );
	}
	
}

?>