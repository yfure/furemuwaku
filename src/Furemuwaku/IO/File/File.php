<?php

namespace Yume\Fure\IO\File;

use Yume\Fure\Error;
use Yume\Fure\IO\Path;
use Yume\Fure\Locale\DateTime;
use Yume\Fure\Util;
use Yume\Fure\Util\Json;

/*
 * File
 *
 * @package Yume\Fure\IO\File
 */
class File {
	
	/*
	 * PHP Valid file open read modes.
	 *
	 * @access Public Static
	 *
	 * @values String
	 */
	public const READABLE_MODES = "/^(r|a\+|ab\+|w\+|wb\+|x\+|xb\+|c\+|cb\+)$/";
	
	/*
	 * PHP Valid file open write modes.
	 *
	 * @access Public Static
	 *
	 * @values String
	 */
	public const WRITABLE_MODES = "/^(a|w|r\+|rb\+|rw|x|c)$/";
	
	/*
	 * PHP File open modes.
	 *
	 * @access Protected
	 *
	 * @values Array
	 */
	protected static Array $modes = [
		"r",
		"r+",
		"rb",
		"rw",
		"a+",
		"ab+",
		"w",
		"wb+",
		"x",
		"x+",
		"xb+",
		"c+",
		"cb+"
	];
	
	/*
	 * File open assertion mode.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 * @params String $mode
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\Error\AssertionError
	 * @throws Yume\Fure\IO\File\FileError
	 */
	public static function assert( String $file, String $mode ): Void {
		try {
			if( in_array( $mode, self::$modes ) === False ) throw new Error\AssertionError( [ "mode", self::$modes, $mode ], Error\AssertionError::VALUE_ERROR );
		}
		catch( Error\AssertionError $e ) {
			throw new FileError( [ $file, $mode ], FileError::MODE_ERROR, $e );
		}
	}
	
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
		
		/*
		 * If the source is a directory, create the
		 * destination directory if it doesn't exist,
		 * then copy each file and subdirectory
		 * recursively to the new location.
		 *
		 */
		if( Path\Path::exists( $from ) ) {
			return( Path\Path::copy( ...func_get_args() ) );
		}
		
