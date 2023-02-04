<?php

namespace Yume\Fure\Util;

use Stringable;
use Throwable;

use Yume\Fure\Error;
use Yume\Fure\Support\Reflect;
use Yume\Fure\Util\Env;
use Yume\Fure\Util\Json;
use Yume\Fure\Util\RegExp;

/*
 * Str<String>
 *
 * @package Yume\Fure\Util
 */
abstract class Str
{
	
	/*
	 * Escape string.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return String
	 */
	public static function escape( String $string ): String
	{
		return( RegExp\RegExp::replace( "/\\\(\S)/m", $string, function( Array $match )
		{
			// If the value is not single or double quote.
			if( $match[1] !== "\"" && $match[1] !== "'" )
			{
				return( self::fmt( "\\\\{1}", $match ) );
			}
			return( $match[0] );
		}));
	}
	
	/*
	 * Get last string value by separator.
	 *
	 * @access Public Static
	 *
	 * @params String $subject
	 * @params String $separator
	 *
	 * @return String
	 */
	public static function end( String $subject, String $separator ): ? String
	{
		// Check if value is more than one.
		if( count( $split = explode( $separator, $subject ) ) !== 0 )
		{
			// Return last array ellement.
			return( end( $split ) );
		}
		return( Null );
	}
	
	/*
	 * Check if check if letter is upper or lower.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function firstLetterIsUpper( String $string ): Int | Bool
	{
		return( RegExp\RegExp::match( "/^[\p{Lu}\x{2160}-\x{216F}]/u", $string ) );
	}
	
	/*
	 * String formater.
	 *
	 * To reduce risk it is not recommended to manage
	 * user input using this method and do not use it
	 * to manage long strings that contain lots of
	 * formatting stuff as this method relies heavily
	 * on Regular Expressions which are long enough to
	 * capture every supported syntax.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 * @params Mixed ...$format
	 *
	 * @allows Iteration Replacement.
	 * @syntax {}
	 *
	 * @allows Increment Replacement.
	 * @syntax +
	 * @syntax ++
	 *
	 * @allows Decrement Replacement.
	 * @syntax -
	 * @syntac --
	 *
	 * @allows Array Indexed Replacement.
	 * @syntax [0-9]+
	 *
	 * @allows Array Asocciative Replacement.
	 * @syntax [a-zA-Z0-9_]+
	 *
	 * @allows Environment Variable Replacement.
	 * @syntax Deprecated instead Callback
	 *
	 * @allows Callback Function Replacement.
	 * @syntax [a-zA-Z0-9_](+|++|-|--|{\}|[key]+|[index]+)
	 *
	 * @allows Callback Static-Method Replacement.
	 * @syntax [a-zA-Z0-9_]+::([a-zA-Z0-9_](+|++|-|--|{\}|[key]+|[index]+)
	 *
	 * @allows Method.
	 * @syntax Increment|Decrement:[a-zA-Z0-9_]+
	 * @syntax Array-Indexed|Asocciative:[a-zA-Z0-9_]+
	 * @syntax Environment<Deprecated>:[a-zA-Z0-9_]+
	 * @syntax Function|Static-Method:[a-zA-Z0-9_]+
	 *
	 * @return String
	 */
	public static function fmt( String $string, Mixed ...$format ): String
	{
		// Patterns.
		$cur = "(?<curly>(\\\*)\{[\s\t]*(\\\*)\})";
		$itr = "(?<iteration>(\+|\-){1,2})";
		//$env = "(?<environment>env\\<(?<ename>[a-zA-Z0-9_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)\\>)";
		$idx = "(?<indexed>[0-9]([a-zA-Z0-9_\.\x80-\xff]{0,}[a-zA-Z0-9_\x80-\xff]{1})*)";
		$key = "(?<key>[a-zA-Z0-9_\x80-\xff]([a-zA-Z0-9_\.\x80-\xff]{0,}[a-zA-Z0-9_\x80-\xff]{1})*)";
		$stt = "(?<static>(?<class>[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(?:\\\[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)*)\:\:(?<fname>[a-zA-Z_\x7f-\xff][a-zA-Z_\x7f-\xff]*))";
		$fun = "(\\\*)(?<function>($stt|(?<fname>[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*))[\s\t]*\([\s\t]*(?<fargs>$cur|$itr|$idx|$key)[\s\t]*\)[\s\t]*)";
		$met = "(?<method>[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)";
		
		/*
		 * Function for get value by match.
		 *
		 * @params Array $match
		 * @params Array $format
		 * @params Int $i
		 *
		 * @return Callable
		 */
		$value = function( Array $match, Array $format, Int &$i )
		{
			try
			{
				// Try to call functiob that return value.
				return( call_user_func( match( True )
				{
					// Get values form format parameter by key name.
					isset( $match['key'] ) => fn() => Arr::ify( $match['key'], $format ),
					
					// Get values form format parameter by indexed number.
					isset( $match['indexed'] ) => fn() => $format[( $match['indexed'] = ( Int ) $match['indexed'] )] ?? throw new Error\IndexError( $match['indexed'] ),
					
					// Get values form format parameter by iteration.
					default => function() use( $match, $format, &$i )
					{
						// Check if matched by iteration symbols.
						if( isset( $match['iteration'] ) )
						{
							// Get current index by symbol.
							$index = $match['iteration'][0] === "\x2b" ? $i++ : $i--;
							
							// Check if index by iteration is exists.
							if( isset( $format[$index] ) )
							{
								return( $format[$index] );
							}
							throw new Error\IndexError( $index );
						}
						
						// Check if matched is only by iteration.
						if( $match['matched'] === "\x7b\x7d" || isset( $match['curly'] ) )
						{
							// Check if index by iteration is exists.
							if( isset( $format[$i] ) )
							{
								return( $format[$i++] );
							}
							throw new Error\IndexError( $i++ );
						}
						throw new Error\SyntaxError( sprintf( "Unsupported syntax %s in string formater", $match['matched'] ) );
					}
				}));
			}
			catch( Throwable $e )
			{
				return( sprintf( "\\{[%s]:[ %s ][ line %d ][ file %s ]\\}", $e::class, $e->getMessage(), $e->getLine(), path( $e->getFile(), True ) ) );
			}
		};
		
		// Replace format syntaxs.
		$replace = preg_replace_callback( pattern: "/(?:(?<matched>(?<!\\\)\{([\s\t]*)(?<syntax>.*?)([\s\t]*)(?<!\\\)\}))/", subject: $string, callback: function( Array $match ) use( $cur, $itr, /** $env, */ $idx, $key, $fun, $met, &$string, &$format, $value )
		{
			// Statically variable.
			static $i = 0;
			
			// Extract variables.
			$matched = $match['matched'];
			$syntax = $match['syntax'];
			
			try
			{
				// Check if syntax is valid.
				if( $match = RegExp\RegExp::match( "/^(?:(?<matched>($cur|$itr|$fun|$idx|$key)(\:($met))*)(?<skip>\#[^\n]*)*)$/iJ", $syntax ) )
				{
					// Get format values.
					$values = $value( $match, $format, $i );
					
					// Check if syntax function is exists.
					if( isset( $match['function'] ) )
					{
						// Check if values is not Array type.
						if( is_array( $values ) === False ) $values = [ $values ];
						
						// Check if function is static method.
						if( isset( $match['static'] ) )
						{
							// Get method return values.
							$values = call_user_func_array( $match['static'], $values );
						}
						else {
							
							// Get function return values.
							$values = call_user_func_array( $match['fname'], $values );
						}
					}
					
					// Parse values to string.
					$values = self::parse( $values );
					
					// Check if method is available.
					if( valueIsNotEmpty( $match['method'] ) )
					{
						// Matching supported method.
						$values = match( $match['method'] )
						{
							// Supported methods.
							"b64decode" => base64_decode( $values ),
							"b64encode" => base64_encode( $values ),
							"lcfirst" => lcfirst( $values ),
							"lower" => strtolower( $values ),
							"ucfirst" => ucfirst( $values ),
							"upper" => strtoupper( $values ),
							
							// When unsupported method passed.
							default => sprintf( "Unsuported method %s", $match['method'] )
						};
					}
					return( self::parse( $values ) );
				}
				else {
					return( self::parse( $value( [ "matched" => $matched ], $format, $i ) ) );
				}
			}
			catch( Throwable $e )
			{
				return( sprintf( "\\{[%s]:[ %s ][ line %d ][ file %s ]\\}", $e::class, $e->getMessage(), $e->getLine(), path( $e->getFile(), True ) ) );
			}
			$i++;
		});
		
		// Find all and replace backslash and return.
		return( preg_replace_callback( pattern: "/(?:(?<backslash>\\\{1,})(?<curly>\{|\}))/ms", subject: $replace, callback: fn( Array $match ) => sprintf( "%s%s", $match['backslash'] === "\x5c" ? "" : str_repeat( "\x5c", strlen( $match['backslash'] ) -1 ), $match['curly'] ) ) );
	}
	
