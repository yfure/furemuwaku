<?php

use Yume\Fure\Config;
use Yume\Fure\Database;
use Yume\Fure\Error;
use Yume\Fure\HTTP;
use Yume\Fure\Support;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\File;
use Yume\Fure\Support\Path;
use Yume\Fure\Support\Services;
use Yume\Fure\Util;
use Yume\Fure\Util\Env;
use Yume\Fure\Util\Json;
use Yume\Fure\Util\RegExp;
use Yume\Fure\View;

/*
 * @inherit Yume\Fure\Config\Config
 *
 */
function config( String $name, Bool $import = False ): Mixed
{
	return( Services\Services::get( "app" ) )->config( $name, $import );
}

function e( $e ): Void
{
	if( $e->getPrevious() )
	{
		do {
			e( $e );
		}
		while( $e = $e->getPrevious() );
		return;
	}
	echo path( remove: True, path: f( "\x1b[1;32m{}\x1b[1;33m: \x1b[0;37m{} in file \x1b[1;36m{} \x1b[0;37mon line \x1b[1;31m{}\n\x1b[1;30m{}\n", ...[
		$e::class,
		$e->getMessage(),
		$e->getFile(),
		$e->getLine(),
		$e->getTrace()
	]));
}

/*
 * @inherit Yume\Fure\Support\Env\Env
 *
 */
function env( String $env, Mixed $optional = Null )
{
	return( Env\Env::get( $env, $optional ) );
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
 * @inherit Yume\Fure\Support\File\File
 *
 */
function fsize( String $file, Int $optional = 0 ): Int
{
	return( File\File::size( $file, $optional ) );
}

/*
 * @inherit Yume\Fure\Support\Path\Path
 *
 */
function ls( String $path ): Array | Bool
{
	return( Path\Path::ls( $path ) );
}

/*
 * @inherit Yume\Fure\Support\Path\Path
 */
function path( String $path, Bool $remove = False ): String
{
	return( Path\Path::path( $path, $remove ) );
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
 * @inherit Yume\Fure\Support\Path\Path
 *
 */
function tree( String $path, String $parent = "" ): Array | False
{
	return( Path\Path::tree( $path, $parent ) );
}

/*
 * Check if value is empty.
 *
 * @params Mixed $value
 *
 * @return Bool
 */
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
			return( RegExp\RegExp::test( "/^([\s\t\n]*)$/", $value ) );
	}
	return( False );
}

/*
 * Check if value is empty.
 *
 * @params Mixed $value
 *
 * @return Bool
 */
function valueIsNotEmpty( Mixed $value ): Bool
{
	return( valueIsEmpty( $value ) === False );
}



?>