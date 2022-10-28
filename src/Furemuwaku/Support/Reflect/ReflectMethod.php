<?php

namespace Yume\Fure\Support\Reflect;

/*
 * ReflectMethod
 *
 * @package Yume\Fure\Support\Reflect
 */
abstract class ReflectMethod
{
	
	/*
	 * Returns a dynamically created closure for the function.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Closure
	 */
	public static function getClosure( Mixed &$reflect = Null ): ? Closure
	{
		// ...
	}
	
	/*
	 * Returns a dynamically created closure for the function.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isAnonymous( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Returns a dynamically created closure for the function.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isDisabled( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	
	/*
	 * Gets Attributes.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params String $name
	 * @params Int $flags
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getAttributes( ? String $name = Null, Int $flags = 0, Mixed &$reflect = Null ): Array
	{
		// ...
	}
	
	/*
	 * Returns the scope associated to the closure.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return ReflectionClass
	 */
	public static function getClosureScopeClass( Mixed &$reflect = Null ): ? ReflectionClass
	{
		// ...
	}
	
	/*
	 * Returns this pointer bound to closure.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Object
	 */
	public static function getClosureThis( Mixed &$reflect = Null ): ? Object
	{
		// ...
	}
	
	/*
	 * Returns an array of the used variables in the Closure.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getClosureUsedVariables( Mixed &$reflect = Null ): Array
	{
		// ...
	}
	
	/*
	 * Gets doc comment.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return False|String
	 */
	public static function getDocComment( Mixed &$reflect = Null ): False | String
	{
		// ...
	}
	
	/*
	 * Gets end line number.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return False|Int
	 */
	public static function getEndLine( Mixed &$reflect = Null ): False | Int
	{
		// ...
	}
	
	/*
	 * Gets extension info.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return ReflectionExtension
	 */
	public static function getExtension( Mixed &$reflect = Null ): ? ReflectionExtension
	{
		// ...
	}
	
	/*
	 * Gets extension name.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return False|String
	 */
	public static function getExtensionName( Mixed &$reflect = Null ): False | String
	{
		// ...
	}
	
	/*
	 * Gets file name.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return False|String
	 */
	public static function getFileName( Mixed &$reflect = Null ): False | String
	{
		// ...
	}
	
	/*
	 * Gets function name.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return String
	 */
	public static function getName( Mixed &$reflect = Null ): String
	{
		// ...
	}
	
	/*
	 * Gets namespace name.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return String
	 */
	public static function getNamespaceName( Mixed &$reflect = Null ): String
	{
		// ...
	}
	
	/*
	 * Gets number of parameters.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Int
	 */
	public static function getNumberOfParameters( Mixed &$reflect = Null ): Int
	{
		// ...
	}
	
	/*
	 * Gets number of required parameters.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Int
	 */
	public static function getNumberOfRequiredParameters( Mixed &$reflect = Null ): Int
	{
		// ...
	}
	
	/*
	 * Gets parameters.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getParameters( Mixed &$reflect = Null ): Array
	{
		// ...
	}
	
	/*
	 * Gets the specified return type of a function.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return ReflectionType
	 */
	public static function getReturnType( Mixed &$reflect = Null ): ? ReflectionType
	{
		// ...
	}
	
	/*
	 * Gets function short name.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return String
	 */
	public static function getShortName( Mixed &$reflect = Null ): String
	{
		// ...
	}
	
	/*
	 * Gets starting line number.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return False|Int
	 */
	public static function getStartLine( Mixed &$reflect = Null ): False | Int
	{
		// ...
	}
	
	/*
	 * Gets static variables.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getStaticVariables( Mixed &$reflect = Null ): Array
	{
		// ...
	}
	
	/*
	 * Returns the tentative return type associated with the function.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return ReflectionType
	 */
	public static function getTentativeReturnType( Mixed &$reflect = Null ): ? ReflectionType
	{
		// ...
	}
	
	/*
	 * Checks if the function has a specified return type.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function hasReturnType( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Returns whether the function has a tentative return type.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function hasTentativeReturnType( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if function in namespace.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function inNamespace( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if closure.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isClosure( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if deprecated.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isDeprecated( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Returns whether this function is a generator.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isGenerator( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if is internal.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isInternal( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if user defined.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isUserDefined( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if the function is variadic.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isVariadic( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if returns reference.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function returnsReference( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	
	/*
	 * Gets declaring class for the reflected method.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function getDeclaringClass( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Gets the method modifiers.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function getModifiers( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Gets the method prototype (if there is one).
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function getPrototype( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Returns whether a method has a prototype.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function hasPrototype( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Invoke.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function invoke( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks if method is abstract.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isAbstract( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks if method is a constructor.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isConstructor( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks if method is a destructor.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isDestructor( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks if method is final.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isFinal( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks if method is private.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isPrivate( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks if method is protected.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isProtected( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks if method is public.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isPublic( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks if method is static.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isStatic( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Set method accessibility.
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function setAccessible( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Create ReflectionMethod instance.
	 *
	 * @access Private Static
	 *
	 * @params  $method
	 * @params Mixed $reflect
	 *
	 * @return ReflectionMethod
	 */
	private static function reflect( $method, Mixed $reflect ): ReflectionMethod
	{
	}
	
}

?>