	/*
	 * To match a string that is a valid binary number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isBin( String $string ): Bool
	{
		return( RegExp\RegExp::test( "/^(?:([01]+))$/", $string ) );
	}
	
	/*
	 * To match a string that is a valid decimal number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isDec( String $string ): Bool
	{
		return( RegExp\RegExp::test( "/^(?:([0-9]+))$/", $string ) );
	}
	
	/*
	 * To match a string that is a valid hexadecimal.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isHexa( String $string ): Bool
	{
		return( RegExp\RegExp::test( "/^(?:([0-9a-fA-F]+))$/", $string ) );
	}
	
	/*
	 * To match a string that is a valid octal number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isOctal( String $string ): Bool
	{
		return( RegExp\RegExp::test( "/^(?:(0[1-7][0-7]*))$/", $string ) );
	}
	
	/*
	 * Checks if string is enclosed by double or single quote.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isQuoted( String $string ): Bool
	{
		return( RegExp\RegExp::test( "/^(?:(\"[^\"]*|\'[^\']*))$/" ) );
	}
	
	/*
	 * Parses any data type to string.
	 *
	 * @access Public Static
	 *
	 * @params Mixed $args
	 *
	 * @return String
	 */
	public static function parse( Mixed $args ): String
	{
		// If `args` value is null type.
		if( $args === Null ) return( "Null" );
		
		// If `args` value is boolean type.
		if( $args === True ) return( "True" );
		if( $args === False ) return( "False" );
		
		// If `args` value is array type.
		if( is_array( $args ) ) return( Json\Json::encode( $args, JSON_INVALID_UTF8_SUBSTITUTE | JSON_PRETTY_PRINT ) );
		
		// If `args` value is object type.
		if( is_object( $args ) )
		{
			// If `args` value is callable type.
			if( is_callable( $args ) )
			{
				return( self::parse( Reflect\ReflectFunction::invoke( $args ) ) );
			}
			else {
				
				// Check if object is stringable.
				if( $args Instanceof Stringable )
				{
					// Parse object into string.
					return( $args->__toString() );
				}
				else {
					return( $args::class );
				}
			}
		}
		return( ( String ) $args );
	}
	
