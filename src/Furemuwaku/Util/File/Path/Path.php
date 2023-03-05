<?php

namespace Yume\Fure\Util\File\Path;

use Yume\Fure\Util\Array;
use Yume\Fure\Util\File;
use Yume\Fure\Util\RegExp;

/*
 * Path
 *
 * @package Yume\Fure\Util\File\Path
 */
final class Path
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
	 * List directory contents.
	 *
	 * @access Public Static
	 *
	 * @params String $dir
	 *
	 * @return Array|Bool
	 *
	 * @throws Yume\Fure\Util\Path\PathNotFoundError
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
	 * @params Int $permissions
	 *
	 * @return Void
	 */
	public static function mkdir( String $path, Int $permissions = 0777 ): Void
	{
		// Directory stack.
		$stack = "";
		
		// Mapping dir.
		Array\Arr::map( explode( "/", $path ), function( Int $i, Int $idx, String $dir ) use( &$stack, $permissions )
		{
			// Check if directory doesn't exists.
			if( self::exists( $stack = sprintf( "%s%s/", $stack, $dir ) ) === False )
			{
				// If failed create directory.
				if( mkdir( path( $stack ), $permissions ) === False ) return( STOP_ITERATION );
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
	 * @params Bool|Yume\Fure\Util\Path\PathName $prefix_or_remove
	 *
	 * @return String
	 */
	public static function path( String $path, Bool | PathName $prefix_or_remove = False ): String
	{
		// Remove all basepath in string.
		if( $prefix_or_remove === True )
		{
			return( str_replace( [ preg_replace( "/\//", DIRECTORY_SEPARATOR, BASE_PATH ), preg_replace( "/\//", "\\" . DIRECTORY_SEPARATOR, BASE_PATH ) ], "", $path ) );
		}
		else {
			
			// Check if the path name has a prefix (e.g. php://).
			if( preg_match( "/^(?<name>[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)\:(?<separator>\/\/|\\\)/i", $path ) )
			{
				return( $path );
			}
			
			// Check if thd path has prefix path.
			if( strpos( $path, BASE_PATH ) !== False || $path === BASE_PATH || $path . "/" === BASE_PATH ) return( $path );
			
			// Check if path has prefix.
			if( $prefix_or_remove Instanceof PathName ) $path = sprintf( "%s/%s", $prefix_or_remove->value, $path );
			
			// Add basepath into prefix pathname.
			return( str_replace( str_repeat( DIRECTORY_SEPARATOR, 2 ), DIRECTORY_SEPARATOR, preg_replace( "/\//", DIRECTORY_SEPARATOR, sprintf( "%s/%s", BASE_PATH, $path ) ) ) );
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
	 * Remove directory.
	 *
	 * @access Public Static
	 *
	 * @params String $path
	 *
	 * @return Bool
	 */
	public static function remove( String $path, String $pattern = "/*", Bool $onlyFile = False ): Bool
	{
		// Check if path is exists.
		if( self::exists( $path, True ) )
		{
			// Find pathnames matching a pattern.
			$files = glob( sprintf( "%s%s", $path, $pattern ) );
			
			foreach( $files !== False ? $files : [] as $file )
			{
				if( is_file( self::path( $file ) ) )
				{
					// If one file fails to delete then
					// the entire queue below it will not be deleted.
					if( unlink( self::path( $file ) ) === False ) return( False );
				}
				else {
					self::remove( $file, $pattern, $onlyFile );
				}
			}
			
			// If path is allowed for delete.
			if( $onlyFile === False )
			{
				return( rmdir( $path ) );
			}
			return( True );
		}
	}
	
	/*
	 * Create tree directory structure.
	 *
	 * @access Public Static
	 *
	 * @params String $path
	 * @params String $parent
	 *
	 * @return Array|String
	 *
	 * @throws Yume\Fure\Util\PathNotFoundError
	 */
	public static function tree( String $path, String $parent = "" ): Array | String
	{
		$path = $path !== "/" ? $path : BASE_PATH;
		
		// If path exists.
		if( self::exists( $path, True ) )
		{
			$tree = [];
			$scan = self::ls( $path );
			
			// Mapping path and files.
			foreach( $scan As $i => $file )
			{
				$next = f( "{}/{}", $path, $file );
				
				switch( $file )
				{
					// Unallowed for scan .git directory.
					case ".git": $tree[$file] = \Yume\Fure\Error\PermissionError::class; break;
					case "vendor":
						if( self::exists( $next ) )
						{
							$list = self::ls( $next );
							
							if( False !== $index = array_search( "yfure", $list ) )
							{
								unset( $list[$index] );
								
								$list['yfure'] = self::tree( $next . "/yfure" );
							}
							$tree[$file] = $list;
						}
						else {
							$tree[] = $file;
						}
						break;
					default:
						if( self::exists( $next ) )
						{
							$tree[$file] = self::tree( $next );
						}
						else {
							$tree[] = $file;
						}
						break;
				}
			}
			return( $tree );
		}
		
		// Return if file exists.
		if( File\File::exists( $path ) )
		{
			return( $path );
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