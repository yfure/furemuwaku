<?php

namespace Yume\Fure\Util;

use Yume\Fure\Error;

/*
 * Strings
 *
 * @package Yume\Fure\Util
 */
class Strings
{
	
	/*
	 * Letter traits utility.
	 *
	 * @include CamelCase
	 * @include HungarianCase
	 * @include KebabCase
	 * @include PascalCase
	 * @include SnakeCase
	 * @include UpperCamelCase
	 * @include UpperCase
	 * @include VerbObjectCase
	 */
	use \Yume\Fure\Util\Letter\CamelCase;
	use \Yume\Fure\Util\Letter\HungarianCase;
	use \Yume\Fure\Util\Letter\KebabCase;
	use \Yume\Fure\Util\Letter\PascalCase;
	use \Yume\Fure\Util\Letter\SnakeCase;
	use \Yume\Fure\Util\Letter\SpaceCase;
	use \Yume\Fure\Util\Letter\UpperCamelCase;
	use \Yume\Fure\Util\Letter\UpperCase;
	use \Yume\Fure\Util\Letter\VerbObjectCase;
	
	/*
	 * String formater & numeric utility.
	 *
	 * @include Format
	 * @include Numeric
	 */
	use \Yume\Fure\Util\Format;
	use \Yume\Fure\Util\Numeric;
	
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
		return( preg_replace( "/\\\(\S)/m", subject: $string, callback: fn( Array $match ) => $match[1] !== "\"" && $match[1] !== "'" ? self::fmt( "\\\\{1}", $match ) : $match[0] ) );
	}
	
	/*
	 * Return if check if letter is uppercase.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function firstLetterIsUpper( String $string ): Int | Bool
	{
		return( preg_match( "/^[\p{Lu}\x{2160}-\x{216F}]/u", $string ) );
	}
	
	/*
	 * Return if string is enclosed by double or single quote.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isQuoted( String $string ): Bool
	{
		return( preg_match( "/^(?:(\"[^\"]*|\'[^\']*))$/", $string ) );
	}
	
	/*
	 * Return if strings is serialized.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isSerialized( String $string ): Bool
	{
		return( @unserialize( $string ) !== False || $string === "b:0;" );
	}
	
	/*
	 * Return if string is spaces only.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isSpaces( String $string ): Bool
	{
		return( preg_match( "/^(?:\s+)$/", $string ) );
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
		return( match( True )
		{
			// If `args` value is Null type.
			$args === Null => "Null",
			
			// If `args` value is Boolean type.
			$args === True => "True",
			$args === False => "False",
			
			// If `args` value is Array type.
			is_array( $args ) => Json\Json::encode( $args, JSON_INVALID_UTF8_SUBSTITUTE | JSON_PRETTY_PRINT ),
			
			// If `args` value is Object type.
			is_object( $args ) => is_callable( $args ) ? self::parse( Reflect\ReflectFunction::invoke( $args ) ) : ( $args Instanceof Stringable ? $args->__toString() : $args::class ),
			
			// Auto convert.
			default => ( String ) $args
		});
	}
	
	/*
	 * Remove last string with separator.
	 *
	 * @access Public Static
	 *
	 * @params String $subject
	 * @params String $separator
	 * @params Bool $last
	 * @params Mixed &$ref
	 *
	 * @return String
	 */
	public static function pop( String $subject, String $separator, Bool $last = False, Mixed &$ref = Null ): String
	{
		if( count( $split = explode( $separator, $subject ) ) > 0 )
		{
			$end = array_pop( $split );
			
			if( $last )
			{
				$ref = [
					$string = implode( $separator, $split ),
					$end
				];
				return( $end );
			}
		}
		$ref = [
			$string = implode( $separator, $split ),
			$end ?? Null
		];
		return( $string );
	}
	
	/*
	 * Remove first string with separator.
	 *
	 * @access Public Static
	 *
	 * @params String $subject
	 * @params String $separator
	 * @params Bool $shift
	 * @params Mixed &$ref
	 *
	 * @return String
	 */
	public static function shift( String $subject, String $separator, Bool $shift = False, Mixed &$ref = Null ): String
	{
		if( count( $split = explode( $separator, $subject ) ) > 0 )
		{
			$first = array_shift( $split );
			
			if( $shift )
			{
				$ref = [
					$string = implode( $separator, $split ),
					$first
				];
				return( $first );
			}
		}
		$ref = [
			$string = implode( $separator, $split ),
			$first ?? Null
		];
		return( $string );
	}
	
	/*
	 * Converts a string to its ASCII codes.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *  The input string to convert.
	 * @params String $outputEncoding
	 *  The desired output encoding.
	 *
	 * @return String
	 *  The ASCII codes of the input string, separated by spaces.
	 */
	public static function toASCII( String $string, String $outputEncoding = "ASCII" ): String
	{
		// If the input string is empty or null, return an empty string.
		if( valueIsEmpty( $string ) ) return "";
		
		// Convert the input string to UTF-8 encoding.
		$string = mb_convert_encoding( $string, "UTF-8", mb_detect_encoding( $string ) );
	
		// Convert each character in the input string to its ASCII code.
		$ascii = [];
		
		for( $i = 0; $i < mb_strlen( $string ); $i++ )
		{
			// Get the current character.
			$char = mb_substr( $string, $i, 1, "UTF-8" );
	
			// Get the ASCII code for the current character.
			$code = mb_ord( $char, "ASCII" );
	
			// If the ASCII code is within the printable range, add it to the output array.
			if( $code >= 32 && $code <= 126 )
			{
				$ascii[] = $code;
			}
			else {
				
				// If the ASCII code is not within the printable
				// range, convert it to an escape sequence.
				switch( $code )
				{
					// Tab character
					case 9:
						$ascii[] = 92; // Backslash
						$ascii[] = 116; // 't'
						break;
						
					// Newline character
					case 10:
						$ascii[] = 92; // Backslash
						$ascii[] = 110; // 'n'
						break;
						
					// Carriage return character
					case 13:
						$ascii[] = 92; // Backslash
						$ascii[] = 114; // 'r'
						break;
						
					// All other characters
					default: $ascii[] = $code; break;
				}
			}
		}
	
		// Convert the output array to a string.
		if( $outputEncoding !== "ASCII" )
		{
			// Return converted the output array to the desired encoding.
			return( iconv( "ASCII", $outputEncoding . "//IGNORE", implode( "", array_map( "chr", $ascii ) ) ) );
		}
		
		// Return converted the output array to a space-separated string.
		return( implode( "\x20", $ascii ) );
	}
	
	/*
	 * Convert a string to any Unicode encoding.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *  The input string to convert.
	 * @params String $outputEncoding
	 *  The desired output Unicode encoding.
	 * @params String $inputEncoding
	 *  The input encoding of the input string.
	 * @params Bool $strict
	 *  Whether to use strict error handling.
	 * @params Bool $useBom
	 *  Whether to include a BOM in the output.
	 * @params Bool $useBor
	 *  Whether to reverse the byte order of the output.
	 *
	 * @return String
	 *  The converted String
	 *
	 * @throws Yume\Fure\Error\TypeError
	 *  When the conversion failed.
	 */
	public static function toUnicode( String $string, String $outputEncoding, String $inputEncoding = "UTF-8", Bool $strict = True, Bool $useBom = True, Bool $useBor = False ): String
	{
		// Check if the output encoding is supported by iconv
		if( in_array( $outputEncoding, iconv_get_encoding( "all" ) ) === False )
		{
			trigger_error( "Unsupported output encoding \"$outputEncoding\"", E_USER_WARNING );
			return( False );
		}
		
		// Determine the BOM for the output encoding.
		if( $useBom )
		{
			$bom = match( $outputEncoding )
			{
				"UTF-16BE" => "\xFE\xFF",
				"UTF-16LE" => "\xFF\xFE",
				"UTF-8" => "\xEF\xBB\xBF",
				
				default => ""
			};
		}
		
		// Convert the string to the output encoding.
		$unicode = @iconv( $inputEncoding, $strict ? $outputEncoding . "//IGNORE" : $outputEncoding, $string );
		
		// Check for conversion errors.
		if( $unicode === False ) throw new Error\TypeError( "Conversion from \"$inputEncoding\" to \"$outputEncoding\" failed" );
		
		// Reverse the byte order of the output if requested.
		if( $useBor ) $unicode = strrev( $unicode );
		
		// Add the BOM to the output if requested.
		return( ( $bom ?? "" ) . $unicode );
	}
	
}

?>