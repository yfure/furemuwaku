<?php

namespace Yume\Fure\Util;

use Stringable;

use Yume\Fure\Error;

/*
 * Strings
 *
 * @package Yume\Fure\Util
 */
class Strings {
	
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
	public static function escape( String $string ): String {
		return( preg_replace_callback( "/\\\(\S)/m", subject: $string, callback: fn( Array $match ) => $match[1] !== "\"" && $match[1] !== "'" ? self::format( "\\\\{1}", $match ) : $match[0] ) );
	}
	
	/*
	 * Return if letter is uppercase.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public static function firstLetterIsUpper( String $string, ? Bool $optional = Null ): Int | Bool {
		return( $optional !== Null ? $optional === self::firstLetterIsUpper( $string ) : ( Bool ) preg_match( "/^[\p{Lu}\x{2160}-\x{216F}]/u", $string ) );
	}
	
	/*
	 * Return if string is valid JSON String.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public static function isJson( String $json, ? Bool $optional = Null ): Bool {
		return( $optional !== Null ? $optional === self::isJson( $json ) : ( Bool ) @json_decode( $json ) );
	}
	
	/*
	 * Return if string is enclosed by double or single quote.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 * @params Mixed &$matches
	 *
	 * @return Bool
	 */
	public static function isQuoted( String $string, Mixed &$matches ): Bool {
		return( preg_match( "/^(?<quote>[\"\'])(?<value>(?:\\\\1|(?!\\\\1).)*)\\1/ms", $string, $matches, PREG_UNMATCHED_AS_NULL ) );
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
	public static function isSerialized( String $string ): Bool {
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
	public static function isSpaces( String $string ): Bool {
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
	public static function parse( Mixed $args ): String {
		return( match( True ) {
			$args === Null => "Null",
			$args === True => "True",
			$args === False => "False",
			
			is_array( $args ) => Json\Json::encode( $args, JSON_INVALID_UTF8_SUBSTITUTE | JSON_PRETTY_PRINT ),
			is_object( $args ) => is_callable( $args ) ? self::parse( Reflect\ReflectFunction::invoke( $args ) ) : ( $args Instanceof Stringable ? $args->__toString() : $args::class ),
			
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
	public static function pop( String $subject, String $separator, Bool $last = False, Mixed &$ref = Null ): String {
		if( count( $split = explode( $separator, $subject ) ) > 0 ) {
			$end = array_pop( $split );
			
			if( $last ) {
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
	public static function shift( String $subject, String $separator, Bool $shift = False, Mixed &$ref = Null ): String {
		if( count( $split = explode( $separator, $subject ) ) > 0 ) {
			$first = array_shift( $split );
			
			if( $shift ) {
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
	public static function toASCII( String $string, String $outputEncoding = "ASCII" ): String {
		if( valueIsEmpty( $string ) ) {
			return "";
		}
		$string = mb_convert_encoding( $string, "UTF-8", mb_detect_encoding( $string ) );
		$ascii = [];
		for( $i = 0; $i < mb_strlen( $string ); $i++ ) {
			$char = mb_substr( $string, $i, 1, "UTF-8" );
			$code = mb_ord( $char, "ASCII" );
			if( $code >= 32 && $code <= 126 ) {
				$ascii[] = $code;
			}
			else {
				
				// If the ASCII code is not within the printable
				// range, convert it to an escape sequence.
				switch( $code ) {

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
		if( $outputEncoding !== "ASCII" ) {
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
	 * @throws Yume\Fure\Error\UnexpectedError
	 *  When the conversion failed.
	 */
	public static function toUnicode( String $string, String $outputEncoding, String $inputEncoding = "UTF-8", Bool $strict = True, Bool $useBom = True, Bool $useBor = False ): String {
		if( in_array( $outputEncoding, iconv_get_encoding( "all" ) ) === False ) {
			trigger_error( "Unsupported output encoding \"$outputEncoding\"", E_USER_WARNING );
			return( False );
		}
		if( $useBom ) {
			$bom = match( $outputEncoding ) {
				"UTF-16BE" => "\xFE\xFF",
				"UTF-16LE" => "\xFF\xFE",
				"UTF-8" => "\xEF\xBB\xBF",
				
				default => ""
			};
		}
		
		// Convert the string to the output encoding.
		$unicode = @iconv( $inputEncoding, $strict ? $outputEncoding . "//IGNORE" : $outputEncoding, $string );
		
		// Check for conversion errors.
		if( $unicode === False ) {
			throw new Error\UnexpectedError( "Conversion from \"$inputEncoding\" to \"$outputEncoding\" failed" );
		}
		
		// Reverse the byte order of the output if requested.
		if( $useBor ) {
			$unicode = strrev( $unicode );
		}
		return( ( $bom ?? "" ) . $unicode );
	}
	
}

?>