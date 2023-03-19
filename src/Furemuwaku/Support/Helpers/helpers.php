<?php

/*
 * Yume Builtin Autoload Helpers.
 *
 */

use Yume\Fure\App;
use Yume\Fure\CLI;
use Yume\Fure\Database;
use Yume\Fure\Error;
use Yume\Fure\HTTP;
use Yume\Fure\HTTP\Stream;
use Yume\Fure\Locale;
use Yume\Fure\Logger;
use Yume\Fure\Services;
use Yume\Fure\Support\Config;
use Yume\Fure\Support\Data;
use Yume\Fure\Util\Array;
use Yume\Fure\Util\Env;
use Yume\Fure\Util\File;
use Yume\Fure\Util\File\Path;
use Yume\Fure\Util\Json;
use Yume\Fure\Util\Reflect;
use Yume\Fure\Util\RegExp;
use Yume\Fure\Util\Type;
use Yume\Fure\View;
use Yume\Fure\View\Template;

// Check if helper function does not exists.
if( function_exists( "helper" ) === False )
{
	/*
	 * Import builtin helper files.
	 *
	 * @params String $path
	 * @params String $self
	 *
	 * @return Void
	 */
	function helper( String $path, String $self ): Void
	{
		// Scaning directory.
		$ls = array_diff( scandir( $path ), [ ".", "..", $self ] );
		
		// Mapping helpers.
		foreach( $ls As $file )
		{
			if( is_dir( $file = $path . DIRECTORY_SEPARATOR . $file ) )
			{
				helper( $file, $self );
			}
			else {
				require $file;
			}
		}
	}
}

// Get file name.
$self = explode( DIRECTORY_SEPARATOR, __FILE__ );
$self = end( $self );

// Importing helpers.
helper( __DIR__, $self );

function clear( String $string ): String
{
	return( preg_replace( "/(^\s+)|(\s+$)/", "", $string ) );
}

/*
 * Parse exception class into string.
 *
 * @params Throwable $e
 *
 * @return Void
 */
function e( Throwable $e ): Void
{
	/*
	 * @inherit Yume\Fure\Error\BaseError::format
	 *
	 */
	$f = static function( Throwable $thrown )
	{
		if( $thrown Instanceof BaseError )
		{
			$format = "\n{class}: {type}: {message} on file {file} line {line} code {code}.\n{class}{trace}\n";
		}
		else {
			$format = "\n{class}: {message} on file {file} line {line} code {code}.\n{class}{trace}\n";
		}
		return( f( $format, class: $thrown::class, message: $thrown->getMessage(), file: $thrown->getFile(), line: $thrown->getLine(), code: $thrown->getCode(), type: $thrown->type ?? "None", trace: $thrown->getTrace() ) );
	};
	
	if( $e Instanceof BaseError )
	{
		echo $e;
	}
	else {
		$error = $e;
		$stack = [
			$f( $e )
		];
		while( $error = $error->getPrevious() )
		{
			$stack[] = $f( $error );
		}
		echo( path( implode( "\n", array_reverse( $stack ) ), True ) );
	}
}

/*
 * @inherit Yume\Fure\Type\Str::fmt
 *
 */
function f( String $string, Mixed ...$format ): String
{
	return( Type\Str::fmt( $string, ...$format ) );
}

/*
 * @inherit Yume\Fure\Array\Arr::ify
 *
 */
function ify( Array | String $refs, Array | ArrayAccess $data ): Mixed
{
	return( Array\Arr::ify( $refs, $data ) );
}

/*
 * @inherit Yume\Fure\Util\File\File::size
 *
 */
function fsize( $file, Int | String $optional = 0 ): Int
{
	return( File\File::size( $file, $optional ) );
}

/*
 * @inherit Yume\Fure\Locale\Language::translate
 *
 */
function lang( String $ify, Mixed ...$format ): String
{
	return( Locale\Locale::translate( $ify, ...$format ) ?? $ify );
}

/*
 * @inherit Yume\Fure\Util\File\Path\Path::ls
 *
 */
function ls( String $path ): Array | Bool
{
	return( Path\Path::ls( $path ) );
}

/*
 * @inherit Yume\Fure\Util\File\Path\Path::path
 */
function path( String $path, Bool | Path\PathName $prefix_or_remove = False ): String
{
	return( Path\Path::path( $path, $prefix_or_remove ) );
}

/*
 * Alias echo.
 *
 * @params String $string
 * @params Mixed $format
 *
 * @return Void
 */
function puts( String $string, Mixed ...$format ): Void
{
	echo( Type\Str::fmt( $string, ...$format ) );
}

/*
 * @inherit Yume\Fure\Util\File\Path\Path::tree
 *
 */
function tree( String $path, String $parent = "" ): Array | False
{
	return( Path\Path::tree( $path, $parent ) );
}

/*
 * Get value type.
 *
 * @params Mixed $value
 * @params String $optional
 * @params Bool $disable
 * @params Mixed $ref
 *
 * @return String
 */
function type( Mixed $value, ? String $optional = Null, Bool $disable = False, Mixed &$ref = Null ): Bool | String
{
	// If optional argument available.
	if( $optional !== Null ) return( ucfirst( $optional ) === type( $value, Null, $disable, $ref ) );
	
	// If value is Object type.
	if( is_object( $value ) )
	{
		return( $ref = $disable ? "Object" : $value::class );
	}
	return( $ref = ucfirst( gettype( $value ) ) );
}

/*
 * Check if value is empty.
 *
 * @params Mixed $value
 * @params Bool $optional
 *
 * @return Bool
 */
function valueIsEmpty( Mixed $value, ? Bool $optional = Null ): Bool
{
	try
	{
		// If value is Resource type.
		if( is_resource( $value ) ) $value = new Stream\Stream( $value );
		
		// If value is instance of Countable.
		if( is_countable( $value ) ) $value = $value->count();
		
		// If value is instance of Stream.
		if( $value Instanceof Stream\StreamInterface ) $value = $value->getSize();
		
		$empty = match( True )
		{
			is_int( $value ) => $value === 0,
			is_null( $value ) => True,
			is_bool( $value ) => $value === False,
			is_array( $value ) => $value === [],
			is_string( $value ) => preg_match( "/^([\r\t\n\s]*)$/", $value ) || $value === ""
		};
	}
	catch( UnhandledMatchError )
	{
		$empty = False;
	}
	return( $optional === Null ? $empty : $empty === $optional );
}

/*
 * Check if value is not empty.
 *
 * @params Mixed $value
 * @params Bool $optional
 *
 * @return Bool
 */
function valueIsNotEmpty( Mixed $value, ? Bool $optional = Null ): Bool
{
	return( $optional === Null ? valueIsEmpty( $value, False ) : valueIsEmpty( $value, False ) === $optional );
}

/*
 * Return view contents.
 *
 * @params String $view
 * @params Array|Yume\Fure\Support\Data\DataInterface $data
 *
 * @return
 */
function view( String $view, Array | Data\DataInterface $data = [] )//: View\ViewInterface
{
	return( new View\View( $view, $data ) );
}

?>