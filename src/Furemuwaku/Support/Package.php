<?php

namespace Yume\Fure\Support;

use Throwable;

use Yume\Fure\Error;
use Yume\Fure\Util\File;
use Yume\Fure\Util\Path;
use Yume\Fure\Util\Reflect;
use Yume\Fure\Util\Type;

/*
 * Package
 *
 * @package Yume\Fure\Support
 *
 * @extends Yume\Fure\Support\Singleton
 */
final class Package extends Singleton
{
	
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
	protected function __construct()
	{
		// Get composer installed packages.
		$installed = File\File::json( Path\Paths::VendorInstalled->value, True )['packages'] ?? [];
		$packages = [];
		
		// Mapping all packages.
		foreach( $installed As $package )
		{
			// Get package autoload prs-4.
			$autoload = $package['autoload']['psr-4'] ?? [];
			
			// Mapping all autoload packages.
			foreach( $autoload As $space => $path )
			{
				$autoload[$space] = sprintf( "%s/%s/%s", Path\Paths::Vendor->value, $package['name'], $path );
			}
			$packages = [
				...$packages,
				...$autoload
			];
		}
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
	 * Yume\Fure\Type\Str::ify method.
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
	public static function array( String $package, ? String $prefix = Null, Bool $disable = False ): String
	{
		// If package has prefix or package has installed.
		if( $prefix !== Null || self::has( $package, $ref ) )
		{
			// If package name has backslash symbol.
			if( strpos( $package, "\\" ) !== False )
			{
				// Default format result without suffix.
				$format = "%s[%s]";
				
				$prefix = Type\Str::pop( $prefix ?? $ref['name'], "\\" );
				$middle = Type\Str::pop( $package, "\\", ref: $suffix );
				
				$middle = substr( $middle, strlen( $prefix ) +1 );
				
				// If lowercase allowed.
				if( $disable === False )
				{
					$prefix = strtolower( $prefix );
					$middle = strtolower( $middle );
				}
				
				// If package has suffix name.
				if( $suffix = $suffix[1] ?? Null )
				{
					$format = "%s[%s.%s]";
				}
				$package = sprintf( $format, $prefix, $middle, $suffix );
			}
		}
		return( str_replace( [ "\\", ".." ], ".", $package ) );
	}
	
	/*
	 * Return if package has installed.
	 *
	 * @access Public Static
	 *
	 * @params String $package
	 *  Package name.
	 * @params Mixed $ref
	 *  If the packet has been installed, the name and path
	 *  will be set there to use if needed, This is useful
	 *  compared to having to loop after checking.
	 *
	 * @return Bool
	 */
	public static function has( String $package, Mixed &$ref = Null ): Bool
	{
		$ref = Null;
		
		// Mapping all installed packages.
		foreach( self::self()->installed As $name => $path )
		{
			// If the package has the same prefix name as the namespace.
			if( strpos( $package, $name ) === 0 )
			{
				$ref = [
					"name" => $name,
					"path" => $path
				];
				return( True );
			}
		}
		return( False );
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
		$name = self::path( $package );
		
		// Create file name.
		$file = sprintf( "%s%s", $name, substr( $name, -4 ) !== ".php" ? ".php" : "" );
		
		// Check if file name is exists.
		if( File\File::exists( $file ) )
		{
			try
			{
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
	 * Create pathname based on namespace package.
	 *
	 * @access Public Static
	 *
	 * @params String $package
	 *
	 * @return String
	 */
	public static function path( String $package ): String
	{
		// If package has installed.
		if( self::has( $package, $ref ) )
		{
			// Replace prefix namespace with pathname.
			$package = substr_replace( $package, $ref['path'], 0, strlen( $ref['name'] ) );
		}
		return( str_replace( [ "/", "\\" ], DIRECTORY_SEPARATOR, $package ) );
	}
	
}

?>