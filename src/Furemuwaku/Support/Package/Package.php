<?php

namespace Yume\Fure\Support\Package;

use Yume\Fure\Error;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\Design;
use Yume\Fure\Support\File;
use Yume\Fure\Util;
use Yume\Fure\Util\RegExp;

/*
 * Package
 *
 * @package Yume\Fure\Support\Package
 *
 * @extends Yume\Fure\Support\Design\Singleton
 */
class Package extends Design\Singleton
{
	
	/*
	 * Composer autoload classes.
	 *
	 * @access Static Private
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	static private Data\DataInterface $packages;
	
	/*
	 * @inherit Yume\Fure\Support\Design\Singleton
	 *
	 */
	final protected function __construct()
	{
		// Create new data instance.
		static::$packages = new Data\Data;
		
		// Get composer installed packages.
		$composer = File\File::json( "vendor/composer/installed.json", True );
		
		// Mapping all packages.
		Util\Arr::map( $packages['packages'], function( $package )
		{
			// Check if package has autoload psr-4.
			if( isset( $package['autoload']['psr-4'] ) )
			{
				Util\Arr::map( $package['autoload']['psr-4'], fn( $i, $namespace, $directory ) => static::$packages[] = $namespace );
			}
		});
	}
	
	/*
	 * Get all packages.
	 *
	 * @access Public Static
	 *
	 * @return Yume\Fure\Support\Data\DataInterface
	 */
	public static function getPackages(): Data\DataInterface
	{
		return( self::self() )->packages->map( fn( $i, $k, $v ) => $v );
	}
	
	/*
	 * Import php file.
	 *
	 * @access Public Static
	 *
	 * @params String $package
	 *
	 * @return Mixed
	 */
	public static function import( String $package ): Mixed
	{
		// Get package name.
		$name = self::name( $package );
		
		// Create file name.
		$file = f( "{}{}", $name, substr( $name, -4 ) !== ".php" ? ".php" : "" );
		
		// Check if file name is exists.
		if( File\File::exists( $file ) )
		{
			try {
				return( require( path( $file ) ) );
			}
			catch( Throwable $e )
			{
				throw new Error\ImportError( $name, previous: $e );
			}
		}
		throw new Error\ModuleNotFoundError( $name );
	}
	
	/*
	 * Create filename from package name.
	 *
	 * @access Public Static
	 *
	 * @params String $package
	 *
	 * @return String
	 */
	public static function name( String $package ): String
	{
		// Replace package namespace.
		return( RegExp\RegExp::replace( "/^\\\*Yume\\\(App|Fure)\b/i", $package, fn( Array $match ) => $match[1] === "Fure" || $match[1] === "fure" ? "system/furemu" : "app" ) );
	}
	
}

?>