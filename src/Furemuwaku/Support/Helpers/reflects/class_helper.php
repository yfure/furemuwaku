<?php

/*
 * Yume Reflection Class Helpers.
 *
 * @include getAttributesClass
 * @include getConstantClass
 * @include getConstantsClass
 * @include getConstructorClass
 * @include getDefaultPropertiesClass
 * @include getDocCommentClass
 * @include getEndLineClass
 * @include getExtensionClass
 * @include getExtensionNameClass
 * @include getFileNameClass
 * @include getInterfaceNamesClass
 * @include getInterfacesClass
 * @include getMethodClass
 * @include getMethodsClass
 * @include getModifiersClass
 * @include getNameClass
 * @include getNamespaceNameClass
 * @include getParentClassClass
 * @include getParentClassesClass
 * @include getPropertiesClass
 * @include getPropertyClass
 * @include getReflectionConstantClass
 * @include getReflectionConstantsClass
 * @include getShortNameClass
 * @include getStartLineClass
 * @include getStaticPropertiesClass
 * @include getStaticPropertyValueClass
 * @include getTraitAliasesClass
 * @include getTraitNamesClass
 * @include getTraitsClass
 * @include hasConstantClass
 * @include hasMethodClass
 * @include hasPropertyClass
 * @include inNamespaceClass
 * @include instanceClass
 * @include isAbstractClass
 * @include isAnonymousClass
 * @include isAppClass
 * @include isCloneableClass
 * @include isCountableClass
 * @include isDataClass
 * @include isEnumClass
 * @include isFinalClass
 * @include isImplementsClass
 * @include isInstanceClass
 * @include isInstantiableClass
 * @include isInterfaceClass
 * @include isInternalClass
 * @include isInvokableClass
 * @include isIterableClass
 * @include isIterateableClass
 * @include isServicesProviderClass
 * @include isSingletonClass
 * @include isSubclassOfClass
 * @include isStringableClass
 * @include isThrowableClass
 * @include isTraitClass
 * @include isUserDefinedClass
 * @include setStaticPropertyValueClass
 */

use Yume\Fure\Util\Reflect;

