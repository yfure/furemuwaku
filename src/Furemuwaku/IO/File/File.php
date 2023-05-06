<?php

namespace Yume\Fure\IO\File;

use Yume\Fure\Error;
use Yume\Fure\IO\Path;
use Yume\Fure\Util;
use Yume\Fure\Util\Json;
use Yume\Fure\Util\Locale;

/*
 * File
 *
 * @package Yume\Fure\IO\File
 */
class File
{
	
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
	public static function assert( String $file, String $mode ): Void
	{
		try
		{
			// Check if mode is not supported.
			if( in_array( $mode, self::$modes ) === False ) throw new Error\AssertionError( [ "mode", self::$modes, $mode ], Error\AssertionError::VALUE_ERROR );
		}
		catch( Error\AssertionError $e )
		{
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
	public static function copy( String $from, String $to, Bool $overwrite = False, Int $permission = 0755 ): Bool
	{
		/*
		 * If the source is a directory, create the
		 * destination directory if it doesn't exist,
		 * then copy each file and subdirectory
		 * recursively to the new location.
		 *
		 */
		if( Path\Path::exists( $from ) ) return( Path\Path::copy( ...func_get_args() ) );
		
		// If the source is a file, simply copy it to the destination.
		if( self::exists( $from ) )
		{
			// If destination is a directory, append filename to the path.
			if( Path\Path::exists( $to ) )
			{
				$to .= DIRECTORY_SEPARATOR . basename( $from );
			}
			else {
				
				/*
				 * Check if file already exists.
				 * And if file is not allowed for overwrite.
				 *
				 */
				if( self::exists( $to ) && $overwrite === False ) return( False );
			}
			
			// Copy the file and set permissions.
			if( copy( Path\Path::path( $from ), Path\Path::path( $to ) ) )
			{
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
	public static function exists( String $file, ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? is_file( Path\Path::path( $file ) ) === $optional : is_file( Path\Path::path( $file ) ) );
	}
	
	/*
	 * Return supported file open modes.
	 *
	 * @access Public Static
	 *
	 * @return Array
	 */
	public static function getSupportedModes(): Array
	{
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
	public static function isReadableMode( String $mode ): Bool
	{
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
	public static function isWritableMode( String $mode ): Bool
	{
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
	public static function json( String $file, ? Bool $associative = Null, Int $depth = 512, Int $flags = 0 ): Mixed
	{
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
	public static function modified( String $file, Int $long = 60 ): Bool
	{
		// If file is exists.
		if( self::exists( $file ) )
		{
			return( Locale\Locale::getDateTime()->getTimestamp() - self::mtime( $file )->getTimestamp() <= $long );
		}
		return( False );
	}
	
	/*
	 * @inherit Yume\Fure\IO\Path\Path::move
	 *
	 */
	public static function move( String $from, String $to, $context = Null ): Void
	{
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
	public static function open( String $file, String $mode = "r", Bool $include = False, $context = Null )
	{
		// File mode assertion.
		self::assert( $file, $mode );
		
		// Check if the filename is a directory.
		if( Path\Path::exists( $file ) ) throw new FileError( $file, FileError::FILE_ERROR );
		
		// Get file pathname.
		$fpath = self::path( $file );
		
		// Check if such a directory exists.
		if( $fpath === $file || $fpath !== $file && Path\Path::exists( $fpath ) )
		{
			if( False !== $fopen = fopen( Path\Path::path( $file ), $mode, $include, $context ) )
			{
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
	public static function path( String $file ): String
	{
		// If path has prefix e.g php:// file://
		if( preg_match( "/^([a-zA-Z][a-zA-Z0-9]*)\:\/\/(([a-zA-Z][a-zA-Z0-9]*)\/*)*/", $file ) )
		{
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
	public static function read( String $file, $context = Null, Bool $close = True ): String
	{
		// Check if context is Resource type.
		if( is_resource( $context ) )
		{
			$fopen = $context;
		}
		else {
			
			// Get file pathname.
			$fpath = Util\Strings::pop( $file, "/" );
			
			// Check if the file is a directory.
			if( Path\Path::exists( $file ) ) throw new FileError( $file, FileError::FILE_ERROR );
			
			// Check if no such directory.
			if( Path\Path::exists( $fpath, False ) ) throw new FileNotFoundError( $file, previous: new Path\PathNotFoundError( $fpath ) );
			
			// Check if such directory is unreadable.
			if( is_readable( Path\Path::path( $fpath ) ) )
			{
				// Check if such a file exists.
				if( self::exists( $file, False ) ) throw new FileNotFoundError( $file );
				
				// Check if such files are readable.
				if( is_readable( Path\Path::path( $file ) ) === False ) throw new FileError( $file, FileError::READ_ERROR );
				
				// Binary-safe file open.
				$fopen = fopen( Path\Path::path( $file ), "r" );
			}
			else {
				throw new FileError( $file, FileError::READ_ERROR, new Path\PathError( $fpath, Path\PathError::READ_ERROR ) );
			}
		}
		
		// Check if file success open.
		if( $fopen )
		{
			// Get file size.
			$fsize = fsize( $fopen, 13421779 );
			
			// Reader stack.
			$fread = "";
			
			// Binary-safe file read.
			while( feof( $fopen ) === False ) $fread .= fread( $fopen, $fsize );
			
			// Closes an open file pointer.
			if( $close && $context || $context === Null ) fclose( $fopen );
			
			// Return readed contents.
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
	public static function readline( String $file, Bool $skip = false, $context = Null ): Array
	{
		// Reading file contents.
		$fread = self::read( $file, $context );
		
		// Split file contents with end line.
		$fline = explode( "\n", $fread );
		
		// If empty line skip.
		if( $skip )
		{
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
	public static function size( $file, Int | String $optional = 0 ): Int | String
	{
		// Default file size.
		$fsize = 0;
		
		// Check if file is Resource type.
		if( is_resource( $file ) )
		{
			// Get file information.
			$fstat = fstat( $file );
			
			// Check if info is available.
			if( $fstat !== False )
			{
				$fsize = $fstat['size'] ?? 0;
			}
		}
		else {
			
			// Check if such a file exists.
			if( self::exists( $file ) )
			{
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
	 * @return Yume\Fure\Util\Locale\DateTime
	 *
	 * @throws Yume\Fure\IO\File\FileNotFoundError
	 */
	public static function ctime( String $file ): Locale\DateTime
	{
		// Check if such a file exists.
		if( self::exists( $file, True ) )
		{
			clearstatcache();
			
			// Get timestamp from file created.
			$time = filectime( Path\Path::path( $file ) );
			
			$date = new Locale\DateTime;
			$date->setTimestamp( $time );
			
			// Return DateTime instance.
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
	 * @return Yume\Fure\Util\Locale\DateTime
	 *
	 * @throws Yume\Fure\IO\File\FileNotFoundError
	 */
	public static function mtime( String $file ): Locale\DateTime
	{
		// Check if such a file exists.
		if( self::exists( $file, True ) )
		{
			clearstatcache();
			
			// Get timestamp from last modification.
			$time = filemtime( Path\Path::path( $file ) );
			
			$date = new Locale\DateTime;
			$date->setTimestamp( $time );
			
			// Return DateTime instance.
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
	public static function remove( String $file ): Bool
	{
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
	public static function write( String $file, ? String $fdata = Null, String $fmode = "w", $context = Null ): Bool | Int
	{
		// Default function return.
		$fwrite = False;
		
		// Check if context is Resource type.
		if( $resource = is_resource( $context ) )
		{
			$fopen = $context;
		}
		else {
			
			// Get file pathname.
			$fpath = Util\Strings::pop( $file, DIRECTORY_SEPARATOR );
			
			// Check if the filename is a directory.
			if( Path\Path::exists( $file, True ) ) throw new FileError( $file, FileError::FILE_ERROR );
			
			// Check if such a directory exists.
			if( Path\Path::exists( $fpath, False ) ) Path\Path::make( $fpath );
			
			// Check if such directory is unwritable.
			if( is_writable( Path\Path::path( $fpath ) ) === False ) throw new Error\PermissionError( $fpath, Error\PermissionError::WRITE_ERROR );
			
			// Check if such a file exists,
			// And if if such files are unwritable.
			if( self::exists( $file ) && is_writable( Path\Path::path( $file ) ) === False ) throw new Error\PermissionError( $file, Error\PermissionError::WRITE_ERROR );
			
			// Add prefix base path.
			$fname = path( $file );
			
			// Binary-safe file open.
			$fopen = fopen( $fname, $fmode );
		}
		
		// File contents.
		$fdata = $fdata ?? "";
		
		// Check if file success open.
		if( $fopen )
		{
			// Binary-safe file write.
			$fwrite = is_int( fwrite( $fopen, $fdata ) );
			
			// If the stream comes from a context argument then the
			// stream will not be forwarded to fclose function.
			if( $resource === False )
			{
				// Closes an open file pointer.
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