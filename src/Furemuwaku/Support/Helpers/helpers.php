<?php

use Yume\App\Models;
use Yume\App\Views;

use Yume\Fure\App;
use Yume\Fure\Database;
use Yume\Fure\Error;
use Yume\Fure\HTTP;
use Yume\Fure\IO;
use Yume\Fure\Seclib;
use Yume\Fure\Support;
use Yume\Fure\Util;
use Yume\Fure\View;

/*
 * @inherit Yume\Fure\Support\Services\Services
 *
 */
function config( String $name, Bool $reImport = False ): App\Config\Config
{
	return( Support\Services\Services::config( $name, $reImport ) );
}

function env( String $env, Mixed $optional = Null )
{
	return( Support\Env\Env::self() )->getEnv( $env, $optional );
}

/*
 * Compare execution time.
 *
 * @params Float|String $start
 * @params Float|String $end
 *
 * @return Float|String
 */
function executionTimeCompare( Float | String $start, Float | String $end ): Float | String
{
	// Sum of all the values in the array.
	$timeStart = array_sum( explode( "\x20", $start ) );
	$timeEnd = array_sum( explode( "\x20", $end ) );
	
	// Format a number with grouped thousands.
	return( number_format( $timeEnd - $timeStart, 6 ) );
}

/*
 * @inherit Yume\Fure\Util\Str
 *
 */
function f( String $string, Mixed ...$format ): String
{
	return( Util\Str::fmt( $string, ...$format ) );
}

/*
 * @inherit Yume\Fure\Util\Arr
 *
 */
function ify( Array | String $refs, Array | ArrayAccess $data ): Mixed
{
	return( Util\Arr::ify( $refs, $data ) );
}

/*
 * @inherit Yume\Fure\IO\File\File
 *
 */
function fsize( String $file, Int $optional = 0 ): Int
{
	return( IO\File\File::size( $file, $optional ) );
}

/*
 * @inherit Yume\Fure\IO\Path\Path
 *
 */
function ls( String $path ): Array | Bool
{
	return( IO\Path\Path::ls( $path ) );
}

/*
 * @inherit Yume\Fure\IO\Path\Path
 */
function path( String $path, Bool $remove = False ): String
{
	return( IO\Path\Path::path( $path, $remove ) );
}

/*
 * Explain echo.
 *
 * @params String $string
 * @params Mixed $format
 *
 * @return Void
 */
function puts( String $string, Mixed ...$format ): Void
{
	echo( Util\Str::fmt( $string, ...$format ) );
}

/*
 * @inherit Yume\Fure\IO\Path\Path
 *
 */
function tree( String $path, String $parent = "" ): Array | False
{
	return( IO\Path\Path::tree( $path, $parent ) );
}

function valueIsEmpty( Mixed $value ): Bool
{
	switch( True )
	{
		// If `value` is Int type.
		case is_int( $value ):
			return( $value === 0 );
			
		// If `value` is Null type.
		case is_null( $value ): return( True );
		
		// If `value` is Bool type.
		case is_bool( $value ):
			return( $value === False );
		
		// If `value` is Array type.
		case is_array( $value ):
			return( count( $value ) === 0 );
			
		// If `value` is String type.
		case is_string( $value ):
			return( Support\RegExp\RegExp::test( "/^([\s\t\n]*)$/", $value ) );
	}
	return( False );
}

function valueIsNotEmpty( Mixed $value ): Bool
{
	return( valueIsEmpty( $value ) === False );
}

/*
 * ...
 *
 * @params String $view
 * @params Array|Yume\Fure\Support\Data\DataInterface $vars
 *
 * @return Yume\Fure\View\ViewInterface
 */
function view( String $view, Array | Support\Data\DataInterface $vars ): View\ViewInterface
{
	
}

?>