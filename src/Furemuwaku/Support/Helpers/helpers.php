<?php

/*
 * Yume PHP Framework.
 *
 * @author Ari Setiawan
 * @create 05.02-2022
 * @update -
 * @github https://github.com/yfure/Yume
 *
 * By making this, it is hoped that developers can easily build
 * programs without writing room names or classes at length.
 *
 * Copyright (c) 2022 Ari Setiawan
 * Copyright (c) 2022 Yume Framework
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

use Yume\Fure\Error;
use Yume\Fure\IO\Path;
use Yume\Fure\Locale;
use Yume\Fure\Locale\Clock;
use Yume\Fure\Locale\DateTime;
use Yume\Fure\Main;
use Yume\Fure\Support;
use Yume\Fure\Util;
use Yume\Fure\Util\Env;

/*
 * @inherit Yume\Fure\Main\Main::config
 *
 */
function config( String $name, Mixed $optional = Null, Bool $shared = True, Bool $import = False ): Mixed
{
	return( Main\Main::config( ...func_get_args() ) );
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
	$output = "";
	
	/*
	 * @inherit Yume\Fure\Error\YumeError::format
	 *
	 */
	$format = static function( Throwable $thrown )
	{
		$values = [
			"class" => $thrown::class,
			"message" => $thrown->getMessage(),
			"file" => $thrown->getFile(),
			"line" => $thrown->getLine(),
			"code" => $thrown->getCode(),
			"trace" => $thrown->getTrace(),
			"type" => $thrown->type ?? "None"
		];
		if( $thrown Instanceof Error\YumeError )
		{
			$values = [ "\n{class}: {type}: {message} on file {file} line {line} code {code}.\n{class}{trace}\n", ...$values ];
		}
		else {
			$values = [ "\n{class}: {message} on file {file} line {line} code {code}.\n{class}{trace}\n", ...$values ];
		}
		return( Util\Strings::format( ...$values ) );
	};
	if( $e Instanceof Error\YumeError )
	{
		$output = $e->__toString();
	}
	else {
		
		/*
		 * Current exception thrown.
		 *
		 */
		$stack = [
			$format( $error = $e )
		];
		
		// Getting previous exception throwns.
		while( $error = $error->getPrevious() ) $stack[] = $f( $error );
		
		// Push exception trace strings.
		$output .= join( "\n", array_reverse( $stack ) );
	}
	puts( "{}\n", $output );
}

/*
 * @inherit Yume\Fure\Util\Env\Env::get
 *
 */
function env( String $name, Mixed $optional = Null ): Mixed
{
	return( Env\Env::get( ...func_get_args() ) );
}

/*
 * @inherit Yume\Fure\Util\Format::format
 *
 */
function f( String $format, Mixed ...$values ): String
{
	return( Util\Strings::format( $format, ...$values ) );
}

/*
 * @inherit Yume\Fure\Support\Package::import
 *
 */
function import( String $package, Mixed $optional = Null ): Mixed
{
	return( Support\Package::import( $package, $optional ) );
}

/*
 * @inherit Yume\Fure\Locale\Locale::translate
 *
 */
function lang( String $key, ? String $optional = Null, Bool $format = False, Mixed ...$values ): ? String
{
	return( Locale\Locale::translate( $key, $optional, $format, ...$values ) );
}

/*
 * @inherit Yume\Fure\Util\Path\Path::path
 *
 */
function path( String $path, Bool | Path\Paths $prefix_or_remove = False ): String
{
	return( Path\Path::path( $path, $prefix_or_remove ) );
}

/*
 * Print outputs.
 *
 * @params String $format
 *  Please see Yume\Fure\Util\Format::format
 * @params Mixed ...$values
 *  Please see Yume\Fure\Util\Format::format
 *
 * @return Void
 */
function puts( String $format, Mixed ...$values ): Void
{
	echo( Util\Strings::format( $format, ...$values ) );
}

/*
 * Alias of explode and str_split.
 *
 * @params String $string
 * @params Int|String $separator
 *  When the Int passed str_split will called.
 * @params Int $imit
 *  When the Int passed for separator this useless.
 *
 * @return Array
 */
function split( String $string, Int | String $separator = 1, Int $limit = PHP_INT_MAX ): Array
{
	return( is_int( $separator ) ? str_split( $string, $separator ) : explode( $separator, $string, $limit ) );
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

?>