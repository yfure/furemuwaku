<?php

namespace Yume\Fure\Support\Reflect;

use ReflectionClass;
use ReflectionFunctionAbstract;
use ReflectionIntersectionType;
use ReflectionParameter;
use ReflectionType;

use Yume\Fure\Support;
use Yume\Fure\Util;

/*
 * ReflectParameter
 *
 * @package Yume\Fure\Support\Reflect
 */
abstract class ReflectParameter
{
	
	/*
	 * Checks if null is allowed
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function allowsNull( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Returns whether this parameter can be passed by value
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function canBePassedByValue( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Gets Attributes
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params String $name
	 * @params Int $flags
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getAttributes( Array | Object | String $function, Int | String $param, ? String $name = Null, Int $flags = 0, Mixed &$reflect = Null ): Array
	{
		// ...
	}
	
	/*
	 * Get a ReflectionClass object for the parameter being reflected or null
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return ReflectionClass
	 */
	abstract public static function getClass( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): ? ReflectionClass;
	
	/*
	 * Gets declaring class
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return ReflectionClass
	 */
	public static function getDeclaringClass( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): ? ReflectionClass
	{
		// ...
	}
	
	/*
	 * Gets declaring function
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return ReflectionFunctionAbstract
	 */
	public static function getDeclaringFunction( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): ReflectionFunctionAbstract
	{
		// ...
	}
	
	/*
	 * Gets default parameter value
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return Mixed
	 */
	public static function getDefaultValue( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Mixed
	{
		// ...
	}
	
	/*
	 * Returns the default value's constant name if default value is constant or null
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return String
	 */
	public static function getDefaultValueConstantName( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): ? String
	{
		// ...
	}
	
	/*
	 * Gets parameter name
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return String
	 */
	public static function getName( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): String
	{
		// ...
	}
	
	/*
	 * Gets parameter position
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return Int
	 */
	public static function getPosition( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Int
	{
		// ...
	}
	
	/*
	 * Gets a parameter's type
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return ReflectionType
	 */
	public static function getType( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): ? ReflectionType
	{
		// ...
	}
	
	/*
	 * Checks if parameter has a type
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function hasType( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if parameter expects an array
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	abstract public static function isArray( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Bool;
	
	/*
	 * Returns whether parameter MUST be callable
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	abstract public static function isCallable( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Boo;
	
	/*
	 * Checks if a default value is available
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isDefaultValueAvailable( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Returns whether the default value of this parameter is a constant
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isDefaultValueConstant( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if optional
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isOptional( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if passed by reference
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isPassedByReference( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if the parameter is variadic
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isVariadic( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	public static function builder( Array $parameter, Array $arguments = [], Mixed &$reflect = Null ): Array
	{
		// 
		$reflect = new Support\Data\Data;
		$binding = [];
		
		// Mapping parameters.
		Util\Arr::map( $parameter, function( $i, $key, $params ) use( &$reflect, &$binding, $arguments )
		{
			// Checks whether the array has elements with index 0 or key reflect.	
			if( isset( $params[2] ) === False && isset( $params['reflect'] ) === False )
			{
				// To avoid argument count errors.
				$params['reflect'] = False;
			}
			
			// Get ReflectionParameter instance.
			$params = self::reflect( ...$params );
			
			// Set ReflectionParameter to reference variable.
			$reflect[$params->getName()] = $params;
			
			// Get parameter value.
			$binding[$params->getName()] = ReflectType::binding( $namedType = $params->getType(), $value = $arguments[$params->getName()] ?? $arguments[$params->getPosition()] ?? Null );
			
			// If Null value is not allowed.
			if( $namedType && $namedType->allowsNull() === False && 
				( $value === Null || 
				$binding[$params->getName()] === Null ) )
			{
				unset( $binding[$params->getName()] );
			}
		});
		
		// Return binded parameter.
		return( $binding );
	}
	
	private static function format( &$function, $param ): String
	{
		$format = new Support\Data\Data([
			"class" => "*",
			"function" => "*",
			"parameter" => $param
		]);
		
		// If `function` is String type.
		if( is_string( $function ) )
		{
			// Split function name with ::.
			$function = explode( "::", $function );
			
			// Validate function name.
			$function = match( count( $function ) )
			{
				// Valid function name.
				1 => $function[0],
				2 => $function,
				
				// If function is invalid value.
				0 => throw new Error\Error()
			};
		}
		
		if( is_array( $function ) )
		{
			if( count( $function ) === 2 )
			{
				if( is_callable( $function[0] ) )
				{
					$format->class = "Closure";
					$format->function = "{closure}";
					
					$function = $function[0];
				}
				else {
					if( is_object( $function[0] ) )
					{
						$format->class = $function[0]::class;
						$format->function = $function[0]::class === "Closure" ? "{closure}" : $function[1]::class;
					}
					else {
						$format->class = $function[0];
						$format->function = $function[1];
					}
				}
			}
			else {
				if( is_object( $function[0] ) )
				{
					$format->class = $function[0]::class;
					$format->function = $function[0]::class === "Closure" ? "{closure}" : "__invoke";
				}
				else {
					$format->class = "Declared";
					$format->function = $function[0];
				}
				$function = $function[0];
			}
		}
		else if( is_object( $function ) )
		{
			$format->class = $function::class;
			$format->function = $function::class === "Closure" ? "{closure}" : "__invoke";
		}
		else if( is_string( $function ) )
		{
			$format->class = "Declared";
			$format->function = $function;
		}
		
		return( Util\Str::fmt( "{ class }::{ function }(:{ parameter })", ...$format->__toArray() ) );
	}
	
	/*
	 * Create ReflectionParameter instance.
	 *
	 * @access Private Static
	 *
	 * @params Array|Object|String $function
	 * @params Int|String $param
	 * @params Mixed $reflect
	 *
	 * @return ReflectionParameter
	 */
	private static function reflect( Array | Object | String $function, Int | String $param, Mixed $reflect )//: ReflectionParameter
	{
		// ...
		if( $reflect Instanceof ReflectionParameter )
		{
			// Default export format.
			$format = "{ class }::{ function }(:{ parameter })";
			
			// Get Reflector instance
			$reflectFunc = $reflect->getDeclaringFunction();
			$reflectClass = $reflect->getDeclaringClass();
			
			// Export ReflectionParameter instance.
			$reflectFormat = Util\Str::fmt( $format, ...[
				
				// Function class name.
				"class" => $reflectClass ? $reflectClass->getName() : "Anonymous",
				
				// Function name.
				"function" => $reflectFunc->getName(),
				
				// Function parameter name.
				"parameter" => match( is_int( $param ) )
				{
					True => $reflect->getPosition(),
					False => $reflect->getName()
				}
			]);
			
			// Export Function given.
			$functFormat = self::format( $function, $param );
			
			if( $functFormat === $reflectFormat )
			{
				return( $reflect );
			}
		}
		return( new ReflectionParameter( $function, $param ) );
	}
	
}

?>