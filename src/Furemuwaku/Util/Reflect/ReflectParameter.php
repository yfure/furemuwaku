<?php

namespace Yume\Fure\Util\Reflect;

use ArrayAccess;

use ReflectionClass;
use ReflectionException;
use ReflectionFunctionAbstract;
use ReflectionParameter;
use ReflectionType;

use Yume\Fure\Error;
use Yume\Fure\Support;
use Yume\Fure\Util;
use Yume\Fure\Util\Json;

/*
 * ReflectParameter
 *
 * @package Yume\Fure\Util\Reflect
 */
final class ReflectParameter {
	
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
	public static function allowsNull( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $function, $param, $reflect ) )->allowsNull();
	}
	
	/*
	 * Parameter builder.
	 *
	 * @access Public Static
	 *
	 * @params Array $parameter
	 * @params Array $arguments
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function builder( Array $parameter, Array | ArrayAccess $arguments = [], Mixed &$reflect = Null ): Array {
		// Reflection instance references.
		$reflect = new Support\Data([]);
		
		// Parameter binding stack.
		$binding = [];
		
		/*
		 * Mapping parameters.
		 *
		 * @params Int $i
		 * @params Int|String $idx
		 * @params Mixed $param
		 *
		 * @scopes $reflect
		 * @scopes $binding
		 * @scopes $arguments
		 *
		 * @return Void
		 */
		Util\Arrays::map( $parameter, function( Int $i, Int | String $idx, Mixed $param ) use( &$reflect, &$binding, $arguments ): Void {
			static $error = "Cannot build parameters, the value of the argument \$parameter must be a valid array <ReflectionParameter>|<Callable<ReflectionParameter>>, {} passed in the array element \$parameter<Array[{}]>";
			
			//<Function<Array>|<Object>|<String>>
			//<Parameter<Int>|<String>>
			//<Reflection<Mixed>>
			
			if( is_array( $param ) ) {
				$param = array_values( $param );
				if( count( $param ) >= 2 ) {
					$param = self::reflect( $param[0], $param[1], $param[3] ?? False );
				}
				else {
					throw new Error\ValueError( f( $error, f( "Array{}", Json\Json::encode( $param, JSON_INVALID_UTF8_SUBSTITUTE ) ), $idx ) );
				}
			}
			else if( $param Instanceof ReflectionParameter ) {
			}
			else {
				throw new Error\ValueError( f( $error, type( $param ), $idx ) );
			}
			$pos = $param->getPosition();
			$name = $param->getName();
			
			// Get value by parameter name or position.
			$binding[$name] = $arguments[$pos] ?? $arguments[$name] ?? ( $param->isDefaultValueAvailable() ? $param->getDefaultValue() : Null );
			$reflect[$name] = $param;
			
			if( $param->hasType() ) {
				$ntyped = $param->getType();
				$binding[$name] = ReflectType::binding( $binding[$name], $ntyped );
				if( $binding[$name] === Null && $ntyped->allowsNull() === False ) {
					throw new Error\ParameterError( [ $param->getDeclaringFunction()->name, $name, $ntyped->getName() ], Error\ParameterError::REQUIRE_ERROR );
				}
			}
		});
		return( $binding );
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
	public static function canBePassedByValue( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $function, $param, $reflect ) )->canBePassedByValue();
	}
	
	/*
	 * Parameter formater.
	 *
	 * @access Private Static
	 *
	 * @params Mixed $function
	 * @params Int|String $param
	 *
	 * @return String
	 */
	private static function format( Mixed &$function, Int | String $param ): String {
		$format = new Support\Data([
			"class" => "*",
			"function" => "*",
			"parameter" => $param
		]);
		if( is_string( $function ) ) {
			$function = explode( "::", $function );
			$function = match( count( $function ) ) {

				// Valid function name.
				1,
				2 => $function,
				
				// If function is invalid value.
				0 => throw new Error\AssertionError( "Invalid function name" )
			};
		}
		if( is_array( $function ) ) {
			if( count( $function ) === 2 ) {
				if( is_callable( $function[0] ) ) {
					$format->class = "Closure";
					$format->function = "{closure}";
					
					$function = $function[0];
				}
				else {
					if( is_object( $function[0] ) ) {
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
				if( is_object( $function[0] ) ) {
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
		else if( is_object( $function ) ) {
			$format->class = $function::class;
			$format->function = $function::class === "Closure" ? "{closure}" : "__invoke";
		}
		else if( is_string( $function ) ) {
			$format->class = "Declared";
			$format->function = $function;
		}
		return( Util\Strings::format( "{ class }::{ function }(:{ parameter })", ...$format->__toArray() ) );
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
	public static function getAttributes( Array | Object | String $function, Int | String $param, ? String $name = Null, Int $flags = 0, Mixed &$reflect = Null ): Array {
		return( $reflect = self::reflect( $function, $param, $reflect ) )->getAttributes( $name, $flags );
	}
	
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
	public static function getDeclaringClass( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): ? ReflectionClass {
		return( $reflect = self::reflect( $function, $param, $reflect ) )->getDeclaringClass();
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
	public static function getDeclaringFunction( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): ReflectionFunctionAbstract {
		return( $reflect = self::reflect( $function, $param, $reflect ) )->getDeclaringFunction();
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
	public static function getDefaultValue( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Mixed {
		return( $reflect = self::reflect( $function, $param, $reflect ) )->getDefaultValue();
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
	public static function getDefaultValueConstantName( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): ? String {
		return( $reflect = self::reflect( $function, $param, $reflect ) )->getDefaultValueConstantName();
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
	public static function getName( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): String {
		return( $reflect = self::reflect( $function, $param, $reflect ) )->getName();
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
	public static function getPosition( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Int {
		return( $reflect = self::reflect( $function, $param, $reflect ) )->getPosition();
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
	public static function getType( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): ? ReflectionType {
		return( $reflect = self::reflect( $function, $param, $reflect ) )->getType();
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
	public static function hasType( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $function, $param, $reflect ) )->hasType();
	}
	
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
	public static function isDefaultValueAvailable( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $function, $param, $reflect ) )->isDefaultValueAvailable();
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
	public static function isDefaultValueConstant( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $function, $param, $reflect ) )->isDefaultValueConstant();
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
	public static function isOptional( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $function, $param, $reflect ) )->isOptional();
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
	public static function isPassedByReference( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $function, $param, $reflect ) )->isPassedByReference();
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
	public static function isVariadic( Array | Object | String $function, Int | String $param, Mixed &$reflect = Null ): Bool {
		return( $reflect = self::reflect( $function, $param, $reflect ) )->isVariadic();
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
	private static function reflect( Array | Object | String $function, Int | String $param, Mixed $reflect ): ReflectionParameter {
		if( $reflect Instanceof ReflectionParameter ) {
			$format = "{ class }::{ function }(:{ parameter })";
			$reflectFunc = $reflect->getDeclaringFunction();
			$reflectClass = $reflect->getDeclaringClass();
			$reflectFormat = Util\Strings::format( $format, ...[
				"class" => $reflectClass ? $reflectClass->getName() : "Anonymous",
				"function" => $reflectFunc->getName(),
				"parameter" => match( is_int( $param ) ) {
					True => $reflect->getPosition(),
					False => $reflect->getName()
				}
			]);
			$functFormat = self::format( $function, $param );
			if( $functFormat === $reflectFormat ) {
				return( $reflect );
			}
		}
		try {
			return( new ReflectionParameter( $function, $param ) );
		}
		catch( ReflectionException $e ) {
			throw new Error\ParameterError( [ $function, $param ], Error\ParameterError::NAME_ERROR, $e );
		}
	}
	
}

?>