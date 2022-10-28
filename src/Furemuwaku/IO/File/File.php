<?php

namespace Yume\Fure\IO\File;

use DateTime;

use Yume\Fure\Error;
use Yume\Fure\IO;
use Yume\Fure\Util;

/*
 * File
 *
 * @package Yume\Fure\IO\File
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
	 * @throws Yume\Fure\Error\AssertError
	 * @throws Yume\Fure\Error\FileError
	 */
	private static function assertMode( String $file, String $mode ): Void
	{
		try
		{
			// If file open mode is invalid mode.
			if( in_array( $mode, self::$modes ) === False )
			{
				throw new Error\AssertError( [ "mode", self::$modes, $mode ], Error\AssertError::VALUE_ERROR );
			}
		}
		catch( Error\AssertionError $e )
		{
			throw new Error\FileError( [ $file, $mode ], Error\FileError::MODE_ERROR, $e );
		}
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
		return( is_file( path( $file ) ) );
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
		return( Util\JSON::decode( self::read( $file ), $associative, $depth, $flags ) );
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
	 * @throws Yume\Fure\Error\FileError
	 */
	public static function open( String $file, String $mode = "r", Bool $include = False, $context = Null )
	{
		// File mode assertion.
		self::assertMode( $file, $mode );
		
		// Check if the filename is not a directory.
		if( IO\Path\Path::exists( $file ) === False )
		{
			// Check if such a directory exists.
			if( IO\Path\Path::exists( $fpath = Util\Str::pop( $file, "/", True ) ) )
			{
				return( fopen( $file, $mode, $include, $context ) );
			}
			throw new Error\FileError( $fpath, Error\FileError::PATH_ERROR );
		}
		throw new Error\FileError( $file, Error\FileError::TYPE_ERROR );
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
	 * @throws Yume\Fure\Error\FileError
	 * @throws Yume\Fure\Error\PathError
	 */
	public static function read( String $file ): String
	{
		// Check if the filename is a directory.
		if( IO\Path\Path::exists( $file ) )
		{
			throw new Error\FileError( $file, Error\FileError::TYPE_ERROR );
		}
		
		// Check if such directory is exists.
		if( IO\Path\Path::exists( $fpath = Util\Str::pop( $file, DIRECTORY_SEPARATOR, True ) ) )
		{
			// Check if such directory is unreadable.
			if( IO\IO::readable( $fpath ) )
			{
				// Check if such a file exists.
				if( self::exists( $file ) === False )
				{
					throw new Error\FileError( $file, Error\FileError::FILE_ERROR );
				}
				
				// Check if such files are readable.
				if( IO\IO::readable( $file ) === False )
				{
					throw new Error\FileError( $file, Error\FileError::READ_ERROR );
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
					throw new Error\FileError( $file, Error\FileError::OPEN_ERROR );
				}
			}
			throw new Error\PathError( $fpath, Error\PathError::READ_ERROR );
		}
		throw new Error\PathError( $fpath, Error\PathError::PATH_ERROR );
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
				if( $line === "" )
				{
					// Destroy the line.
					unset( $fline[$i] );
				}
			}
		}
		return( $fline );
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
	 * @throws Yume\Fure\Error\FileError
	 */
	public static function size( String $file, Int $optional = 0 ): Int | String
	{
		// Check if such a file exists.
		if( self::exists( $file ) )
		{
			return( filesize( path( $file ) ) ?: $optional );
		}
		throw new Error\FileError( $file, Error\FileError::FILE_ERROR );
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
	 * @throws Yume\Fure\Error\FileError
	 */
	public static function time( String $file ): DateTime
	{
		// Check if such a file exists.
		if( self::exists( $file ) )
		{
			// Get timestamp value from file.
			$time = filemtime( path( $file ) );
			
			// Create new instance of DateTime class.
			$date = new DateTime;
			$date->setTimestamp( $time );
			
			// Return DateTime instance.
			return( $date );
		}
		throw new Error\FileError( $file, Error\FileError::FILE_ERROR );
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
		return( unlink( path( $file ) ) );
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
	 * @throws Yume\Fure\Error\FileError
	 * @throws Yume\Fure\Error\PermissionError
	 */
	public static function write( String $file, ? String $fdata = Null, String $fmode = "w" ): Void
	{
		// Check if the filename is a directory.
		if( IO\Path\Path::exists( $file ) )
		{
			throw new Error\FileError( $file, Error\FileError::TYPE_ERROR );
		}
		
		// Check if such a directory exists.
		if( IO\Path\Path::exists( $fpath = Util\Str::pop( $file, DIRECTORY_SEPARATOR ) ) === False )
		{
			IO\Path\Path::mkdir( $fpath );
		}
		
		// Check if such directory is unwriteable.
		if( IO\IO::writeable( $fpath ) === False )
		{
			throw new Error\PermissionError( $fpath, Error\PermissionError::WRITE_ERROR );
		}
		
		// Check if such a file exists.
		if( self::exists( $file ) )
		{
			// Check if such files are unwriteable.
			if( IO\IO::writeable( $file ) === False )
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
			throw new Error\FileError( $file, Error\FileError::OPEN_ERROR );
		}
	}

}

?>