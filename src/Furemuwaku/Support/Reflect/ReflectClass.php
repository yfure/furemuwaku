<?php

namespace Yume\Fure\Support\Reflect;

use Countable;
use Stringable;

use ReflectionClass;
use ReflectionClassConstant;
use ReflectionExtension;
use ReflectionMethod;
use ReflectionProperty;

use Yume\Fure\Support;

/*
 * ReflectClass
 *
 * @package Yume\Fure\Support\Reflect
 */
abstract class ReflectClass
{
	
	/*
	 * Gets Attributes.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $name
	 * @params Int $flags
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getAttributes( Object | String $class, ? String $name = Null, Int $flags = 0, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getAttributes( $name, $flags );
	}
	
	/*
	 * Gets defined constant.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $name
	 * @params Mixed $reflect
	 *
	 * @return Mixed
	 */
	public static function getConstant( Object | String $class, String $name, Mixed &$reflect = Null ): Mixed
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getConstant( $name );
	}
	
	/*
	 * Gets constants.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Int $filter
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getConstants( Object | String $class, ? Int $filter = Null, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getConstants( $filter );
	}
	
	/*
	 * Gets the constructor of the class.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return ReflectionMethod
	 */
	public static function getConstructor( Object | String $class, Mixed &$reflect = Null ): ? ReflectionMethod
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getConstructor();
	}
	
	/*
	 * Gets default properties.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getDefaultProperties( Object | String $class, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getDefaultProperties();
	}
	
	/*
	 * Gets doc comments.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return False|String
	 */
	public static function getDocComment( Object | String $class, Mixed &$reflect = Null ): False | String
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getDocComment();
	}
	
	/*
	 * Gets end line.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return False|Int
	 */
	public static function getEndLine( Object | String $class, Mixed &$reflect = Null ): False | Int
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getEndLine();
	}
	
	/*
	 * Gets a ReflectionExtension object for the extension which defined the class.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return ReflectionExtension
	 */
	public static function getExtension( Object | String $class, Mixed &$reflect = Null ): ? ReflectionExtension
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getExtension();
	}
	
	/*
	 * Gets the name of the extension which defined the class.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return False|String
	 */
	public static function getExtensionName( Object | String $class, Mixed &$reflect = Null ): False | String
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getExtensionName();
	}
	
	/*
	 * Gets the filename of the file in which the class has been defined.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return String
	 */
	public static function getFileName( Object | String $class, Mixed &$reflect = Null ): False | String
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getFileName();
	}
	
	/*
	 * Gets the interface names.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getInterfaceNames( Object | String $class, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getInterfaceNames();
	}
	
	/*
	 * Gets the interfaces.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getInterfaces( Object | String $class, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getInterfaces();
	}
	
	/*
	 * Gets a ReflectionMethod for a class method.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $name
	 * @params Mixed $reflect
	 *
	 * @return ReflectionMethod
	 */
	public static function getMethod( Object | String $class, String $name, Mixed &$reflect = Null ): ReflectionMethod
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getMethod( $name );
	}
	
	/*
	 * Gets an array of methods.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Int $filter
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getMethods( Object | String $class, ? Int $filter = Null, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getMethods( $filter );
	}
	
	/*
	 * Gets the class modifiers.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Int
	 */
	public static function getModifiers( Object | String $class, Mixed &$reflect = Null ): Int
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getModifiers();
	}
	
	/*
	 * Gets class name.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return String
	 */
	public static function getName( Object | String $class, Mixed &$reflect = Null ): String
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getName();
	}
	
	/*
	 * Gets namespace name.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return String
	 */
	public static function getNamespaceName( Object | String $class, Mixed &$reflect = Null ): String
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getNamespaceName();
	}
	
	/*
	 * Gets parent class.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return ReflectionClass
	 */
	public static function getParentClass( Object | String $class, Mixed &$reflect = Null ): False | ReflectionClass
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getParentClass();
	}
	
	/*
	 * Gets parent class.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getParentClasses( Object | String $class, Mixed &$reflect = Null ): Array
	{
		$parent = self::reflect( $class, $class, $reflect );
		$parents = [];
		
		while( $parent = $class->getParentClass() )
		{
			$parents[] = $parent->getName();
		}
		
		return( $parents );
	}
	
	/*
	 * Gets properties.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Int $filter
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getProperties( Object | String $class, ? Int $filter = Null, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getProperties( $filter );
	}
	
	/*
	 * Gets a ReflectionProperty for a class's property.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $name
	 * @params Mixed $reflect
	 *
	 * @return ReflectionProperty
	 */
	public static function getProperty( Object | String $class, String $name, Mixed &$reflect = Null ): ReflectionProperty
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getProperty( $name );
	}
	
	/*
	 * Gets a ReflectionClassConstant for a class's constant.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $name
	 * @params Mixed $reflect
	 *
	 * @return False|ReflectionClassConstant
	 */
	public static function getReflectionConstant( Object | String $class, String $name, Mixed &$reflect = Null ): False | ReflectionClassConstant
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getReflectionClassConstant( $name );
	}
	
	/*
	 * Gets class constants.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Int $filter
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getReflectionConstants( Object | String $class, ? Int $filter = Null, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getReflectionClassConstants( $filter );
	}
	
	/*
	 * Gets short name.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return String
	 */
	public static function getShortName( Object | String $class, Mixed &$reflect = Null ): String
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getShortName();
	}
	
	/*
	 * Gets starting line number.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return False|Int
	 */
	public static function getStartLine( Object | String $class, Mixed &$reflect = Null ): False | Int
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getStartLine();
	}
	
	/*
	 * Gets static properties.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getStaticProperties( Object | String $class, Mixed &$reflect = Null ): ? Array
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getStaticProperties();
	}
	
	/*
	 * Gets static property value.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $name
	 * @params Mixed $reflect
	 *
	 * @return Mixed
	 */
	public static function getStaticPropertyValue( Object | String $class, String $name, Mixed &$value, Mixed &$reflect = Null ): Mixed
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getStaticPropertyValue( $name );
	}
	
	/*
	 * Returns an array of trait aliases.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getTraitAliases( Object | String $class, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getTraitAliases();
	}
	
	/*
	 * Returns an array of names of traits used by this class.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getTraitNames( Object | String $class, Mixed &$reflect = Null ): Array
	{
		// ...
		$traitsNames = [];
		$recursiveClasses = function ($class) use( &$recursiveClasses, &$traitsNames) {
		if ($class->getParentClass() != false) {
		$recursiveClasses( $class->getParentClass());
		}
		else {
		$traitsNames = array_merge( $traitsNames, $class->getTraitNames());
		}
		};
		$recursiveClasses( $controllerClass);
	}
	
	/*
	 * Returns an array of traits used by this class.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Array
	 */
	public static function getTraits( Object | String $class, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $class, $reflect ) )->getTraits();
	}
	
	/*
	 * Checks if constant is defined.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $name
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function hasConstant( Object | String $class, String $name, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->hasConstant( $name );
	}
	
	/*
	 * Checks if method is defined.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $name
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function hasMethod( Object | String $class, String $name, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->hasMethod( $name );
	}
	
	/*
	 * Checks if property is defined.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $name
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function hasProperty( Object | String $class, String $name, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->hasProperty( $name );
	}
	
	/*
	 * Checks if in namespace.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function inNamespace( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->inNamespace();
	}
	
	/*
	 * Creates a new class instance from given arguments.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Array|False $construct
	 * @params Mixed $reflect
	 *
	 * @return Object
	 */
	public static function instance( Object | String $class, Array | False | Null $construct = Null, Mixed &$reflect = Null ): Object
	{
		// Check if class is not Singleton.
		if( self::isSingleton( $class, $reflect ) )
		{
			// Check if class is not abstraction and has constructor.
			if( $construct !== False && $reflect->isAbstract() === False && $reflect->getConstructor() )
			{
				// Get class constructor parameter.
				$parameter = $reflect->getConstructor()->getParameters();
				
				// Return new class instance.
				return( $reflect )->newInstance( ...ReflectParameter::create( $parameter, $construct ?? [] ) );
			}
			
			// Return new class instance without constructor.
			return( $reflect )->newInstanceWithoutConstructor();
		}
		throw new Error\ClassError( $class, Error\ClassError::INSTANCE_ERROR );
	}
	
	/*
	 * Checks if class is abstract.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isAbstract( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->isAbstract();
	}
	
	/*
	 * Checks if class is anonymous.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isAnonymous( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->isAnonymous();
	}
	
	/*
	 * Returns whether this class is Cloneable.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isCloneable( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->isCloneable();
	}
	
	/*
	 * Returns whether this class is Countable.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isCountable( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( self::isImplements( $class, Countable::class, $reflect ) );
	}
	
	/*
	 * Returns whether this class is an data.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isData( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( self::isImplements( $class, Support\Data\DataInterface::class, $reflect ) );
	}
	
	/*
	 * Returns whether this is an enum
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isEnum( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->isEnum();
	}
	
	/*
	 * Checks if class is final
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isFinal( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->isFinal();
	}
	
	/*
	 * Implements interface
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isImplements( Object | String $class, String $name, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->implementsInterface( $name );
	}
	
	/*
	 * Checks class for instance
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isInstance( Object | String $class, Object $object, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->isInstance();
	}
	
	/*
	 * Checks if the class is instantiable
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isInstantiable( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->isInstantiable();
	}
	
	/*
	 * Checks if the class is an interface
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isInterface( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->isInterface();
	}
	
	/*
	 * Checks if class is defined internally by an extension, or the core
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isInternal( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->isInternal();
	}
	
	/*
	 * Check whether this class is iterable
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isIterable( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->isIterable();
	}
	
	/*
	 * Alias of 
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isIterateable( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->isIterateable();
	}
	
	/*
	 * Returns whether this class is ServiceProvider.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isServicesProvider( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( self::isImplements( $class, Support\Services\ServicesProviderInterface::class ) );
	}
	
	/*
	 * Returns whether this class is a Singleton.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isSingleton( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( self::isSubClassOf( $class, Support\Design\Creational\Singleton::class, $reflect ) );
	}
	
	/*
	 * Checks if a subclass
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String|ReflectionClass $subclass
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isSubclassOf( Object | String $class, String | ReflectionClass $subclass, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->isSubClassOf( $subclass );
	}
	
	/*
	 * Returns whether this class is Stringable.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isStringable( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( self::isImplements( $class, Stringable::class, $reflect ) );
	}
	
	/*
	 * Returns whether this class is Throwable.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isThrowable( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( self::isImplements( $class, Throwable::class, $reflect ) );
	}
	
	/*
	 * Returns whether this is a trait.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isTrait( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->isTrait();
	}
	
	/*
	 * Checks if user defined.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isUserDefined( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $class, $reflect ) )->isUserDefined();
	}
	
	/*
	 * Sets static property value.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $name
	 * @params Mixed $value
	 * @params Mixed $reflect
	 *
	 * @return Void
	 */
	public static function setStaticPropertyValue( Object | String $class, String $name, Mixed $value, Mixed &$reflect = Null ): Void
	{
		( $reflect = self::reflect( $class, $reflect ) )->setStaticPropertyValue( $name, $value );
	}
	
	/*
	 * Create ReflectionClass instance.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return ReflectionClass
	 */
	private static function reflect( Object | String $class, Mixed $reflect = Null ): ReflectionClass
	{
		// Get class name.
		$class = is_object( $class ) ? $class::class : $class;
		
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