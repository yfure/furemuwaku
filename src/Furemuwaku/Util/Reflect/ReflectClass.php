<?php

namespace Yume\Fure\Util\Reflect;

use Countable;
use Stringable;
use Throwable;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionEnum;
use ReflectionException;
use ReflectionExtension;
use ReflectionMethod;
use ReflectionProperty;

use Yume\Fure\App;
use Yume\Fure\Error;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\Design;
use Yume\Fure\Services;

/*
 * ReflectClass
 *
 * @package Yume\Fure\Util\Reflect
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
		
		while( $parent = $parent->getParentClass() )
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
	 * @params Array|False $argument
	 *  If false then the class will be instantiated without a constructor method.
	 * @params Mixed &$reflect
	 *
	 * @return Object
	 *
	 * @throws Yume\Fure\Error\AttributeError
	 * @throws Yume\Fure\Error\ClassError
	 */
	public static function instance( Object | String $class, Array | False $argument = [], Mixed &$reflect = Null ): Object
	{
		// Check if reflect is instanceof ReflectionAttribute class.
		if( $reflect Instanceof ReflectionAttribute )
		{
			if( $argument === False )
			{
				$argument = [];
			}
			try
			{
				return( $reflect )->newInstance( ...$argument );
			}
			catch( \Error $e )
			{
				if( preg_match( "/^Attempting\sto\suse\snon\-attribute\sclass\s\"[^\"]+\"\sas\sattribute$/i", $e->getMessage() ) )
				{
					$e = new Error\AttributeError( $reflect->getName(), Error\AttributeError::TYPE_ERROR, $e );
				}
				else {
					$e = new Error\AttributeError( $e->getMessage(), previous: $e );
				}
				throw $e;
			}
		}
		else {
			
			// Check if class is instantiable and not Singleton class.
			if( self::isInstantiable( $class, $reflect ) === True &&
				self::isSingleton( $class, $reflect ) === False )
			{
				// If the constructor is allowed to be called,
				// and if class has constructor method.
				if( $argument !== False && $construct = $reflect->getConstructor() )
				{
					return( $reflect )->newInstance(
						...ReflectParameter::builder( 
							$construct->getParameters(), 
							$argument 
						)
					);
				}
				return( $reflect )->newInstanceWithoutConstructor();
			}
		}
		throw new Error\ClassInstanceError( $reflect->name );
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
	 * Return whether this class is App.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isApp( Object | String $class, Mixed &$reflect = Null  ): Bool
	{
		// Check if `class` is Object type.
		if( is_object( $class ) )
		{
			return( $class Instanceof App\App );
		}
		return( self::getName( $class, $reflect ) === App\App::class );
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
		return( self::isImplements( $class, Data\DataInterface::class, $reflect ) );
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
	 * Check whether this class is invokable.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return Bool
	 */
	public static function isInvokable( Object | String $class, Mixed $reflect = Null ): Bool
	{
		return( self::hasMethod( $class, "__invoke", $reflect ) );
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
		return( self::isImplements( $class, Services\ServicesProviderInterface::class ) );
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
		return( self::isSubClassOf( $class, Design\Singleton::class, $reflect ) );
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
	 * @access Private Static
	 *
	 * @params Object|String $class
	 * @params Mixed $reflect
	 *
	 * @return ReflectionAttribute|ReflectionClass|ReflectionEnum
	 */
	private static function reflect( Object | String $class, Mixed $reflect ): ReflectionAttribute | ReflectionClass | ReflectionEnum
	{
		// Get class name.
		$class = is_object( $class ) ? $class::class : $class;
		
		// Check if `reflect` is instance of Reflection Attribute, Class or Enum.
		if( $reflect Instanceof ReflectionAttribute && $reflect->getName() === $class ||
			$reflect Instanceof ReflectionClass && $reflect->name === $class ||
			$reflect Instanceof ReflectionEnum )
		{
			return( $reflect );
		}
		else {
			try
			{
				return( new ReflectionClass( $class ) );
			}
			catch( ReflectionException $e )
			{
				if( preg_match( "/^Class\s\"[^\"]+\"\sdoes\snot\sexist$/i", $e->getMessage() ) )
				{
					$e = new Error\ClassNameError( $class, previous: $e );
				}
				else {
					$e = new Error\ClassError( $e->getMessage(), previous: $e );
				}
				throw $e;
			}
		}
	}
	
}

?>