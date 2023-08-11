<?php

namespace Yume\Fure\Util\Reflect;

use ReflectionExtension;

use Yume\Fure\IO\Stream;

/*
 * ReflectExtension
 *
 * @package Yume\Fure\Util\Reflect
 */
final class ReflectExtension
{
	
	/*
	 * Gets classes.
	 *
	 * @params String $extension
	 * @params Mixed &$reflect
	 *
	 * @return Array
	*/
	public static function getClasses( String $extension, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $extension, $reflect ) )->getClasses();
	}
	
	/*
	 * Gets class names.
	 *
	 * @params String $extension
	 * @params Mixed &$reflect
	 *
	 * @return Array
	*/
	public static function getClassNames( String $extension, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $extension, $reflect ) )->getClassNames();
	}
	
	/*
	 * Gets constants.
	 *
	 * @params String $extension
	 * @params Mixed &$reflect
	 *
	 * @return Array
	*/
	public static function getConstants( String $extension, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $extension, $reflect ) )->getConstants();
	}
	
	/*
	 * Gets dependencies.
	 *
	 * @params String $extension
	 * @params Mixed &$reflect
	 *
	 * @return Array
	*/
	public static function getDependencies( String $extension, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $extension, $reflect ) )->getDependencies();
	}
	
	/*
	 * Gets extension functions.
	 *
	 * @params String $extension
	 * @params Mixed &$reflect
	 *
	 * @return Array
	*/
	public static function getFunctions( String $extension, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $extension, $reflect ) )->getFunctions();
	}
	
	/*
	 * Gets extension ini entries.
	 *
	 * @params String $extension
	 * @params Mixed &$reflect
	 *
	 * @return Array
	*/
	public static function getINIEntries( String $extension, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $extension, $reflect ) )->getINIEntries();
	}
	
	/*
	 * Gets extension name.
	 *
	 * @params String $extension
	 * @params Mixed &$reflect
	 *
	 * @return String
	*/
	public static function getName( String $extension, Mixed &$reflect = Null ): String
	{
		return( $reflect = self::reflect( $extension, $reflect ) )->getName();
	}
	
	/*
	 * Gets extension version.
	 *
	 * @params String $extension
	 * @params Mixed &$reflect
	 *
	 * @return String
	*/
	public static function getVersion( String $extension, Mixed &$reflect = Null ): ? String
	{
		return( $reflect = self::reflect( $extension, $reflect ) )->getVersion();
	}
	
	/*
	 * Print extension info.
	 *
	 * @params String $extension
	 * @params Mixed &$reflect
	 *
	 * @return Void
	*/
	public static function info( String $extension, Mixed &$reflect = Null ): Void
	{
		$reflect = self::reflect( $extension, $reflect );
		$reflect->info();
	}
	
	/*
	 * Returns whether this extension is persistent.
	 *
	 * @params String $extension
	 * @params Mixed &$reflect
	 *
	 * @return Bool
	*/
	public static function isPersistent( String $extension, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $extension, $reflect ) )->isPersistent();
	}
	
	/*
	 * Returns whether this extension is temporary.
	 *
	 * @params String $extension
	 * @params Mixed &$reflect
	 *
	 * @return Bool
	*/
	public static function isTemporary( String $extension, Mixed &$reflect = Null ): Bool
	{
		return( $reflect = self::reflect( $extension, $reflect ) )->isTemporary();
	}
	
	/*
	 * Create ReflectionExtension instance.
	 *
	 * @access Private Static
	 *
	 * @params String $extension
	 * @params Mixed $reflect
	 *
	 * @return ReflectionExtension
	 */
	private static function reflect( String $extension, Mixed $reflect )
	{
		if( $reflect Instanceof ReflectionExtension )
		{
			if( $reflect->name === $extension )
			{
				return( $reflect );
			}
		}
		return( new ReflectionExtension( $extension ) );
	}
}

?>