if( function_exists( "getAttributesClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getAttributesClass( Object | String $class, ? String $name = Null, Int $flags = 0, Mixed &$reflect = Null ): Array
	{
		return( Reflect\ReflectClass::getAttributes( $class, $name, $flags, $reflect ) );
	}
}

if( function_exists( "getConstantClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getConstantClass( Object | String $class, String $name, Mixed &$reflect = Null ): Mixed
	{
		return( Reflect\ReflectClass::getConstant( $class, $name, $reflect ) );
	}
}

if( function_exists( "getConstantsClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getConstantsClass( Object | String $class, ? Int $filter = Null, Mixed &$reflect = Null ): Array
	{
		return( Reflect\ReflectClass::getConstants( $class, $filter, $reflect ) );
	}
}

if( function_exists( "getConstructorClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getConstructorClass( Object | String $class, Mixed &$reflect = Null ): ? ReflectionMethod
	{
		return( Reflect\ReflectClass::getConstructor( $class, $reflect ) );
	}
}

if( function_exists( "getDefaultPropertiesClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getDefaultPropertiesClass( Object | String $class, Mixed &$reflect = Null ): Array
	{
		return( Reflect\ReflectClass::getProperties( $class, $reflect ) );
	}
}

if( function_exists( "getDocCommentClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getDocCommentClass( Object | String $class, Mixed &$reflect = Null ): False | String
	{
		return( Reflect\ReflectClass::getDocComment( $class, $reflect ) );
	}
}

if( function_exists( "getEndLineClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getEndLineClass( Object | String $class, Mixed &$reflect = Null ): False | Int
	{
		return( Reflect\ReflectClass::getEndLine( $class, $reflect ) );
	}
}

if( function_exists( "getExtensionClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getExtensionClass( Object | String $class, Mixed &$reflect = Null ): ? ReflectionExtension
	{
		return( Reflect\ReflectClass::getExtension( $class, $reflect ) );
	}
}

if( function_exists( "getExtensionNameClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getExtensionNameClass( Object | String $class, Mixed &$reflect = Null ): False | String
	{
		return( Reflect\ReflectClass::getExtensionName( $class, $reflect ) );
	}
}

if( function_exists( "getFileNameClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getFileNameClass( Object | String $class, Mixed &$reflect = Null ): False | String
	{
		return( Reflect\ReflectClass::getFileName( $class, $reflect ) );
	}
}

if( function_exists( "getInterfaceNamesClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getInterfaceNamesClass( Object | String $class, Mixed &$reflect = Null ): Array
	{
		return( Reflect\ReflectClass::getInterfaceNames( $class, $reflect ) );
	}
}

if( function_exists( "getInterfacesClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getInterfacesClass( Object | String $class, Mixed &$reflect = Null ): Array
	{
		return( Reflect\ReflectClass::getInterfaces( $class, $reflect ) );
	}
}

if( function_exists( "getMethodClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getMethodClass( Object | String $class, String $name, Mixed &$reflect = Null ): ReflectionMethod
	{
		return( Reflect\ReflectClass::getMethod( $class, $name, $reflect ) );
	}
}

if( function_exists( "getMethodsClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getMethodsClass( Object | String $class, ? Int $filter = Null, Mixed &$reflect = Null ): Array
	{
		return( Reflect\ReflectClass::getMethods( $class, $filter, $reflect ) );
	}
}

if( function_exists( "getModifiersClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getModifiersClass( Object | String $class, Mixed &$reflect = Null ): Int
	{
		return( Reflect\ReflectClass::getModifiers( $class, $reflect ) );
	}
}

if( function_exists( "getNameClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getNameClass( Object | String $class, Mixed &$reflect = Null ): String
	{
		return( Reflect\ReflectClass::getName( $class, $reflect ) );
	}
}

if( function_exists( "getNamespaceNameClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getNamespaceNameClass( Object | String $class, Mixed &$reflect = Null ): String
	{
		return( Reflect\ReflectClass::getNamespaceName( $class, $reflect ) );
	}
}

if( function_exists( "getParentClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getParentClass( Object | String $class, Mixed &$reflect = Null ): False | ReflectionClass
	{
		return( Reflect\ReflectClass::getParentClass( $class, $reflect ) );
	}
}

if( function_exists( "getParentClasses" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getParentClasses( Object | String $class, Mixed &$reflect = Null ): Array
	{
		return( Reflect\ReflectClass::getParentClasses( $class, $reflect ) );
	}
}

if( function_exists( "getPropertiesClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getPropertiesClass( Object | String $class, ? Int $filter = Null, Mixed &$reflect = Null ): Array
	{
		return( Reflect\ReflectClass::getProperties( $class, $filter, $reflect ) );
	}
}

if( function_exists( "getPropertyClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getPropertyClass( Object | String $class, String $name, Mixed &$reflect = Null ): ReflectionProperty
	{
		return( Reflect\ReflectClass::getProperty( $class, $name, $reflect ) );
	}
}

if( function_exists( "getReflectionConstantClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getReflectionConstantClass( Object | String $class, String $name, Mixed &$reflect = Null ): False | ReflectionClassConstant
	{
		return( Reflect\ReflectClass::getRelfectionConstant( $class, $name, $reflect ) );
	}
}

if( function_exists( "getReflectionConstantsClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getReflectionConstantsClass( Object | String $class, ? Int $filter = Null, Mixed &$reflect = Null ): Array
	{
		return( Reflect\ReflectClass::getReflectionConstants( $class, $filter, $reflect ) );
	}
}

if( function_exists( "getShortNameClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getShortNameClass( Object | String $class, Mixed &$reflect = Null ): String
	{
		return( Reflect\ReflectClass::getShortName( $class, $reflect ) );
	}
}

if( function_exists( "getStartLineClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getStartLineClass( Object | String $class, Mixed &$reflect = Null ): False | Int
	{
		return( Reflect\ReflectClass::getStartLine( $class, $reflect ) );
	}
}

if( function_exists( "getStaticPropertiesClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getStaticPropertiesClass( Object | String $class, Mixed &$reflect = Null ): ? Array
	{
		return( Reflect\ReflectClass::getStaticProperties( $class, $reflect ) );
	}
}

if( function_exists( "getStaticPropertyValueClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getStaticPropertyValueClass( Object | String $class, String $name, Mixed &$value, Mixed &$reflect = Null ): Mixed
	{
		return( Reflect\ReflectClass::getStaticPropertyValue( $class, $name, $reflect ) );
	}
}

if( function_exists( "getTraitAliasesClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getTraitAliasesClass( Object | String $class, Mixed &$reflect = Null ): Array
	{
		return( Reflect\ReflectClass::getTraitAliases( $class, $reflect ) );
	}
}

if( function_exists( "getTraitNamesClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getTraitNamesClass( Object | String $class, Mixed &$reflect = Null ): Array
	{
		return( Reflect\ReflectClass::getTraitNames( $class, $reflect ) );
	}
}

if( function_exists( "getTraitsClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function getTraitsClass( Object | String $class, Mixed &$reflect = Null ): Array
	{
		return( Reflect\ReflectClass::getTraits( $class, $reflect ) );
	}
}

if( function_exists( "hasConstantClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function hasConstantClass( Object | String $class, String $name, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::hasConstant( $class, $name, $reflect ) );
	}
}

if( function_exists( "hasMethodClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function hasMethodClass( Object | String $class, String $name, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::hasMethod( $class, $name, $reflect ) );
	}
}

if( function_exists( "hasPropertyClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function hasPropertyClass( Object | String $class, String $name, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::hasProperty( $class, $name, $reflect ) );
	}
}

if( function_exists( "inNamespaceClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function inNamespaceClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::inNamespace( $class, $reflect ) );
	}
}

if( function_exists( "instanceClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function instanceClass( Object | String $class, Array | False $argument = [], Mixed &$reflect = Null ): Object
	{
		return( Reflect\ReflectClass::instance( $class, $argument, $reflect ) );
	}
}

if( function_exists( "isAbstractClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isAbstractClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isAbstract( $class, $reflect ) );
	}
}

if( function_exists( "isAnonymousClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isAnonymousClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isAnonymous( $class, $reflect ) );
	}
}

if( function_exists( "isAppClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isAppClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isApp( $class, $reflect ) );
	}
}

if( function_exists( "isCloneableClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isCloneableClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isCloneable( $class, $reflect ) );
	}
}

if( function_exists( "isCountableClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isCountableClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isCountable( $class, $reflect ) );
	}
}

if( function_exists( "isDataClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isDataClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isData( $class, $reflect ) );
	}
}

if( function_exists( "isEnumClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isEnumClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isEnum( $class, $reflect ) );
	}
}

if( function_exists( "isFinalClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isFinalClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isFinal( $class, $reflect ) );
	}
}

if( function_exists( "isImplementsClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isImplementsClass( Object | String $class, String $name, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isImplements( $class, $reflect ) );
	}
}

if( function_exists( "isInstanceClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isInstanceClass( Object | String $class, Object $object, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isInstance( $class, $reflect ) );
	}
}

if( function_exists( "isInstantiableClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isInstantiableClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isInstantiable( $class, $reflect ) );
	}
}

if( function_exists( "isInterfaceClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isInterfaceClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isInterface( $class, $reflect ) );
	}
}

if( function_exists( "isInternalClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isInternalClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isInternal( $class, $reflect ) );
	}
}

if( function_exists( "isInvokableClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isInvokableClass( Object | String $class, Mixed $reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isInvokable( $class, $reflect ) );
	}
}

if( function_exists( "isIterableClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isIterableClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isIterable( $class, $reflect ) );
	}
}

if( function_exists( "isIterateableClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isIterateableClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isIterateable( $class, $reflect ) );
	}
}

if( function_exists( "isServicesProviderClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isServicesProviderClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isServicesProvider( $class, $reflect ) );
	}
}

if( function_exists( "isSingletonClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isSingletonClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isSingleton( $class, $reflect ) );
	}
}

if( function_exists( "isSubclassOfClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isSubclassOfClass( Object | String $class, String | ReflectionClass $subclass, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isSubclassOf( $class, $reflect ) );
	}
}

if( function_exists( "isStringableClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isStringableClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isStringable( $class, $reflect ) );
	}
}

if( function_exists( "isThrowableClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isThrowableClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isThrowable( $class, $reflect ) );
	}
}

if( function_exists( "isTraitClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isTraitClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isTrait( $class, $reflect ) );
	}
}

if( function_exists( "isUserDefinedClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function isUserDefinedClass( Object | String $class, Mixed &$reflect = Null ): Bool
	{
		return( Reflect\ReflectClass::isUserDefined( $class, $reflect ) );
	}
}

if( function_exists( "setStaticPropertyValueClass" ) === False )
{
	/*
	 * @inherit Yume\Fure\Util\Reflect\ReflectClass::
	 *
	 */
	function setStaticPropertyValueClass( Object | String $class, String $name, Mixed $value, Mixed &$reflect = Null ): Void
	{
		Reflect\ReflectClass::setStaticPropertyValue( $class, $name, $value, $reflect );
	}
}

?>