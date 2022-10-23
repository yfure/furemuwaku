<?php

namespace Yume\Fure\Support\Reflect;

use ReflectionClass;
use ReflectionExtension;
use ReflectionMethod;

/*
 * ReflectClass
 *
 * @package Yume\Fure\Support\Reflect
 */
abstract class ReflectClass
{
	
	/*
	 * Gets Attributes
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getAttributes( ? String $name = Null, Int $flags = 0, Mixed &$reflect = Null ): Array
	{
		// ...
	}
	
	/*
	 * Gets defined constant
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getConstant( String $name, Mixed &$reflect = Null ): Mixed
	{
		// ...
	}
	
	/*
	 * Gets constants
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getConstants( ? Int $filter = Null, Mixed &$reflect = Null ): Array
	{
		// ...
	}
	
	/*
	 * Gets the constructor of the class
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getConstructor( Mixed &$reflect = Null ): ? ReflectionMethod
	{
		// ...
	}
	
	/*
	 * Gets default properties
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getDefaultProperties( Mixed &$reflect = Null ): Array
	{
		// ...
	}
	
	/*
	 * Gets doc comments
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getDocComment( Mixed &$reflect = Null ): False | String
	{
		// ...
	}
	
	/*
	 * Gets end line
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getEndLine( Mixed &$reflect = Null ): False | Int
	{
		// ...
	}
	
	/*
	 * Gets a ReflectionExtension object for the extension which defined the class
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getExtension( Mixed &$reflect = Null ): ? ReflectionExtension
	{
		// ...
	}
	
	/*
	 * Gets the name of the extension which defined the class
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getExtensionName( Mixed &$reflect = Null ): False | String
	{
		// ...
	}
	
	/*
	 * Gets the filename of the file in which the class has been defined
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getFileName( Mixed &$reflect = Null ): False | String
	{
		// ...
	}
	
	/*
	 * Gets the interface names
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getInterfaceNames( Mixed &$reflect = Null ): Array
	{
		// ...
	}
	
	/*
	 * Gets the interfaces
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getInterfaces( Mixed &$reflect = Null ): Array
	{
		// ...
	}
	
	/*
	 * Gets a ReflectionMethod for a class method
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getMethod( String $name, Mixed &$reflect = Null ): ReflectionMethod
	{
		// ...
	}
	
	/*
	 * Gets an array of methods
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getMethods( ? Int $filter = Null, Mixed &$reflect = Null ): Array
	{
		// ...
	}
	
	/*
	 * Gets the class modifiers
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getModifiers( Mixed &$reflect = Null ): Int
	{
		// ...
	}
	
	/*
	 * Gets class name
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getName( Mixed &$reflect = Null ): String
	{
		// ...
	}
	
	/*
	 * Gets namespace name
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getNamespaceName( Mixed &$reflect = Null ): String
	{
		// ...
	}
	
	/*
	 * Gets parent class
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getParentClass( Mixed &$reflect = Null ): False | ReflectionClass
	{
		// ...
	}
	
	/*
	 * Gets parent class
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getParentClasses( Mixed &$reflect = Null ): Array
	{
		$parent = self::reflect( $class, $reflect );
		$parents = [];
		
		while( $parent = $class->getParentClass() )
		{
			$parents[] = $parent->getName();
		}
		
		return( $parents );
	}
	
	/*
	 * Gets properties
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getProperties( ? Int $filter = Null, Mixed &$reflect = Null ): Array
	{
		// ...
	}
	
	/*
	 * Gets a ReflectionProperty for a class's property
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getProperty( String $name, Mixed &$reflect = Null ): ReflectionProperty
	{
		// ...
	}
	
	/*
	 * Gets a ReflectionClassConstant for a class's constant
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getReflectionConstant( String $name, Mixed &$reflect = Null ): False | ReflectionClassConstant
	{
		// ...
	}
	
	/*
	 * Gets class constants
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getReflectionConstants( ? Int $filter = Null, Mixed &$reflect = Null ): Array
	{
		// ...
	}
	
	/*
	 * Gets short name
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getShortName( Mixed &$reflect = Null ): String
	{
		// ...
	}
	
	/*
	 * Gets starting line number
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getStartLine( Mixed &$reflect = Null ): False | Int
	{
		// ...
	}
	
	/*
	 * Gets static properties
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getStaticProperties( Mixed &$reflect = Null ): ? Array
	{
		// ...
	}
	
	/*
	 * Gets static property value
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getStaticPropertyValue( String $name, Mixed &$value, Mixed &$reflect = Null ): Mixed
	{
		// ...
	}
	
	/*
	 * Returns an array of trait aliases
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getTraitAliases( Mixed &$reflect = Null ): Array
	{
		// ...
	}
	
	/*
	 * Returns an array of names of traits used by this class
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getTraitNames( Mixed &$reflect = Null ): Array
	{
		// ...
		$traitsNames = [];
		$recursiveClasses = function ($class) use(&$recursiveClasses, &$traitsNames) {
		if ($class->getParentClass() != false) {
		$recursiveClasses($class->getParentClass());
		}
		else {
		$traitsNames = array_merge($traitsNames, $class->getTraitNames());
		}
		};
		$recursiveClasses($controllerClass);
	}
	
	/*
	 * Returns an array of traits used by this class
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function getTraits( Mixed &$reflect = Null ): Array
	{
		// ...
	}
	
	/*
	 * Checks if constant is defined
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function hasConstant( String $name, Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if method is defined
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function hasMethod( String $name, Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if property is defined
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function hasProperty( String $name, Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if in namespace
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function inNamespace( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Creates a new class instance from given arguments
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function instance( Mixed &$reflect = Null ): Object
	{
		// ...
	}
	
	/*
	 * Checks if class is abstract
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function isAbstract( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if class is anonymous
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function isAnonymous( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Returns whether this class is cloneable
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function isCloneable( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Returns whether this is an enum
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function isEnum( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if class is final
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function isFinal( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Implements interface
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function isImplements( String $name, Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks class for instance
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function isInstance( Object $object, Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if the class is instantiable
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function isInstantiable( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if the class is an interface
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function isInterface( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if class is defined internally by an extension, or the core
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function isInternal( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Check whether this class is iterable
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function isIterable( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Alias of 
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function isIterateable( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if a subclass
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function isSubclassOf( String | ReflectionClass $subclass, Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Returns whether this is a trait
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function isTrait( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Checks if user defined
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function isUserDefined( Mixed &$reflect = Null ): Bool
	{
		// ...
	}
	
	/*
	 * Sets static property value
	 *
	 * @access Public Static
	 *
	 * @params  $
	 *
	 * @return 
	 */
	public static function setStaticPropertyValue( String $name, Mixed $value, Mixed &$reflect = Null ): Void
	{
		// ...
	}
	
	/*
	 * Create ReflectionClass instance.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params ReflectionClass $reflect
	 *
	 * @return ReflectionClass
	 */
	private static function ref( Object | String $class, Mixed $reflect = Null ): ReflectionClass
	{
		// Get class name.
		$class = is_object( $class ) ?: $class::class;
		
		// Check if `reflect` is ReflectionClass instance.
		if( $reflect Instanceof ReflectionClass && $reflect->getClass() === $class )
		{
			return( $reflect );
		}
		else {
			return( new ReflectionClass( $class ) );
		}
	}
	
}

?>