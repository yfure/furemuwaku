<?php

namespace Yume\Fure\Util;

use Stringable;

use phpseclib3\Crypt;

use Yume\Fure\Error;
use Yume\Fure\Support;

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
		return( Support\RegExp\RegExp::replace( "/\\\(\S)/m", $string, function( Array $match )
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
		return( Support\RegExp\RegExp::match( "/^[\p{Lu}\x{2160}-\x{216F}]/u", $string ) );
	}
	
	/*
	 * String formater.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 * @params Mixed $format
	 *
	 * @return String
	 */
	public static function fmt( String $string, Mixed ...$format ): String
	{
		return( Support\RegExp\RegExp::replace( "/(?:(?<format>(?<except>\\\{0,})(\{[\s\t]*((?<key>[a-zA-Z0-9_\x80-\xff]([a-zA-Z0-9_\.\x80-\xff]{0,}[a-zA-Z0-9_\x80-\xff]{1})*)|(?<index>[\d]+))*[\s\t]*\})))/i", $string, function( Array $match ) use( &$string, &$format )
		{
			// Statically variable.
			static $i = 0;
			
			// If backslash character exists.
			if( isset( $match['except'] ) && $match['except'] !== "" )
			{
				// If the number of backslashes is more than one.
				if( strlen( $match['except'] ) === 1 )
				{
					return( $match['format'] );
				}
				$match['except'] = str_repeat( "\\", strlen( $match['except'] ) -1 );
			}
			
			// If array key is matched.
			if( isset( $match['key'] ) )
			{
				return( $match['except'] . self::parse( Arr::ify( $match['key'], $format ) ) );
			}
			
			// If array index is matched.
			if( isset( $match['index'] ) )
			{
				// Check if index is exists.
				if( isset( $format[$match['index']] ) )
				{
					return( $match['except'] . self::parse( $format[$match['index']] ) );
				}
				throw new Error\IndexError( $match['index'], Error\IndexError::RANGE_ERROR );
			}
			
			else {
				
				// Check if index iteration is exists.
				if( isset( $format[$i] ) )
				{
					return( $match['except'] . self::parse( $format[$i++] ) );
				}
				throw new Error\IndexError( $i++, Error\IndexError::RANGE_ERROR );
			}
			$i++;
		}));
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
		return( Support\RegExp\RegExp::test( "/^(?:(\"[^\"]*|\'[^\']*))$/" ) );
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
		if( is_array( $args ) ) return( JSON::encode( $args, JSON_INVALID_UTF8_SUBSTITUTE | JSON_PRETTY_PRINT ) );
		
		// If `args` value is object type.
		if( is_object( $args ) )
		{
			// If `args` value is callable type.
			if( is_callable( $args ) )
			{
				return( self::parse( Support\Reflect\ReflectFunction::invoke( $data ) ) );
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
		// Split string with separator.
		$split = explode( $separator, $subject );
		
		// Remove last array ellement.
		array_pop( $split );
		
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
		return( Crypt\Random::string( $length ) );
	}
	
	/*
	 * Generate random string by alphabhet given.
	 *
	 * @source http://stackoverflow.com/a/13733588/
	 *
	 * @access Public Static
	 *
	 * @params Int $length
	 * @params String $alphabet
	 *
	 * @return String
	 */
	public static function randomAlpha(): String
	{
		// Token result stack.
		$token = "";
		
		// Check if alphabet is null type.
		if( $alphabet === Null )
		{
			// Generate random alphabet.
			$alphabet = self::fmt( "{}{}{}", [
				implode( range( "a", "z" ) ),
				implode( range( "A", "Z" ) ),
				implode( range( 0, 9 ) )
			]);
		}
		
		// Get alphabet length.
		$alphabetLength = strlen( $alphabet );
		
		for( $i = 0; $i < $length; $i++ )
		{
			// Get alphabet based on randomable number.
			$token .= $alphabet[Number::random( 0, $alphabetLength )];
		}
		
		return( $token );
	}
	
}

?>