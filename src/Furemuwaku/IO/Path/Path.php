<?php

namespace Yume\Fure\IO\Path;

use const BASE_PATH;

use Yume\Fure\IO\File;

/*
 * Path
 *
 * @package Yume\Fure\IO\Path
 */
class Path {
	
	/*
	 * Copy a file or directory recursively to a new location.
	 *
	 * @access Public Static
	 *
	 * @param String $from
	 *  The path to the file or directory to copy.
	 * @param String $to
	 *  The path to the destination directory or file.
	 * @param Bool $overwrite
	 *  Whether or not to overwrite files if they already exist.
	 * @param Int $permission
	 *  The permission to set for the copied files and directories.
	 *
	 * @return Bool
	 *  Return True if the copy was successful.
	 *  Return False otherwise.
	 * 
	 * @throws Yume\Fure\IO\PathError
	 *  If source or destination is not allowed for copy.
	 * @throws Yume\Fure\IO\PathNotFoundError
	 *  If source destination is not exists.
	 */
	public static function copy( String $from, String $to, Bool $overwrite = False, Int $permission = 0755 ): Bool {
	
		// If the source is a file, simply copy it to the destination.
		if( File\File::exists( $from ) ) return( File\File::copy( ...func_get_args() ) );
		
		/*
		 * If the source is a directory, create the
		 * destination directory if it doesn't exist,
		 * then copy each file and subdirectory
		 * recursively to the new location.
		 *
		 */
		if( self::exists( $from ) ) {
			if( File\File::exists( $to, False ) ) {
				if( self::exists( $to ) === False ) {
					
					// If destination directory doesn't exist,
					// create it and this just copy the contents.
					if( self::make( $to, $permission ) === False ) {
						return( False );
					}
				}
				foreach( self::ls( $from ) as $file ) {
					$srcFile = $from . DIRECTORY_SEPARATOR . $file;
					$destFile = $to . DIRECTORY_SEPARATOR . $file;
					if( self::copy( $srcFile, $destFile, $overwrite, $permission ) ) {
						continue;
					}
					return( False );
				}
				return( True );
			}
			throw new PathError( $from );
		}
		throw new PathNotFoundError( $from );
	}
	
	/*
	 * Check if directory is exists.
	 *
	 * @access Public Static
	 *
	 * @params String $dir
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public static function exists( String $dir, ? Bool $optional = Null ): Bool {
		return( $optional !== Null ? is_dir( self::path( $dir ) ) === $optional : is_dir( self::path( $dir ) ) );
	}
	
	/*
	 * List directory contents.
	 *
	 * @access Public Static
	 *
	 * @params String $dir
	 *
	 * @return Array
	 *
	 * @throws Yume\Fure\IO\Path\PathNotFoundError
	 */
	public static function ls( String $path ): Array {
		if( self::exists( $path ) ) {
			$scan = scandir( path( $path ) );
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
	 * @return Bool
	 */
	public static function make( String $path, Int $permissions = 0777 ): Bool {
		$stack = "";
		$stack = "";
		foreach( explode( DIRECTORY_SEPARATOR, $path ) As $dir ) {
			if( self::exists( $make = path( $stack .= DIRECTORY_SEPARATOR . $dir ) ) === False ) {
				if( mkdir( $make, $permissions ) === False ) return( False );
			}
		}
		return( True );
	}
	
	/*
	 * Move directory.
	 *
	 * @access Public Static
	 *
	 * @params String $from
	 * @params String $to
	 * @params Resource $context
	 *
	 * @return Bool
	 */
	public static function move( String $from, String $to, $context = Null ): Bool {
		return( rename( self::path( $from ), self::path( $to ), $context ) );
	}
	
	/*
	 * Get fullpath name or remove basepath name.
	 *
	 * @access Public Static
	 *
	 * @params String $path
	 * @params Bool|Yume\Fure\IO\Path\PathName $prefix_or_remove
	 *  Remove or add prefix name in pathname.
	 *
	 * @return String
	 */
	public static function path( String $path, Bool | Paths $prefix_or_remove = False ): String {
		if( $prefix_or_remove === True ) {
			$paths = [
				preg_replace( "/\//", DIRECTORY_SEPARATOR, BASE_PATH . DIRECTORY_SEPARATOR ),
				preg_replace( "/\//", "\\" . DIRECTORY_SEPARATOR, BASE_PATH . DIRECTORY_SEPARATOR ),
				preg_replace( "/\//", DIRECTORY_SEPARATOR, BASE_PATH ),
				preg_replace( "/\//", "\\" . DIRECTORY_SEPARATOR, BASE_PATH ),
			];
			return( str_replace( $paths, "", $path ) );
		}
		else {
			if( preg_match( "/^(?<name>[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)\:(?<separator>\/\/|\\\)/i", $path ) ) {
				return( $path );
			}
			if( strpos( $path, BASE_PATH ) !== False || $path === BASE_PATH || $path . "/" === BASE_PATH ) {
				return( $path );
			}
			if( $prefix_or_remove Instanceof Paths ) {
				$path = join( "/", [ $prefix_or_remove->value, $path ] );
			}
			return( str_replace( str_repeat( DIRECTORY_SEPARATOR, 2 ), DIRECTORY_SEPARATOR, preg_replace( "/\//", DIRECTORY_SEPARATOR, sprintf( "%s/%s", BASE_PATH, $path ) ) ) );
		}
	}
	
	/*
	 * Remove directory.
	 *
	 * @access Public Static
	 *
	 * @params String $path
	 * @params String $pattern
	 * @params Bool $onlyFile
	 *  Remove file only.
	 *
	 * @return Bool
	 */
	public static function remove( String $path, String $pattern = "/*", Bool $onlyFile = False ): Bool {
		if( self::exists( $path, True ) ) {
			$files = glob( sprintf( "%s%s", $path, $pattern ) );
			foreach( $files !== False ? $files : [] as $file ) {
				if( is_file( self::path( $file ) ) ) {
				
					// If one file fails to delete then
					// the entire queue below it will not be deleted.
					if( unlink( self::path( $file ) ) === False ) return( False );
				}
				else {
					if( self::remove( $file, $pattern, $onlyFile ) === False ) return( False );
				}
			}
			if( $onlyFile === False ) {
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
	 *  Array directory structure.
	 *  String when if path is file.
	 *
	 * @throws Yume\Fure\IO\PathNotFoundError
	 */
	public static function tree( String $path, String $parent = "" ): Array | String {
		$path = $path !== "/" ? $path : BASE_PATH;
		if( File\File::exists( $path ) ) {
			return( $path );
		}
		if( self::exists( $path, True ) ) {
			$tree = [];
			$scan = self::ls( $path );
			foreach( $scan As $i => $file ) {
				if( self::exists( $next = $path . DIRECTORY_SEPARATOR . $file ) ) {
					$stack[$file] = self::tree( $next );
				}
				else {
					$stack[] = $file;
				}
			}
			return( $tree );
		}
		throw new PathNotFoundError( $path );
	}
}

?>