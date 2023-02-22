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

function clear( String $string ): String
{
	return( preg_replace( "/(^\s+)|(\s+$)/", "", $string ) );
}

/*
 * @inherit Yume\Fure\Config\Config
 *
 */
function config( String $name, Bool $import = False ): Mixed
{
	return( App\App::config( $name, $import ) );
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
 * @inherit Yume\Fure\Util\Str::fmt
 *
 */
function f( String $string, Mixed ...$format ): String
{
	return( Util\Str::fmt( $string, ...$format ) );
}

/*
 * @inherit Yume\Fure\Util\Arr::ify
 *
 */
function ify( Array | String $refs, Array | ArrayAccess $data ): Mixed
{
	return( Util\Arr::ify( $refs, $data ) );
}

/*
 * @inherit Yume\Fure\Support\File\File::size
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
	if( Services\Services::available( "logger", False ) )
	{
		Services\Services::register( "logger", new Logger\Logger(), False );
	}
	if( $level !== Null && $message !== Null && $context !== Null )
	{
		return( Services\Services::get( "logger" ) )->log( $level, $message, $context );
	}
	return( Services\Services::get( "logger" ) );
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
 * Alias echo.
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
	if( is_object( $value ) )
	{
		return( $value::class );
	}
	return( ucfirst( gettype( $value ) ) );
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