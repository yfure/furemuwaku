<?php

namespace Yume\Fure\Support\Path;

use Yume\Fure\Util;
use Yume\Fure\Util\RegExp;

/*
 * Path
 *
 * @package Yume\Fure\Support\Path
 */
abstract class Path
{
	
	/*
	 * Copy directory.
	 *
	 * @access Public Static
	 *
	 * @params String $from
	 * @params String $to
	 *
	 * @return Void
	 */
	public static function cpdir( String $from, String $to ): Void
	{
		// ...
	}
	
	/*
	 * Check if directory is exists.
	 *
	 * @access Public Static
	 *
	 * @params String $dir
	 *
	 * @return Bool
	 */
	public static function exists( String $dir ): Bool
	{
		return( file_exists( self::path( $dir ) ) && is_dir( self::path( $dir ) ) );
	}
	
	/*
	 * Tells whether the filename is a directory.
	 *
	 * @access Public Static
	 *
	 * @params String $dir
	 *
	 * @return Bool
	 */
	public static function is( String $dir ): Bool
	{
		return( is_dir( $dir ) );
	}
	
	/*
	 * List directory contents.
	 *
	 * @access Public Static
	 *
	 * @params String $dir
	 *
	 * @return Array|Bool
	 *
	 * @throws Yume\Fure\Support\Path\PathNotFoundError
	 */
	public static function ls( String $path ): Array | Bool
	{
		if( self::exists( $path ) )
		{
			// Scanning directory.
			$scan = scandir( path( $path ) );
			
			// Computes the difference of arrays.
			$scan = array_diff( $scan, [ ".", ".." ] );
			
			// Sort an array by key in ascending order.
			ksort( $scan );
			
			return( $scan );
		}
		throw new PathNotFoundError( $path );
	}
	
	/*
	 * Create new directory.
	 *
	 * @access Public Static
	 *
	 * @params String $path
	 *
	 * @return Void
	 */
	public static function mkdir( String $path ): Void
	{
		// Directory stack.
		$stack = "";
		
		// Mapping dir.
		Util\Arr::map( explode( "/", $path ), function( $dir ) use( &$stack )
		{
			// Check if directory is exists.
			if( self::exists( $stack = Util\Str::fmt( "{}{}/", $stack, $dir ) ) === False )
			{
				// Create new directory.
				mkdir( path( $stack ) );
			}
		});
	}
	
	/*
	 * Move directory.
	 *
	 * @access Public Static
	 *
	 * @params String $from
	 * @params String $to
	 *
	 * @return Void
	 */
	public static function mvdir( String $from, String $to ): Void
	{
		// ...
	}
	
	/*
	 * Check if directory is not found.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 *
	 * @return Bool
	 */
	public static function none( String $file ): Bool
	{
		return( self::exists( $file ) === False );
	}
	
	/*
	 * Get fullpath name or remove basepath name.
	 *
	 * @params String $path
	 * @params Bool $remove
	 *
	 * @return String
	 */
	public static function path( String $path, Bool $remove = False ): String
	{
		// Remove all basepath in string.
		if( $remove )
		{
			return( str_replace( [ RegExp\RegExp::replace( "/\//", BASE_PATH, DIRECTORY_SEPARATOR ), RegExp\RegExp::replace( "/\//", BASE_PATH, "\\" . DIRECTORY_SEPARATOR ) ], "", $path ) );
		}
		else {
			
			// Check if the path name has a prefix (e.g. php://).
			if( preg_match( "/^(?<name>[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)\:(?<separator>\/\/|\\\)/i", $path ) )
			{
				return( $path );
			}
			
			// Check if thd path has prefix path.
			if( strpos( $path, BASE_PATH ) !== False ) return( $path );
			
			// Add basepath into prefix pathname.
			return( str_replace( str_repeat( DIRECTORY_SEPARATOR, 2 ), DIRECTORY_SEPARATOR, RegExp\RegExp::replace( "/\//", f( "{}/{}", BASE_PATH, $path ), DIRECTORY_SEPARATOR ) ) );
		}
	}
	
	/*
	 * Check if directory is readable.
	 *
	 * @access Public
	 *
	 * @params String $file
	 *
	 * @return Bool
	 */
	public static function readable( String $file ): Bool
	{
		return( is_readable( self::path( $file ) ) );
	}
	
	/*
	 * Create tree directory structure.
	 *
	 * @access Public Static
	 *
	 * @params String $path
	 * @params String $parent
	 *
	 * @return Array|False
	 *
	 * @throws Yume\Fure\Support\PathNotFoundError
	 */
	public static function tree( String $path, String $parent = "" ): Array | False
	{
		// Check if path is exists.
		if( self::exists( $path, True ) )
		{
			$tree = [];
			$scan = self::ls( $path );
			
			// Mapping path and files.
			foreach( $scan As $i => $file )
			{
				switch( $file )
				{
					// Unsafe directory.
					case ".git":
					case "vendor":
						break;
						
					// Safe directory scan.
					default:
						
						// Check path has depth.
						if( $rscan = self::tree( f( "{}/{}", $path, $file ) ) )
						{
							$tree[$file] = $rscan;
						}
						else {
							$tree[] = $file;
						}
						break;
				}
			}
			return( $tree );
		}
		throw new PathNotFoundError( $path );
	}
	
	/*
	 * Check if directory is writable.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 *
	 * @return Bool
	 */
	public static function writable( String $file )
	{
		return( is_writable( self::path( $file ) ) );
	}
	
}

?>