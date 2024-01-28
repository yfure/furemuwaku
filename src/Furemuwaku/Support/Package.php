<?php

namespace Yume\Fure\Support;

use Throwable;

use Yume\Fure\Error;
use Yume\Fure\IO\File;
use Yume\Fure\IO\Path;
use Yume\Fure\Util;
use Yume\Fure\Util\Reflect;

/*
 * Package
 *
 * @package Yume\Fure\Support
 *
 * @extends Yume\Fure\Support\Singleton
 */
final class Package extends Singleton {
	
	/*
	 * Composer installed packages.
	 *
	 * @access Private Readonly
	 *
	 * @values Array
	 */
	private Readonly Array $installed;
	
	/*
	 * @inherit Yume\Fure\Support\Singleton::__construct
	 *
	 */
	protected function __construct() {
		$packages = [];
		try {
			$installed = File\File::json( Path\Paths::VendorInstalled->value, True )['packages'] ?? [];
			foreach( $installed As $package ) {
				$autoload = $package['autoload']['psr-4'] ?? [];
				foreach( $autoload As $space => $path ) {
					$autoload[$space] = sprintf( "%s/%s/%s", Path\Paths::Vendor->value, $package['name'], $path );
				}
				$packages = [
					...$packages,
					...$autoload
				];
			}
		}
		catch( Error\YumeError ) {}
		$this->installed = [
			...$packages,
			...[
				"Yume\\App\\" => Path\Paths::App->value . "/"
			]
		];
	}
	
	/*
	 * Make package name into array syntax.
	 *
	 * The array syntax can be use with
	 * Yume\Fure\Util\Arrays::ify method.
	 *
	 * @before Yume\Fure\Error\AssertionError
	 * @after yume.fure[error.AssertionError]
	 *
	 * @access Public Static
	 *
	 * @params String $package
	 *  Package name.
	 * @params Array $prefix
	 *  Prefix package name.
	 * @params Bool $disable
	 *  Disable transform into lowercase.
	 *
	 * @return String
	 */
	public static function array( String $package, ? String $prefix = Null, Bool $disable = False ): String {
		if( $prefix !== Null || self::has( $package, $ref ) ) {
			if( strpos( $package, "\\" ) !== False ) {
				$format = "%s[%s]";
				$prefix = Util\Strings::pop( $prefix ?? $ref['name'], "\\" );
				$middle = Util\Strings::pop( $package, "\\", ref: $suffix );
				$middle = substr( $middle, strlen( $prefix ) +1 );
				if( $disable === False ) {
					$prefix = strtolower( $prefix );
					$middle = strtolower( $middle );
				}
				if( $suffix = $suffix[1] ?? Null ) {
					$format = "%s[%s.%s]";
				}
				$package = sprintf( $format, $prefix, $middle, $suffix );
			}
		}
		return( str_replace( [ "\\", ".." ], ".", $package ) );
	}
	
	/*
	 * Return if package has installed and exists.
	 *
	 /*
	 * Return if package name has installed.
	 *
	 * @access Public Static
	 *
	 * @params String $package
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public static function exists( String $package, ? Bool $optional = Null ): Bool {
		$name = self::path( $package );
		return( File\File::exists( join( "", [ $name, substr( $name, -4 ) !== ".php" ? ".php" : "" ] ), $optional ) );
	}
	
	/*
	 * Return if package name has installed.
	 *
	 * @access Public Static
	 *
	 * @params String $package
	 *  Package name.
	 * @params Mixed $ref
	 *  If the packet has been installed, the name and path
	 *  will be set there to use if needed, This is useful
	 *  compared to having to loop after checking.
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public static function has( String $package, Mixed &$ref = Null, ? Bool $optional = Null ): Bool {
		if( $optional === Null ) {
			foreach( self::self()->installed As $name => $path ) {
				if( strpos( $package, $name ) === 0 ) {
					$ref = [
						"name" => $name,
						"path" => $path
					];
					return( True );
				}
				$ref = Null;
			}
			return( False );
		}
		return( self::has( $package, $ref ) === $optional );
	}
	
	/*
	 * Import php file.
	 *
	 * @access Public Static
	 *
	 * @params String $package
	 * @params Mixed $optional
	 *  When the module import something wrong like syntax error,
	 *  Method will return optional value for avoid error.
	 * @params Mixed ...$args
	 *  Pass the parameters to the file to import.
	 *
	 * @return Mixed
	 */
	public static function import( String $package, Mixed $optional = Null, Mixed ...$args ): Mixed {
		$name = self::path( $package );
		$file = join( "", [ $name, substr( $name, -4 ) !== ".php" ? ".php" : "" ] );
		if( File\File::exists( $file ) ) {
			try {
				return( fn( Mixed ...$args ) => require( path( $file ) ) )( ...$args );
			}
			catch( Throwable $e ) {
				if( $optional ) {
					return( $optional );
				}
				throw new Error\ImportError( $package, previous: $e );
			}
		}
		throw new Error\ModuleNotFoundError( $package );
	}
	
	/*
	 * Create pathname based on namespace package.
	 *
	 * @access Public Static
	 *
	 * @params String $package
	 *
	 * @return String
	 */
	public static function path( String $package ): String {
		if( self::has( $package, $ref ) ) {
			$package = substr_replace( $package, $ref['path'], 0, strlen( $ref['name'] ) );
		}
		return( str_replace( [ "/", "\\" ], DIRECTORY_SEPARATOR, $package ) );
	}
	
}

?>
