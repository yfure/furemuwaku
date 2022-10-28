<?php

namespace Yume\Fure\Support\Reflect;

/*
 * ReflectProperty
 *
 * @package Yume\Fure\Support\Reflect
 */
abstract class ReflectProperty
{
	
	
	/*
	 * Gets Attributes
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function getAttributes( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Gets declaring class
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
	 * Returns the default value declared for a property
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function getDefaultValue( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Gets the property doc comment
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function getDocComment( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Gets the property modifiers
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
	 * Gets property name
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function getName( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Gets a property's type
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function getType( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Gets value
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function getValue( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks if property has a default value declared
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function hasDefaultValue( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks if property has a type
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function hasType( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks if property is a default property
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isDefault( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks whether a property is initialized
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isInitialized( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks if property is private
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
	 * Checks if property is promoted
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isPromoted( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks if property is protected
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
	 * Checks if property is public.
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
	 * Checks if property is readonly
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isReadOnly( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks if property is static
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
	 * Set property accessibility
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
	 * Set property value
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function setValue( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Create ReflectionProperty instance.
	 *
	 * @access Private Static
	 *
	 * @params  $property
	 * @params Mixed $reflect
	 *
	 * @return ReflectionProperty
	 */
	private static function reflect( $property, Mixed $reflect ): ReflectionProperty
	{
	}
	
}

?>