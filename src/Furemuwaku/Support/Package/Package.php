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
				require( path( $file ) );
			}
			catch( Throwable $e )
			{
				throw new Error\ImportError( $name, Error\ImportError::SOMETHING_ERROR );
			}
		}
		throw new Error\ModuleError( $name, Error\ModuleError::NAME_ERROR );
	}
	
}

?>