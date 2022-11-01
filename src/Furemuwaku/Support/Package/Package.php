<?php

namespace Yume\Fure\Support\Package;

use Throwable;

use Yume\Fure\Error;
use Yume\Fure\IO;
use Yume\Fure\Support;

/*
 * Package
 *
 * @extends Yume\Fure\Support\Design\Creational\Singleton
 *
 * @package Yume\Fure\Support\Package
 */
class Package extends Support\Design\Creational\Singleton
{
	
	/*
	 * Composer autoload classes.
	 *
	 * @access Static Private
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	static private Support\Data\DataInterface $packages;
	
	/*
	 * @inherit Yume\Fure\Support\Design\Creational\Singleton
	 *
	 */
	protected function __construct()
	{
		// Create new data instance.
		static::$packages = new Support\Data\Data;
		
		// Get composer installed packages.
		$composer = IO\File\File::json( "vendor/composer/installed.json", True );
		
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
	
	public static function getPackages(): Support\Data\DataInterface
	{
		return( Package::self() )->packages->map( fn( $i, $k, $v ) => $v );
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
		// Replace package namespace.
		$name = Support\RegExp\RegExp::replace( "/^\\\*Yume\\\(App|Fure)\b/i", $package, fn( Array $match ) => $match[1] === "Fure" || $match[1] === "fure" ? "system/furemu" : "app" );
		
		// Create file name.
		$file = f( "{}{}", $name, substr( $name, -4 ) !== ".php" ? ".php" : "" );
		
		// Check if file name is exists.
		if( IO\File\File::exists( $file ) )
		{
			try {
				return( require( path( $file ) ) );
			}
			catch( Throwable $e )
			{
				throw new Error\ImportError( $name, Error\ImportError::SOMETHING_ERROR, $e );
			}
		}
		throw new Error\ModuleError( $name, Error\ModuleError::NAME_ERROR );
	}
	
}

?>