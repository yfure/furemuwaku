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

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getAttributes
 *
 */
function getAttributesClass( Object | String $class, ? String $name = Null, Int $flags = 0, Mixed &$reflect = Null ): Array
{
	return( Reflect\ReflectClass::getAttributes( $class, $name, $flags, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getConstant
 *
 */
function getConstantClass( Object | String $class, String $name, Mixed &$reflect = Null ): Mixed
{
	return( Reflect\ReflectClass::getConstant( $class, $name, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getConstants
 *
 */
function getConstantsClass( Object | String $class, ? Int $filter = Null, Mixed &$reflect = Null ): Array
{
	return( Reflect\ReflectClass::getConstants( $class, $filter, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getConstructor
 *
 */
function getConstructorClass( Object | String $class, Mixed &$reflect = Null ): ? ReflectionMethod
{
	return( Reflect\ReflectClass::getConstructor( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getDefaultProperties
 *
 */
function getDefaultPropertiesClass( Object | String $class, Mixed &$reflect = Null ): Array
{
	return( Reflect\ReflectClass::getProperties( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getDocComment
 *
 */
function getDocCommentClass( Object | String $class, Mixed &$reflect = Null ): False | String
{
	return( Reflect\ReflectClass::getDocComment( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getEndLine
 *
 */
function getEndLineClass( Object | String $class, Mixed &$reflect = Null ): False | Int
{
	return( Reflect\ReflectClass::getEndLine( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getExtension
 *
 */
function getExtensionClass( Object | String $class, Mixed &$reflect = Null ): ? ReflectionExtension
{
	return( Reflect\ReflectClass::getExtension( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getExtensionName
 *
 */
function getExtensionNameClass( Object | String $class, Mixed &$reflect = Null ): False | String
{
	return( Reflect\ReflectClass::getExtensionName( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getFileName
 *
 */
function getFileNameClass( Object | String $class, Mixed &$reflect = Null ): False | String
{
	return( Reflect\ReflectClass::getFileName( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getInterfaceNames
 *
 */
function getInterfaceNamesClass( Object | String $class, Mixed &$reflect = Null ): Array
{
	return( Reflect\ReflectClass::getInterfaceNames( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getInterfaces
 *
 */
function getInterfacesClass( Object | String $class, Mixed &$reflect = Null ): Array
{
	return( Reflect\ReflectClass::getInterfaces( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getMethod
 *
 */
function getMethodClass( Object | String $class, String $name, Mixed &$reflect = Null ): ReflectionMethod
{
	return( Reflect\ReflectClass::getMethod( $class, $name, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getMethods
 *
 */
function getMethodsClass( Object | String $class, ? Int $filter = Null, Mixed &$reflect = Null ): Array
{
	return( Reflect\ReflectClass::getMethods( $class, $filter, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getModifiers
 *
 */
function getModifiersClass( Object | String $class, Mixed &$reflect = Null ): Int
{
	return( Reflect\ReflectClass::getModifiers( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getName
 *
 */
function getNameClass( Object | String $class, Mixed &$reflect = Null ): String
{
	return( Reflect\ReflectClass::getName( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getNamespaceName
 *
 */
function getNamespaceNameClass( Object | String $class, Mixed &$reflect = Null ): String
{
	return( Reflect\ReflectClass::getNamespaceName( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getParentClass
 *
 */
function getParentClass( Object | String $class, Mixed &$reflect = Null ): False | ReflectionClass
{
	return( Reflect\ReflectClass::getParentClass( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getParentClasses
 *
 */
function getParentClasses( Object | String $class, Mixed &$reflect = Null ): Array
{
	return( Reflect\ReflectClass::getParentClasses( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getProperties
 *
 */
function getPropertiesClass( Object | String $class, ? Int $filter = Null, Mixed &$reflect = Null ): Array
{
	return( Reflect\ReflectClass::getProperties( $class, $filter, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getProperty
 *
 */
function getPropertyClass( Object | String $class, String $name, Mixed &$reflect = Null ): ReflectionProperty
{
	return( Reflect\ReflectClass::getProperty( $class, $name, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getReflectionConstant
 *
 */
function getReflectionConstantClass( Object | String $class, String $name, Mixed &$reflect = Null ): False | ReflectionClassConstant
{
	return( Reflect\ReflectClass::getRelfectionConstant( $class, $name, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getReflectionConstants
 *
 */
function getReflectionConstantsClass( Object | String $class, ? Int $filter = Null, Mixed &$reflect = Null ): Array
{
	return( Reflect\ReflectClass::getReflectionConstants( $class, $filter, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getShortName
 *
 */
function getShortNameClass( Object | String $class, Mixed &$reflect = Null ): String
{
	return( Reflect\ReflectClass::getShortName( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getStartLine
 *
 */
function getStartLineClass( Object | String $class, Mixed &$reflect = Null ): False | Int
{
	return( Reflect\ReflectClass::getStartLine( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getStaticProperties
 *
 */
function getStaticPropertiesClass( Object | String $class, Mixed &$reflect = Null ): ? Array
{
	return( Reflect\ReflectClass::getStaticProperties( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getStaticProperty
 *
 */
function getStaticPropertyValueClass( Object | String $class, String $name, Mixed &$value, Mixed &$reflect = Null ): Mixed
{
	return( Reflect\ReflectClass::getStaticPropertyValue( $class, $name, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getTraitAliases
 *
 */
function getTraitAliasesClass( Object | String $class, Mixed &$reflect = Null ): Array
{
	return( Reflect\ReflectClass::getTraitAliases( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getTraitNames
 *
 */
function getTraitNamesClass( Object | String $class, Mixed &$reflect = Null ): Array
{
	return( Reflect\ReflectClass::getTraitNames( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::getTraits
 *
 */
function getTraitsClass( Object | String $class, Mixed &$reflect = Null ): Array
{
	return( Reflect\ReflectClass::getTraits( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::hasConstant
 *
 */
function hasConstantClass( Object | String $class, String $name, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::hasConstant( $class, $name, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::hasMethod
 *
 */
function hasMethodClass( Object | String $class, String $name, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::hasMethod( $class, $name, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::hasProperty
 *
 */
function hasPropertyClass( Object | String $class, String $name, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::hasProperty( $class, $name, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::inNamespace
 *
 */
function inNamespaceClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::inNamespace( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::instance
 *
 */
function instanceClass( Object | String $class, Array | False $argument = [], Mixed &$reflect = Null ): Object
{
	return( Reflect\ReflectClass::instance( $class, $argument, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isAbstract
 *
 */
function isAbstractClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isAbstract( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isAnonymous
 *
 */
function isAnonymousClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isAnonymous( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isApp
 *
 */
function isAppClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isApp( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isCloneable
 *
 */
function isCloneableClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isCloneable( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isCountable
 *
 */
function isCountableClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isCountable( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isData
 *
 */
function isDataClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isData( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isEnum
 *
 */
function isEnumClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isEnum( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isFinal
 *
 */
function isFinalClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isFinal( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isImplements
 *
 */
function isImplementsClass( Object | String $class, String $name, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isImplements( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isInstance
 *
 */
function isInstanceClass( Object | String $class, Object $object, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isInstance( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isInstantiable
 *
 */
function isInstantiableClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isInstantiable( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isInterface
 *
 */
function isInterfaceClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isInterface( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isInternal
 *
 */
function isInternalClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isInternal( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isInvokable
 *
 */
function isInvokableClass( Object | String $class, Mixed $reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isInvokable( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isIterable
 *
 */
function isIterableClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isIterable( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isIterateable
 *
 */
function isIterateableClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isIterateable( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isServiceProvider
 *
 */
function isServicesProviderClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isServicesProvider( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isSingleton
 *
 */
function isSingletonClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isSingleton( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isSubClassOf
 *
 */
function isSubclassOfClass( Object | String $class, String | ReflectionClass $subclass, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isSubclassOf( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isStringable
 *
 */
function isStringableClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isStringable( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isThrowable
 *
 */
function isThrowableClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isThrowable( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isTrait
 *
 */
function isTraitClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isTrait( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::isUserDefined
 *
 */
function isUserDefinedClass( Object | String $class, Mixed &$reflect = Null ): Bool
{
	return( Reflect\ReflectClass::isUserDefined( $class, $reflect ) );
}

/*
 * @inherit Yume\Fure\Util\Reflect\ReflectClass::setStaticPropertyValue
 *
 */
function setStaticPropertyValueClass( Object | String $class, String $name, Mixed $value, Mixed &$reflect = Null ): Void
{
	Reflect\ReflectClass::setStaticPropertyValue( $class, $name, $value, $reflect );
}

?>