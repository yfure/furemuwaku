<?php

namespace Yume\Fure\CLI;

use Closure;

use Yume\Fure\CLI\Command;
use Yume\Fure\Util;
use Yume\Fure\Util\RegExp;

/*
 * CLITrait
 *
 * @package Yume\Fure\CLI
 */
trait CLITrait
{
	
	/*
	 * Colorize string.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 * @params String $base
	 *
	 * @return String
	 */
	public static function colorize( String $string, String $base = "\x1b[00m" ): String
	{
		$patterns = [
			
			//"/\b(?<!\\\(x1b|033)|\\;|\\[)(\d+)\b/" => "\x1b[1;31m$2",
			
			/*
			 * Pattern to capture symbols.
			 *
			 * @include \
			 * @include :
			 * @include *
			 * @include -
			 * @include +
			 * @include /
			 * @include &
			 * @include %
			 * @include =
			 * @include ;
			 * @include ,
			 * @include .
			 * @include ?
			 * @include !
			 * @include <
			 * @include >
			 * @include ^
			 */
			"/((\\\(?<!x1b)|(\\\(?<!033)))|\:|\*|\-|\+|\/|\&|\%|\=|((?<!\d)\;(?<!\d))|\,|\.|\\?|\\!|\<|\>)/" => "\x1b[1;38;5;111m$1",
			
			/*
			 * Pattern to capture the symbol brackets.
			 *
			 * @include {}
			 * @include []
			 * @include ()
			 */
			"/(\{|\}|(((?<!\\\x1b)\[)|\])|\(|\))/" => "\x1b[1;38;5;214m$1",
			
			/*
			 * Patterns for capturing strings flanked by singles or double quotes.
			 *
			 * @include (')
			 * @include (")
			 */
			"/(?<!\\\)([\"\'])(?:\\\\1|(?!\\\\1).)*\\1/ms" => fn( Array $m ) => sprintf( "\x1b[1;33m%s%s", ...[
				
				preg_replace_callback(  
					
					/*
					 * Patterns for capturing characters that begin with backslash.
					 *
					 * @include \r
					 * @include \t
					 * @include \n
					 * @include \s
					 */
					pattern: "/(\\r|\\t|\\n|\\\s)/",
					
					// Remove all colors in the quote.
					subject: preg_replace( "/(\\\x1b|\\\033)\[([^m]+m)/", "", $m[0] ),
					
					/*
					 * Call back to handle the caught character.
					 *
					 * @params Array $m
					 *
					 * @return String
					 */
					callback: fn( Array $m ) => match( $m[0] )
					{
						"\'" => "\x1b[1;38;5;214m\'\x1b[1;33m",
						"\"" => "\x1b[1;38;5;214m\\\"\x1b[1;33m",
						"\r" => "\x1b[1;38;5;214m\\r\x1b[1;33m",
						"\t" => "\x1b[1;38;5;214m\\t\x1b[1;33m",
						"\n" => "\x1b[1;38;5;214m\\n\x1b[1;33m",
						"\s" => "\x1b[1;38;5;214m\\s\x1b[1;33m",
					}
				),
				$base
			])
		];
		foreach( $patterns As $pattern => $replace )
		{
			if( $replace Instanceof Closure )
			{
				$string = RegExp\RegExp::replace( $pattern, $string, $replace );
			}
			else {
				$string = RegExp\RegExp::replace( $pattern, $string, sprintf( "%s%s", $replace, $base ) );
			}
		}
		return( $string );
	}
	
	public static function fmt( String $format, String $base = "\x1b[00m", Mixed ...$values ): String
	{
		return( self::colorize( Util\Str::fmt( $format, ...$values ), $base ) );
	}
	
	public static function puts( String $format, String $base = "\x1b[00m", Mixed ...$values ): Void
	{
		echo( self::fmt( $format, $base, ...$values ) );
	}
	
}

?>