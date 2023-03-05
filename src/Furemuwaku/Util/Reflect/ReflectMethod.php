<?php

namespace Yume\Fure\Util\Reflect;

use Closure;
use Generator;
use ReflectionClass;
use ReflectionMethod;
use ReflectionType;

use Yume\Fure\Error;

/*
 * ReflectMethod
 *
 * @package Yume\Fure\Util\Reflect
 */
abstract class ReflectMethod
{
	
	/*
	 * Gets Attributes.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params String $name
	 * @params Int $flags
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getAttributes( Object | String $class, String $method, ? String $name = Null, Int $flags = 0, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getAttributes( $name, $flags );
	}
	
	/*
	 * Returns a dynamically created closure for the function.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Closure
	 */
	public static function getClosure( Object | String $class, String $method, Mixed &$reflect = Null ): ? Closure
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getClosure();
	}
	
	/*
	 * Returns the scope associated to the closure.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return ReflectionClass
	 */
	public static function getClosureScopeClass( Object | String $class, String $method, Mixed &$reflect = Null ): ? ReflectionClass
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getClosureScopeClass();
	}
	
	/*
	 * Returns this pointer bound to closure.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Object
	 */
	public static function getClosureThis( Object | String $class, String $method, Mixed &$reflect = Null ): ? Object
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getClosureThis();
	}
	
	/*
	 * Returns an array of the used variables in the Closure.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getClosureUsedVariables( Object | String $class, String $method, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getClosureUsedVariables();
	}
	
	/*
	 * Gets doc comment.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return False|String
	 */
	public static function getDocComment( Object | String $class, String $method, Mixed &$reflect = Null ): False | String
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getDocComment();
	}
	
	/*
	 * Gets end line number.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return False|Int
	 */
	public static function getEndLine( Object | String $class, String $method, Mixed &$reflect = Null ): False | Int
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getEndLine();
	}
	
	/*
	 * Gets extension info.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return ReflectionExtension
	 */
	public static function getExtension( Object | String $class, String $method, Mixed &$reflect = Null ): ? ReflectionExtension
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getExtension()();
	}
	
	/*
	 * Gets extension name.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return False|String
	 */
	public static function getExtensionName( Object | String $class, String $method, Mixed &$reflect = Null ): False | String
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getExtensionName();
	}
	
	/*
	 * Gets file name.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return False|String
	 */
	public static function getFileName( Object | String $class, String $method, Mixed &$reflect = Null ): False | String
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getFileName();
	}
	
	/*
	 * Gets function name.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return String
	 */
	public static function getName( Object | String $class, String $method, Mixed &$reflect = Null ): String
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getName();
	}
	
	/*
	 * Gets namespace name.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return String
	 */
	public static function getNamespaceName( Object | String $class, String $method, Mixed &$reflect = Null ): String
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getNamespaceName();
	}
	
	/*
	 * Gets number of parameters.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Int
	 */
	public static function getNumberOfParameters( Object | String $class, String $method, Mixed &$reflect = Null ): Int
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getNumberOfParameters();
	}
	
	/*
	 * Gets number of required parameters.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Int
	 */
	public static function getNumberOfRequiredParameters( Object | String $class, String $method, Mixed &$reflect = Null ): Int
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getNumberOfRequiredParameters();
	}
	
	/*
	 * Gets parameters.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getParameters( Object | String $class, String $method, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getParameters();
	}
	
	/*
	 * Gets the specified return type of a function.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return ReflectionType
	 */
	public static function getReturnType( Object | String $class, String $method, Mixed &$reflect = Null ): ? ReflectionType
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getReturnType();
	}
	
	/*
	 * Gets function short name.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return String
	 */
	public static function getShortName( Object | String $class, String $method, Mixed &$reflect = Null ): String
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getShortName();
	}
	
	/*
	 * Gets starting line number.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return False|Int
	 */
	public static function getStartLine( Object | String $class, String $method, Mixed &$reflect = Null ): False | Int
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getStartLine();
	}
	
	/*
	 * Gets static variables.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getStaticVariables( Object | String $class, String $method, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getStaticVariables();
	}
	
	/*
	 * Returns the tentative return type associated with the function.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return ReflectionType
	 */
	public static function getTentativeReturnType( Object | String $class, String $method, Mixed &$reflect = Null ): ? ReflectionType
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getTentativeReturnType();
	}
	
	/*
	 * Gets declaring class for the reflected method.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return ReflectionClass
	 */
	public static function getDeclaringClass( Object | String $class, String $method, Mixed &$reflect = Null ): ReflectionClass
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getDeclaringClass();
	}
	
	/*
	 * Gets the method modifiers.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Int
	 */
	public static function getModifiers( Object | String $class, String $method, Mixed &$reflect = Null ): Int
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getModifiers();
	}
	
	/*
	 * Gets the method prototype (if there is one).
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return ReflectionMethod
	 */
	public static function getPrototype( Object | String $class, String $method, Mixed &$reflect = Null ): ReflectionMethod
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->getPrototype();
	}
	
	/*
	 * Returns whether a method has a prototype.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function hasPrototype( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->hasPrototype();
	}
	
	/*
	 * Checks if the function has a specified return type.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function hasReturnType( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->hasReturnType();
	}
	
	/*
	 * Returns whether the function has a tentative return type.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function hasTentativeReturnType( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->hasTentativeReturnType();
	}
	
	/*
	 * Checks if function in namespace.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function inNamespace( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->inNamespace();
	}
	
	/*
	 * Invoke.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Array $arguments
	 * @params Mixed $reflect
	 *
	 * @return Mixed
	 */
	public static function invoke( Object | String $class, String $method, Array $arguments = [], Mixed &$reflect = Null ): Mixed
	{
		// Allow accessibility for method.
		self::setAccessible( $class, $method, True, $reflect );
		
		// Checks if method is static.
		if( $reflect->isStatic() )
		{
			$object = Null;
		}
		else {
			
			// If class has no instance.
			if( is_string( $class ) )
			{
				$class = ReflectClass::instance( $class );
			}
			$object = $class;
		}
		
		// Get the result of the called method.
		$result = $reflect->invoke(
			
			// Class instance...
			$object,
			
			// Build parameters for functions.
			...ReflectParameter::builder( $reflect->getParameters(), $arguments )
		);
		
		// Disable accessbility for method (Private & Protected ).
		$reflect->setAccessible( $reflect->isPublic() );
		
		// Return results.
		return( $result );
	}
	
	/*
	 * Checks if method is abstract.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isAbstract( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->isAbstract();
	}
	
	/*
	 * Returns a dynamically created closure for the function.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isAnonymous( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->isAnonymous();
	}
	
	/*
	 * Checks if closure.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isClosure( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->isClosure();
	}
	
	/*
	 * Checks if method is a constructor.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isConstructor( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->isConstructor();
	}
	
	/*
	 * Checks if deprecated.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isDeprecated( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->isDeprecated();
	}
	
	/*
	 * Checks if method is a destructor.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isDestructor( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->isDestructor();
	}
	
	/*
	 * Returns a dynamically created closure for the function.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isDisabled( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->isDisabled();
	}
	
	/*
	 * Checks if method is final.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isFinal( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->isFinal();
	}
	
	/*
	 * Returns whether this function is a generator.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isGenerator( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->isGenerator();
	}
	
	/*
	 * Checks if is internal.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isInternal( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->isInternal();
	}
	
	/*
	 * Checks if method is private.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isPrivate( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->isPrivate();
	}
	
	/*
	 * Checks if method is protected.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isProtected( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->isProtected();
	}
	
	/*
	 * Checks if method is public.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isPublic( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->isPublic();
	}
	
	/*
	 * Checks if method is static.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isStatic( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->isStatic();
	}
	
	/*
	 * Checks if user defined.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isUserDefined( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->isUserDefined();
	}
	
	/*
	 * Checks if the function is variadic.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isVariadic( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->isVariadic();
	}
	
	/*
	 * Checks if returns reference.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function returnsReference( Object | String $class, String $method, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $method, $reflect ) )->returnsReference();
	}
	
	/*
	 * Set method accessibility.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Bool $accessible
	 * @params Mixed $reflect
	 *
	 * @return Void
	 */
	public static function setAccessible( Object | String $class, String $method, Bool $accessible, Mixed &$reflect = Null ): Void
	{
		// Reflection class.
		$reclass = Null;
		
		// Check if class is Singleton.
		// Check if class is App.
		if( ReflectClass::isSingleton( $class, $reclass ) ||
			ReflectClass::isApp( $class, $reclass ) )
		{
			throw new Error\ClassError( $class, Error\ClassError::ACCESSIBLE_ERROR );
		}
		
		// Set method accessiblity.
		( $reflect = self::reflect( $class, $method, $reflect ) )->setAccessible( $accessible );
	}
	
	/*
	 * Create ReflectionMethod instance.
	 *
	 * @access Private Static
	 *
	 * @params Object|String $class
	 * @params String $method
	 * @params Mixed $reflect
	 *
	 * @return ReflectionMethod
	 */
	private static function reflect( Object | String $class, String $method, Mixed $reflect ): ReflectionMethod
	{
		// Check if `reflect` is instanceof ReflectionMethod.
		if( $reflect Instanceof ReflectionMethod )
		{
			// Get class name.
			$object = is_object( $class ) ? $class::class : $class;
			
			// Check if class name and method name is equals.
			if( $reflect->getDeclaringClass()->getName() === $object && $reflect->getName() === $method )
			{
				return( $reflect );
			}
		}
		return( new ReflectionMethod( $class, $method ) );
	}
	
}

?>