<?php

namespace Yume\Fure\IO\Path;

use Yume\Fure\Error;
use Yume\Fure\Support;
use Yume\Fure\Util;

/*
 * Path
 *
 * @package Yume\Fure\IO
 */
abstract class Path
{
	
	/*
	 * Tells whether the filename is a directory.
	 *
	 * @access Public Static
	 *
	 * @params String $dir
	 *
	 * @return Bool
	 */
	public static function exists( String $dir ): Bool
	{
		return( is_dir( path( $dir ) ) );
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
	 * @throws Yume\Fure\Error\PathError
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
		throw new Error\PathError( $path, Error\PathError::PATH_ERROR );
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
	 * Get fullpath name or remove basepath name.
	 *
	 * @params String $path
	 * @params Bool $remove
	 *
	 * @return String
	 */
	public static function path( String $path, Bool $remove = False ): String
	{
		// Check if the path name has a prefix (e.g. php://).
		if( Support\RegExp\RegExp::test( "/(?<name>^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)\:(?<separator>\/\/|\\\)/i", $path ) )
		{
			return( $path );
		}
		
		// Remove all basepath in string.
		if( $remove )
		{
			return( str_replace( [ Support\RegExp\RegExp::replace( "/\//", BASE_PATH, DIRECTORY_SEPARATOR ), Support\RegExp\RegExp::replace( "/\//", BASE_PATH, "\\" . DIRECTORY_SEPARATOR ) ], "", $path ) );
		}
		
		// Add basepath into prefix pathname.
		return( str_replace( str_repeat( DIRECTORY_SEPARATOR, 2 ), DIRECTORY_SEPARATOR, Support\RegExp\RegExp::replace( "/\//", f( "{}/{}", BASE_PATH, $path ), DIRECTORY_SEPARATOR ) ) );
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
	 * @throws Yume\Fure\Error\PathError
	 */
	public static function tree( String $path, String $parent = "" ): Array | False
	{
		if( self::exists( $path ) )
		{
			$tree = [];
			$scan = self::ls( $path );
			
			foreach( $scan As $i => $file )
			{
				if( $file === "vendor" || $file === ".git" )
				{
					continue;
				}
				if( $rscan = self::tree( f( "{}/{}", $path, $file ) ) )
				{
					$tree[$file] = $rscan;
				} else {
					$tree[] = $file;
				}
			}
			return( $tree );
		}
		throw new Error\PathError( $path, Error\PathError::PATH_ERROR );
	}
	
}

?>