	/*
	 * Remove last string with separator.
	 *
	 * @access Public Static
	 *
	 * @params String $subject
	 * @params String $separator
	 *
	 * @return String
	 */
	public static function pop( String $subject, String $separator ): String
	{
		// Check if value is more than one.
		if( count( $split = explode( $separator, $subject ) ) !== 0 )
		{
			// Remove last array ellement.
			$last = array_pop( $split );
		}
		
		// Join array elements with a string.
		return( $subject = implode( $separator, $split ) );
	}
	
	/*
	 * Generate random pseudo bytes by length.
	 *
	 * @access Public Static
	 *
	 * @params Int $length
	 *
	 * @return String
	 */
	public static function random( Int $length = 16 ): String
	{
		return( random_bytes( $length ) );
	}
	
	/*
	 * Generate random string by alphabhet given.
	 * This function has deprecated on Yume on v3.0.6
	 *
	 * @source http://stackoverflow.com/a/13733588/
	 *
	 * @access Public Static
	 *
	 * @params Int $length
	 * @params String $alphabet
	 *
	 * @return String
	 *
	 * @throws Yume\Fure\Error\DeprecatedError
	 */
	public static function randomAlpha(): String
	{
		throw new Error\DeprecatedError( f( "Method {} has been deprecated, instead use {}", __METHOD__, "Yume\Fure\Util\Random\Random::strings" ) );
	}
	
}

?>