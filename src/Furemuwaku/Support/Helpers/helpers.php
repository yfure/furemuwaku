<?php

use Yume\Fure\App;
use Yume\Fure\Cache;
use Yume\Fure\Config;
use Yume\Fure\Database;
use Yume\Fure\Error;
use Yume\Fure\HTTP;
use Yume\Fure\Locale;
use Yume\Fure\Logger;
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
use Yume\Fure\View\Template;

/*
 * Get cache item or cache item pool
 *
 * @params String $key
 *
 * @return Yume\Fure\Cache\CacheItemInterface|Yume\Fure\Cache\CacheItemPoolInterface|
 */
function cache( ? String $key = Null ): Cache\CacheItemInterface | Cache\CacheItemPoolInterface
{
	if( valueIsNotEmpty( $key ) )
	{
		return( Cache\Cache::get( $key ) );
	}
	return( Cache\Cache::pool() );
}

/*
 * @inherit Yume\Fure\Config\Config
 *
 */
function config( String $name, Bool $import = False ): Mixed
{
	return( App\App::self() )->config( $name, $import );
}

function e( Throwable $e ): Void
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
	try
	{
		return( Env\Env::get( $env, $optional ) );
	}
	catch( Env\EnvError $e )
	{
		// Check if optional value is Callable type.
		if( is_callable( $optional ) )
		{
			return( call_user_func( $optional ) );
		}
		return( $optional );
	}
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

function executionTimeCompare2x( $start, $end )
{
	//$start = array_sum( explode( "\x20", $start ) );
	//$end = array_sum( explode( "\x20", $end ) );
	
	$time = $end - $start;
	
	$formatted_time_ms = number_format($time * 1000, 4);
	$formatted_time_s = number_format($time, 4);
	$formatted_time_m = number_format($time / 60, 4);
	
	$time_unit = "ms";
	
	if ($formatted_time_ms >= 1000)
	{
		$formatted_time_ms = round($formatted_time_ms / 1000, 2);
		return( f( "{}s", $formatted_time_ms ) );
	}
	
	if ($formatted_time_s >= 60)
	{
		return( f( "{}m", $formatted_time_m ) );
	}
	
	//return [$formatted_time_ms, $formatted_time_s, $formatted_time_m, $time_unit];
	return( f( "{}ms", $formatted_time_s ) );
}

/*
 * Get last execution time compared.
 *
 * @return Float|String
 */
function executionTimeCompareEnd(): Float | String
{
	return( executionTimeCompare( YUME_START, microtime( True ) ) );
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
function fsize( $file, Int | String $optional = 0 ): Int
{
	return( File\File::size( $file, $optional ) );
}

/*
 * @inherit Yume\Fure\Locale\Language
 *
 */
function lang( String $ify, Mixed ...$format ): String
{
	return( Locale\Locale::translate( $ify, ...$format ) ?? $ify );
}

/*
 * Write new log or get Logger instance class.
 *
 * @params Int|String|Yume\Fure\Logger\LoggerLevel
 * @params String $message
 * @params Array $context
 *
 * @return Yume\Fure\Logger\LoggerInterface
 */
function logger( Int | Null | String | Logger\LoggerLevel $level = Null, ? String $message = Null, ? Array $context = Null ): ? Logger\LoggerInterface
{
	// If arguments given is not Null type.
	if( $level !== Null &&
		$message !== Null &&
		$context !== Null )
	{
		return( Services\Services::get( Logger\Logger::class ) )->log( $level, $message, $context );
	}
	return( Services\Services::get( Logger\Logger::class ) );
}

/*
 * @inherit Yume\Fure\Support\Path\Path::ls
 *
 */
function ls( String $path ): Array | Bool
{
	return( Path\Path::ls( $path ) );
}

/*
 * @inherit Yume\Fure\Support\Path\Path::path
 */
function path( String $path, Bool | Path\PathName $prefix_or_remove = False ): String
{
	return( Path\Path::path( $path, $prefix_or_remove ) );
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
 * @inherit Yume\Fure\Support\Path\Path::tree
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
 *
 * @return String
 */
function type( Mixed $value ): String
{
	// Check if value is object type.
	if( is_object( $value ) )
	{
		return( $value::class );
	}
	return( gettype( $value ) );
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
	return( match( True )
	{
		// If `value` is Int type.
		is_int( $value ) => $value === 0,
		
		// If `value` is Null type.
		is_null( $value ) => True,
		
		// If `value` is Bool type.
		is_bool( $value ) => $value === False,
		
		// If `value` is Array type.
		is_array( $value ) => count( $value ) === 0,
		
		// If `value` is String type.
		is_string( $value ) => preg_match( "/^([\r\t\n\s]*)$/", $value ),
		
		default => False
	});
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