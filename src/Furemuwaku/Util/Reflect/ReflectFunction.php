<?php

namespace Yume\Fure\Util\Reflect;

use Closure;

use ReflectionClass;
use ReflectionException;
use ReflectionExtension;
use ReflectionFunction;
use ReflectionType;

use Yume\Fure\Error;

/*
 * ReflectFunction
 *
 * @package Yume\Fure\Util\Reflect
 */
abstract class ReflectFunction
{
	
	/*
	 * Gets Attributes.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params String $name
	 * @params Int $flags
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getAttributes( Closure | String $function, ? String $name = Null, Int $flags = 0, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getAttributes( $name, $flags );
	}
	
	/*
	 * Returns a dynamically created closure for the function.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Closure
	 */
	public static function getClosure( Closure | String $function, Mixed &$reflect = Null ): ? Closure
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getClosure();
	}
	
	/*
	 * Returns the scope associated to the closure.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return ReflectionClass
	 */
	public static function getClosureScopeClass( Closure | String $function, Mixed &$reflect = Null ): ? ReflectionClass
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getClosureScopeClass();
	}
	
	/*
	 * Returns this pointer bound to closure.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Object
	 */
	public static function getClosureThis( Closure | String $function, Mixed &$reflect = Null ): ? Object
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getClosureScopeThis();
	}
	
	/*
	 * Returns an array of the used variables in the Closure.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getClosureUsedVariables( Closure | String $function, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getClosureUsedVariables();
	}
	
	/*
	 * Gets doc comment.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return False|String
	 */
	public static function getDocComment( Closure | String $function, Mixed &$reflect = Null ): False | String
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getDocComment();
	}
	
	/*
	 * Gets end line number.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return False|Int
	 */
	public static function getEndLine( Closure | String $function, Mixed &$reflect = Null ): False | Int
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getEndLine();
	}
	
	/*
	 * Gets extension info.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return ReflectionExtension
	 */
	public static function getExtension( Closure | String $function, Mixed &$reflect = Null ): ? ReflectionExtension
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getExtension();
	}
	
	/*
	 * Gets extension name.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return False|String
	 */
	public static function getExtensionName( Closure | String $function, Mixed &$reflect = Null ): False | String
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getExtensionName();
	}
	
	/*
	 * Gets file name.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return False|String
	 */
	public static function getFileName( Closure | String $function, Mixed &$reflect = Null ): False | String
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getFileName();
	}
	
	/*
	 * Gets function name.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return String
	 */
	public static function getName( Closure | String $function, Mixed &$reflect = Null ): String
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getName();
	}
	
	/*
	 * Gets namespace name.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return String
	 */
	public static function getNamespaceName( Closure | String $function, Mixed &$reflect = Null ): String
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getNamespaceName();
	}
	
	/*
	 * Gets number of parameters.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Int
	 */
	public static function getNumberOfParameters( Closure | String $function, Mixed &$reflect = Null ): Int
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getNumberOfParameters();
	}
	
	/*
	 * Gets number of required parameters.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Int
	 */
	public static function getNumberOfRequiredParameters( Closure | String $function, Mixed &$reflect = Null ): Int
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getNumberOfRequiredParameters();
	}
	
	/*
	 * Gets parameters.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getParameters( Closure | String $function, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getParameters();
	}
	
	/*
	 * Gets the specified return type of a function.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return ReflectionType
	 */
	public static function getReturnType( Closure | String $function, Mixed &$reflect = Null ): ? ReflectionType
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getReturnType();
	}
	
	/*
	 * Gets function short name.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return String
	 */
	public static function getShortName( Closure | String $function, Mixed &$reflect = Null ): String
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getShortName();
	}
	
	/*
	 * Gets starting line number.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return False|Int
	 */
	public static function getStartLine( Closure | String $function, Mixed &$reflect = Null ): False | Int
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getStartLine();
	}
	
	/*
	 * Gets static variables.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getStaticVariables( Closure | String $function, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getStaticVariables();
	}
	
	/*
	 * Returns the tentative return type associated with the function.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return ReflectionType
	 */
	public static function getTentativeReturnType( Closure | String $function, Mixed &$reflect = Null ): ? ReflectionType
	{
		return( $reflect = self::reflect( $function, $reflect ) )->getTentativeReturnType();
	}
	
	/*
	 * Checks if the function has a specified return type.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function hasReturnType( Closure | String $function, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $function, $reflect ) )->hasReturnType();
	}
	
	/*
	 * Returns whether the function has a tentative return type.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function hasTentativeReturnType( Closure | String $function, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $function, $reflect ) )->hasTentativeReturnType();
	}
	
	/*
	 * Checks if function in namespace.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function inNamespace( Closure | String $function, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $function, $reflect ) )->inNamespace();
	}
	
	/*
	 * Returns a dynamically created closure for the function.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Mixed
	 */
	public static function invoke( Closure | String $function, Array $arguments = [], Mixed &$reflect = Null ): Mixed
	{
		// Get ReflectionFunction class.
		$reflect = self::reflect( $function, $reflect );
		
		// Return the result of the called function.
		return( $reflect->invoke(
			
			// Build parameters for functions.
			...ReflectParameter::builder( $reflect->getParameters(), $arguments )
		));
	}
	
	/*
	 * Returns a dynamically created closure for the function.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isAnonymous( Closure | String $function, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $function, $reflect ) )->isAnonymous();
	}
	
	/*
	 * Checks if closure.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isClosure( Closure | String $function, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $function, $reflect ) )->isClosure();
	}
	
	/*
	 * Checks if deprecated.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isDeprecated( Closure | String $function, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $function, $reflect ) )->isDeprecated();
	}
	
	/*
	 * Returns a dynamically created closure for the function.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isDisabled( Closure | String $function, Mixed &$reflect = Null ): Bool
	{
		throw new Error\DeprecationError( __METHOD__, Error\DeprecationError::METHOD_ERROR );
	}
	
	/*
	 * Returns whether this function is a generator.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isGenerator( Closure | String $function, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $function, $reflect ) )->isGenerator();
	}
	
	/*
	 * Checks if is internal.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isInternal( Closure | String $function, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $function, $reflect ) )->isInternal();
	}
	
	/*
	 * Checks if user defined.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isUserDefined( Closure | String $function, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $function, $reflect ) )->isUserDefined();
	}
	
	/*
	 * Checks if the function is variadic.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isVariadic( Closure | String $function, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $function, $reflect ) )->isVariadic();
	}
	
	/*
	 * Checks if returns reference.
	 *
	 * @access Public Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function returnsReference( Closure | String $function, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $function, $reflect ) )->returnsReference();
	}
	
	/*
	 * Create ReflectionFunction instance.
	 *
	 * @access Private Static
	 *
	 * @params Closure|String $function
	 * @params Mixed $reflect
	 *
	 * @return ReflectionFunction
	 */
	private static function reflect( Closure | String $function, Mixed $reflect ): ReflectionFunction
	{
		// Check if `reflect` is instanceof ReflectionFunction.
		if( $reflect Instanceof ReflectionFunction )
		{
			if( is_string( $function ) && $reflect->getName() === $function ||
				is_callable( $function ) && $reflect->getClosure() === $function )
			{
				return( $reflect );
			}
		}
		try
		{
			return( new ReflectionFunction( $function ) );
		}
		catch( ReflectionException $e )
		{
			if( preg_match( "/^Function\s[^\s]*\sdoes\snot\sexist$/i", $e->getMessage() ) )
			{
				$e = new Error\FunctionError( $function, Error\FunctionError::NAME_ERROR, $e );
			}
			else {
				$e = new Error\FunctionError( $e->getMessage(), previous: $e );
			}
			throw $e;
		}
	}
	
}

?>