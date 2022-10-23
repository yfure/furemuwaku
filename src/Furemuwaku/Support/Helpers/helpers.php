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
 * @inherit Yume\Fure\IO\Path\Path
 *
 */
function ls( String $path ): Array | Bool
{
	return( IO\Path\Path::ls( $path ) );
}

/*
 * Get fullpath name or remove basepath name.
 *
 * @params String $path
 * @params Bool $remove
 *
 * @return String
 */
function path( String $path, Bool $remove = False ): String
{
	if( $path !== Null && $remove )
	{
		return( str_replace( RegExp\RegExp::replace( "/\//", BASE_PATH, DIRECTORY_SEPARATOR ), "", $path ) );
	}
	return( Support\RegExp\RegExp::replace( "/\//", f( "{}/{}", BASE_PATH, $path ), DIRECTORY_SEPARATOR ) );
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