<?php

namespace Yume\Fure\Support\Reflect;

/*
 * ReflectParameter
 *
 * @package Yume\Fure\Support\Reflect
 */
abstract class ReflectParameter
{
	
	
	/*
	 * Checks if null is allowed
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function allowsNull( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Returns whether this parameter can be passed by value
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function canBePassedByValue( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
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
	 * Get a ReflectionClass object for the parameter being reflected or null
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function getClass( Mixed &$reflect = Null ): 
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
	 * Gets declaring function
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function getDeclaringFunction( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Gets default parameter value
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
	 * Returns the default value's constant name if default value is constant or null
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function getDefaultValueConstantName( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Gets parameter name
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
	 * Gets parameter position
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function getPosition( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Gets a parameter's type
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
	 * Checks if parameter has a type
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
	 * Checks if parameter expects an array
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isArray( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Returns whether parameter MUST be callable
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isCallable( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks if a default value is available
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isDefaultValueAvailable( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Returns whether the default value of this parameter is a constant
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isDefaultValueConstant( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks if optional
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isOptional( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks if passed by reference
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isPassedByReference( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
	/*
	 * Checks if the parameter is variadic
	 *
	 * @access Public Static
	 *
	 * @params  $
	 * @params Mixed $reflect
	 *
	 * @return 
	 */
	public static function isVariadic( Mixed &$reflect = Null ): 
	{
		// ...
	}
	
}

?>