		// If the source is a file, simply copy it to the destination.
		if( self::exists( $from ) ) {
			if( Path\Path::exists( $to ) ) {
				$to .= DIRECTORY_SEPARATOR . basename( $from );
			}
			else {
				
				/*
				 * Check if file already exists.
				 * And if file is not allowed for overwrite.
				 *
				 */
				if( self::exists( $to ) && $overwrite === False ) {
					return( False );
				}
			}
			
			// Copy the file and set permissions.
			if( copy( Path\Path::path( $from ), Path\Path::path( $to ) ) ) {
				
				// Set file permission.
				chmod( Path\Path::path( $to ), $permission );
				
				return( True );
			}
			return( False );
		}
		throw new FileNotFoundError( $from );
	}
	
	/*
	 * Check if file is exists.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 * @parans Bool $optional
	 *
	 * @return Bool
	 */
	public static function exists( String $file, ? Bool $optional = Null ): Bool {
		return( $optional !== Null ? is_file( Path\Path::path( $file ) ) === $optional : is_file( Path\Path::path( $file ) ) );
	}
	
	/*
	 * Return supported file open modes.
	 *
	 * @access Public Static
	 *
	 * @return Array
	 */
	public static function getSupportedModes(): Array {
		return( self::$modes );
	}
	
	/*
	 * Check if file open mode is readable.
	 * 
	 * @access Public Static
	 * 
	 * @params String $mode
	 * 
	 * @return Bool
	 */
	public static function isReadableMode( String $mode ): Bool {
		return( preg_match( self::READABLE_MODES, $mode ) );
	}
	
	/*
	 * Return if file open is writable mode.
	 * 
	 * @access Public Static
	 * 
	 * @params String $mode
	 * 
	 * @return Bool
	 */
	public static function isWritableMode( String $mode ): Bool {
		return( preg_match( self::WRITABLE_MODES, $mode ) );
	}
	
	/*
	 * Decode String from file content.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 * @params Bool $associative
	 * @params Int $depth
	 * @params Int $flags
	 *
	 * @return Mixed
	 */
	public static function json( String $file, ? Bool $associative = Null, Int $depth = 512, Int $flags = 0 ): Mixed {
		return( Json\Json::decode( self::read( $file ), $associative, $depth, $flags ) );
	}
	
	/*
	 * Return if file is modified.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 *
	 * @return Bool
	 */
	public static function modified( String $file, Int $long = 60 ): Bool {
		// If file is exists.
		if( self::exists( $file ) ) {
			return( ( new DateTime\DateTime( "now" ) )->getTimestamp() - self::mtime( $file )->getTimestamp() <= $long );
		}
		return( False );
	}
	
	/*
	 * @inherit Yume\Fure\IO\Path\Path::move
	 *
	 */
	public static function move( String $from, String $to, $context = Null ): Bool {
		return( Path\Path::move( ...func_get_args() ) );
	}
	
	/*
	 * Opens file or URL.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 * @params String $mode
	 * @params Bool $include
	 * @params Resource $context
	 *
	 * @return Resource
	 *
	 * @throws Yume\Fure\IO\File\FileError
	 * @throws Yume\Fure\IO\Path\PathNotFoundError
	 */
	public static function open( String $file, String $mode = "r", Bool $include = False, $context = Null ) {
		self::assert( $file, $mode );
		if( Path\Path::exists( $file ) ) {
			throw new FileError( $file, FileError::FILE_ERROR );
		}
		$fpath = self::path( $file );
		if( $fpath === $file || $fpath !== $file && Path\Path::exists( $fpath ) ) {
			if( False !== $fopen = fopen( Path\Path::path( $file ), $mode, $include, $context ) ) {
				return( $fopen );
			}
			throw new FileError( $file, FileError::OPEN_ERROR );
		}
		throw new FileError( [ $file, $fpath ], FileError::PATH_ERROR, new Path\PathNotFoundError( $fpath ) );
	}
	
	/*
	 * Get file pathname.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 *
	 * @return String
	 */
	public static function path( String $file ): String {
		// If path has prefix e.g php:// file://
		if( preg_match( "/^([a-zA-Z][a-zA-Z0-9]*)\:\/\/(([a-zA-Z][a-zA-Z0-9]*)\/*)*/", $file ) ) {
			return( $file );
		}
		return( Util\Strings::pop( $file, DIRECTORY_SEPARATOR ) );
	}
	
	/*
	 * Read the contents of the file.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 * @params Resource $context
	 * @params Bool $close
	 *
	 * @return String
	 *
	 * @throws Yume\Fure\IO\File\FileError
	 * @throws Yume\Fure\IO\File\FileNotFoundError
	 * @throws Yume\Fure\IO\Path\PathError
	 * @throws Yume\Fure\IO\Path\PathNotFoundError
	 */
	public static function read( String $file, $context = Null, Bool $close = True ): String {
		if( is_resource( $context ) ) {
			$fopen = $context;
		}
		else {
			
			$fpath = Util\Strings::pop( $file, "/" );
			if( Path\Path::exists( $file ) ) {
				throw new FileError( $file, FileError::FILE_ERROR );
			}
			if( Path\Path::exists( $fpath, False ) ) {
				throw new FileNotFoundError( $file, previous: new Path\PathNotFoundError( $fpath ) );
			}
			if( is_readable( Path\Path::path( $fpath ) ) ) {
				if( self::exists( $file, False ) ) {
					throw new FileNotFoundError( $file );
				}
				if( is_readable( Path\Path::path( $file ) ) === False ) {
					throw new FileError( $file, FileError::READ_ERROR );
				}
				$fopen = fopen( Path\Path::path( $file ), "r" );
			}
			else {
				throw new FileError( $file, FileError::READ_ERROR, new Path\PathError( $fpath, Path\PathError::READ_ERROR ) );
			}
		}
		if( $fopen ) {
			$fsize = self::size( $fopen, 13421779 );
			$fread = "";
			while( feof( $fopen ) === False ) {
				$fread .= fread( $fopen, $fsize );
			}
			if( $close && $context || $context === Null ) {
				fclose( $fopen );
			}
			return( $fread );
		}
		else {
			throw new FileError( $file, FileError::OPEN_ERROR );
		}
	}
	
	/*
	 * Read file contents and split file contents with endline.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 * @params Bool $skip
	 * @params Resource $context
	 *
	 * @return Array
	 */
	public static function readline( String $file, Bool $skip = false, $context = Null ): Array {
		$fread = self::read( $file, $context );
		$fline = explode( "\n", $fread );
		if( $skip ) {
			return( array_filter( $fline, fn( String $line ) => valueIsNotEmpty( $line ) ) );
		}
		return( $fline );
	}
	
	/*
	 * Get file size.
	 *
	 * @access Public Static
	 *
	 * @params Resource|String $file
	 * @params Int|String $optional
	 *
	 * @return String|Int
	 *
	 * @throws Yume\Fure\IO\File\FileNotFoundError
	 */
	public static function size( $file, Int | String $optional = 0 ): Int | String {
		$fsize = 0;
		if( is_resource( $file ) ) {
			$fstat = fstat( $file );
			if( $fstat !== False ) {
				$fsize = $fstat['size'] ?? 0;
			}
		}
		else {
			if( self::exists( $file ) ) {
				$fsize = filesize( Path\Path::path( $file ) );
			}
			else {
				throw new FileNotFoundError( $file );
			}
		}
		return( $fsize ?: ( is_int( $optional ) ? $optional : strlen( $optional ) ) );
	}
	
	/*
	 * Get file created timestamp.
	 *
	 * This method DateTime class instance from file.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 *
	 * @return Yume\Fure\Util\Locale\DateTime\DateTime
	 *
	 * @throws Yume\Fure\IO\File\FileNotFoundError
	 */
	public static function ctime( String $file ): DateTime\DateTime {
		if( self::exists( $file, True ) ) {
			clearstatcache();
			$time = filectime( Path\Path::path( $file ) );
			$date = new DateTime\DateTime( "now" );
			$date->setTimestamp( $time );
			return( $date );
		}
		throw new FileNotFoundError( $file );
	}
	
	/*
	 * Get file last modification timestamp.
	 *
	 * This method DateTime class instance from file.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 *
	 * @return Yume\Fure\Util\Locale\DateTime\DateTime
	 *
	 * @throws Yume\Fure\IO\File\FileNotFoundError
	 */
	public static function mtime( String $file ): DateTime\DateTime {
		if( self::exists( $file, True ) ) {
			clearstatcache();
			$time = filemtime( Path\Path::path( $file ) );
			$date = new DateTime\DateTime( "now" );
			$date->setTimestamp( $time );
			return( $date );
		}
		throw new FileNotFoundError( $file );
	}
	
	/*
	 * Remove file.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 *
	 * @return Bool
	 */
	public static function remove( String $file ): Bool {
		return( unlink( Path\Path::path( $file ) ) );
	}
	
	/*
	 * Write or create a new file.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 * @params Resource $context
	 *
	 * @return Bool
	 *
	 * @throws Yume\Fure\Error\PermissionError
	 * @throws Yume\Fure\IO\File\FileError
	 */
	public static function write( String $file, ? String $fdata = Null, String $fmode = "w", $context = Null ): Bool | Int {
		$fwrite = False;
		if( $resource = is_resource( $context ) ) {
			$fopen = $context;
		}
		else {
			
			// Get file pathname.
			$fpath = Util\Strings::pop( $file, DIRECTORY_SEPARATOR );
			
			// Check if the filename is a directory.
			if( Path\Path::exists( $file, True ) ) {
				throw new FileError( $file, FileError::FILE_ERROR );
			}
			
			// Check if such a directory exists.
			if( Path\Path::exists( $fpath, False ) ) {
				Path\Path::make( $fpath );
			}
			
			// Check if such directory is unwritable.
			if( is_writable( Path\Path::path( $fpath ) ) === False ) {
				throw new Error\PermissionError( $fpath, Error\PermissionError::WRITE_ERROR );
			}
			
			// Check if such a file exists,
			// And if if such files are unwritable.
			if( self::exists( $file ) && is_writable( Path\Path::path( $file ) ) === False ) {
				throw new Error\PermissionError( $file, Error\PermissionError::WRITE_ERROR );
			}
			$fname = path( $file );
			$fopen = fopen( $fname, $fmode );
		}
		$fdata = $fdata ?? "";
		if( $fopen ) {
			$fwrite = is_int( fwrite( $fopen, $fdata ) );
			
			// If the stream comes from a context argument then the
			// stream will not be forwarded to fclose function.
			if( $resource === False ) {
				fclose( $fopen );
			}
		}
		else {
			throw new FileError( $file, FileError::OPEN_ERROR );
		}
		return( $fwrite );
	}
	
}

?>