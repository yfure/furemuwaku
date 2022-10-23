<?php

namespace Yume\Fure\Support\Package;

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
		
		return( path( $name . substr( $name, -4 ) !== ".php" ? ".php" : "" ) );
	}
	
}

?>