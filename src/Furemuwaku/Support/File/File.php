<?php

namespace Yume\Fure\Support\File;

use Yume\Fure\Error;
use Yume\Fure\Locale\DateTime;
use Yume\Fure\Support\Path;
use Yume\Fure\Util;
use Yume\Fure\Util\Json;

/*
 * File
 *
 * @package Yume\Fure\Support\File
 */
abstract class File
{
	
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
		"w",
		"w+",
		"a",
		"a+",
		"x",
		"x+",
		"c",
		"c+",
		"e"
	];
	
	/*
	 * File open assertion mode.
	 *
	 * @access Private Static
	 *
	 * @params String $file
	 * @params String $mode
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\Error\AssertionError
	 * @throws Yume\Fure\Support\File\FileError
	 */
	private static function assert( String $file, String $mode ): Void
	{
		try
		{
			// If file open mode is invalid mode.
			if( in_array( $mode, self::$modes ) === False )
			{
				throw new Error\AssertionError( [ "mode", self::$modes, $mode ], Error\AssertError::VALUE_ERROR );
			}
		}
		catch( Error\AssertionError $e )
		{
			throw new FileError( [ $file, $mode ], FileError::MODE_ERROR, $e );
		}
	}
	
	/*
	 * Check if file is executable.
	 *
	 * @access Public
	 *
	 * @params String $file
	 *
	 * @return Bool
	 */
	public static function executeable( String $file ): Bool
	{
		return( is_executable( Path\Path::path( $file ) ) );
	}
	
	/*
	 * Check if file is exists.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 *
	 * @return Bool
	 */
	public static function exists( String $file ): Bool
	{
		return( file_exists( Path\Path::path( $file ) ) && is_file( Path\Path::path( $file ) ) );
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
	 * @return Array
	 */
	public static function json( String $file, ? Bool $associative = Null, Int $depth = 512, Int $flags = 0 ): Array
	{
		return( Json\Json::decode( self::read( $file ), $associative, $depth, $flags ) );
	}
	
	/*
	 * Check if file is not found.
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
	 * @throws Yume\Fure\Support\File\FileError
	 * @throws Yume\Fure\Support\Path\PathNotFoundError
	 */
	public static function open( String $file, String $mode = "r", Bool $include = False, $context = Null )
	{
		// File mode assertion.
		self::assert( $file, $mode );
		
		// Check if the filename is a directory.
		if( Path\Path::is( $file ) )
		{
			throw new FileError( $file, FileError::FILE_ERROR );
		}
		
		// Check if such a directory exists.
		if( Path\Path::exists( $fpath = Util\Str::pop( $file, "/", True ) ) )
		{
			return( fopen( Path\Path::path( $file ), $mode, $include, $context ) );
		}
		throw new FileError( [ $file, $fpath ], FileError::PATH_ERROR, new Path\PathNotFoundError( $fpath ) );
	}
	
	/*
	 * Read the contents of the file.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 *
	 * @return String
	 *
	 * @throws Yume\Fure\Support\File\FileError
	 * @throws Yume\Fure\Support\File\FileNotFoundError
	 * @throws Yume\Fure\Support\Path\PathError
	 * @throws Yume\Fure\Support\Path\PathNotFoundError
	 */
	public static function read( String $file ): String
	{
		// Get file pathname.
		$fpath = Util\Str::pop( $file, "/", True );
		
		// Check if the filename is a directory.
		if( Path\Path::exists( $file ) )
		{
			throw new FileError( $file, FileError::FILE_ERROR );
		}
		
		// Check if such directory is exists.
		if( Path\Path::exists( $fpath ) )
		{
			// Check if such directory is unreadable.
			if( Path\Path::readable( $fpath ) )
			{
				// Check if such a file exists.
				if( self::none( $file ) )
				{
					throw new FileNotFoundError( $file );
				}
				
				// Check if such files are readable.
				if( self::readable( $file ) === False )
				{
					throw new FileError( $file, FileError::READ_ERROR );
				}
				
				// Binary-safe file open.
				if( $fopen = fopen( $file, "r" ) )
				{
					// Get file size.
					$fsize = fsize( $file, 13421779 );
					
					// Reader stack.
					$fread = "";
					
					// Binary-safe file read.
					while( feof( $fopen ) === False )
					{
						$fread .= fread( $fopen, $fsize );
					}
					
					// Closes an open file pointer.
					fclose( $fopen );
					
					return( $fread );
				}
				else {
					throw new FileError( $file, FileError::OPEN_ERROR );
				}
			}
			throw new FileError( $file, FileError::READ_ERROR, new Path\PathError( $fpath, Path\PathError::READ_ERROR ) );
		}
		throw new FileNotFoundError( $file, previous: new Path\PathNotFoundError( $fpath ) );
	}
	
	/*
	 * Read file contents and split file contents with endline.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 * @params Bool $skip
	 *
	 * @return Array
	 */
	public static function readline( String $file, Bool $skip = false ): Array
	{
		// Reading file contents.
		$fread = self::read( $file );
		
		// Split file contents with end line.
		$fline = explode( "\n", $fread );
		
		// If empty line skip.
		if( $skip )
		{
			// Mapping Lines.
			foreach( $fline As $i => $line )
			{
				// Check if the line is empty.
				if( valueIsEmpty( $line ) )
				{
					// Destroy the line.
					unset( $fline[$i] );
				}
			}
		}
		return( $fline );
	}
	
	/*
	 * Check if file is readable.
	 *
	 * @access Public
	 *
	 * @params String $file
	 *
	 * @return Bool
	 */
	public static function readable( String $file ): Bool
	{
		return( is_readable( Path\Path::path( $file ) ) );
	}
	
	/*
	 * Get file size.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 * @params Int $optional
	 *
	 * @return String|Int
	 *
	 * @throws Yume\Fure\Support\File\FileNotFoundError
	 */
	public static function size( String $file, Int $optional = 0 ): Int | String
	{
		// Check if such a file exists.
		if( self::exists( $file ) )
		{
			return( filesize( Path\Path::path( $file ) ) ?: $optional );
		}
		throw new FileNotFoundError( $file );
	}
	
	/*
	 * Get DateTime class instance from file.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 *
	 * @return DateTime
	 *
	 * @throws Yume\Fure\Support\File\FileNotFoundError
	 */
	public static function time( String $file ): DateTime
	{
		// Check if such a file exists.
		if( self::exists( $file, True ) )
		{
			// Get timestamp value from file.
			$time = filemtime( Path\Path::path( $file ) );
			
			// Create new instance of DateTime class.
			$date = new DateTime;
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
	public static function unlink(): Bool
	{
		return( unlink( Path\Path::path( $file ) ) );
	}
	
	/*
	 * Write or create a new file.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 *
	 * @return Bool
	 *
	 * @throws Yume\Fure\Error\PermissionError
	 * @throws Yume\Fure\Support\File\FileError
	 */
	public static function write( String $file, ? String $fdata = Null, String $fmode = "w" ): Void
	{
		// Get file pathname.
		$fpath = Util\Str::pop( $file, DIRECTORY_SEPARATOR );
		
		// Check if the filename is a directory.
		if( Path\Path::exists( $file ) )
		{
			throw new FileError( $file, FileError::FILE_ERROR );
		}
		
		// Check if such a directory exists.
		if( Path\Path::exists( $fpath ) === False )
		{
			Path\Path::mkdir( $fpath );
		}
		
		// Check if such directory is unwriteable.
		if( Path\Path::writeable( $fpath ) === False )
		{
			throw new Error\PermissionError( $fpath, Error\PermissionError::WRITE_ERROR );
		}
		
		// Check if such a file exists.
		if( self::exists( $file ) )
		{
			// Check if such files are unwriteable.
			if( self::writeable( $file ) === False )
			{
				throw new Error\PermissionError( $file, Error\PermissionError::WRITE_ERROR );
			}
		}
		
		// Add prefix base path.
		$fname = path( $file );
		
		// File contents.
		$fdata = $fdata ? $fdata : "";
		
		// Binary-safe file open.
		if( $fopen = fopen( $fname, $fmode ) )
		{
			// Binary-safe file write.
			fwrite( $fopen, $fdata );
			
			// Closes an open file pointer.
			fclose( $fopen );
		}
		else {
			throw new FileError( $file, FileError::OPEN_ERROR );
		}
	}
	
	/*
	 * Check if file is writeable.
	 *
	 * @access Public Static
	 *
	 * @params String $file
	 *
	 * @return Bool
	 */
	public static function writeable( String $file )
	{
		return( is_writeable( Path\Path::path( $file ) ) );
	}
	
}

?>