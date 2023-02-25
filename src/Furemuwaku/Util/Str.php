<?php

namespace Yume\Fure\Util;

use Stringable;
use Throwable;

use Yume\Fure\Error;
use Yume\Fure\Support\Reflect;
use Yume\Fure\Util\Env;
use Yume\Fure\Util\Json;

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
		
		// Replace format syntaxs.
		$replace = preg_replace_callback( pattern: "/(?:(?<matched>(?<!\\\)\{([\s\t]*)(?<syntax>.*?)([\s\t]*)(?<!\\\)\}))/", subject: $string, callback: function( Array $match ) use( $cur, $itr, /** $env, */ $idx, $key, $fun, $met, &$string, &$format )
		{
			// Statically variable.
			static $i = 0;
			
			// Extract variables.
			$matched = $match['matched'];
			$syntax = $match['syntax'];
			
			try
			{
				// Check if syntax is valid.
				if( preg_match( "/^(?:(?<matched>($cur|$itr|$fun|$idx|$key)(\:($met))*)(?<skip>\#[^\n]*)*)$/iJ", $syntax, $match, PREG_UNMATCHED_AS_NULL ) )
				{
					// Get format values.
					$values = self::fmtValue( $match, $format, $i );
					
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
					if( isset( $match['method'] ) )
					{
						// Matching supported method.
						$values = match( strtolower( $match['method'] ) )
						{
							// Supported methods.
							"b64decode",
							"base64_decode" => base64_decode( $values ),
							"b64encode",
							"base64_encode" => base64_encode( $values ),
							"bin2hex" => bin2hex( $values ),
							"lcfirst" => lcfirst( $values ),
							"lower" => strtolower( $values ),
							"ucfirst" => ucfirst( $values ),
							"upper" => strtoupper( $values ),
							"htmlspecialchars" => htmlspecialchars( $value ),
							
							// Supported hash methods.
							"md2",
							"md4",
							"md5",
							"sha1",
							"sha224",
							"sha256",
							"sha384",
							"sha512x2f224",
							"sha512x2f256",
							"sha512",
							"sha3x2d224",
							"sha3x2d256",
							"sha3x2d384",
							"sha3x2d512",
							"ripemd128",
							"ripemd160",
							"ripemd256",
							"ripemd320",
							"whirlpool",
							"tiger128x2c3",
							"tiger160x2c3",
							"tiger192x2c3",
							"tiger128x2c4",
							"tiger160x2c4",
							"tiger192x2c4",
							"snefru",
							"snefru256",
							"gost",
							"gostx2dcrypto",
							"adler32",
							"crc32",
							"crc32b",
							"crc32c",
							"fnv132",
							"fnv1a32",
							"fnv164",
							"fnv1a64",
							"joaat",
							"murmur3a",
							"murmur3c",
							"murmur3f",
							"xxh32",
							"xxh64",
							"xxh3",
							"xxh128",
							"haval128x2c3",
							"haval160x2c3",
							"haval192x2c3",
							"haval224x2c3",
							"haval256x2c3",
							"haval128x2c4",
							"haval160x2c4",
							"haval192x2c4",
							"haval224x2c4",
							"haval256x2c4",
							"haval128x2c5",
							"haval160x2c5",
							"haval192x2c5",
							"haval224x2c5",
							"haval256x2c5" => hash( preg_replace_callback( "/x([a-fA-F0-9]{2})/", fn( Array $m ) => hex2bin( $m[1] ), $match['method'] ), $values ),
							
							// When unsupported method passed.
							default => sprintf( "#[value(Unsuported method %s)]", $match['method'] )
						};
					}
					return( self::parse( $values ) );
				}
				else {
					return( self::parse( self::fmtValue( [ "matched" => $matched ], $format, $i ) ) );
				}
			}
			catch( Throwable $e )
			{
				
				return( sprintf( "#[%s(%s)]", $e::class, $e->getMessage(), $e->getLine(), path( $e->getFile(), True ) ) );
			}
			$i++;
		});
		return( preg_replace_callback( pattern: "/(?:(?<backslash>\\\{1,})(?<curly>\{|\}))/ms", subject: $replace, callback: fn( Array $match ) => sprintf( "%s%s", $match['backslash'] === "\x5c" ? "" : str_repeat( "\x5c", strlen( $match['backslash'] ) -1 ), $match['curly'] ) ) );
	}
	
	/*
	 * Function for get value by match.
	 *
	 * @params Array $match
	 * @params Array $format
	 * @params Int $i
	 *
	 * @return Callable
	 */
	private static function fmtValue( Array $match, Array $format, Int &$i ): Array | Callable | String
	{
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
	
	/*
	 * Return if String is valid Integer Binary number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isBinary( String $string ): Bool
	{
		return( preg_match( "/^(?:(0[bB][01]+(_[01]+)*)|([01]+))$/", $string ) );
	}
	
	/*
	 * Return if String is valid Integer Decimal number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isDecimal( String $string ): Bool
	{
		return( preg_match( "/^(?:([1-9][0-9]*(_[0-9]+)*|0)|([0-9]+))$/", $string ) );
	}
	
	/*
	 * Return is String is valid Numeric Double number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isDouble( String $string ): Bool
	{
		return( preg_match( "/^(?:([0-9]*)[\.]([0-9]+)|([0-9]+)[\.]([0-9]*))$/", $string ) );
	}
	
	/*
	 * Return is String is valid Numeric Double Exponent number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isExponentDouble( String $string ): Bool
	{
		return( preg_match( "/^(?:((([0-9]+)|(([0-9]*)[\.]([0-9]+)|([0-9]+)[\.]([0-9]*)))[eE][+-]?([0-9]+)))$/", $string ) );
	}
	
	/*
	 * Return is String is valid Numeric Floating number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isFloat( String $string ): Bool
	{
		return( preg_match( "/^(?:\s*[+-]?((([0-9]*)[\.]([0-9]+)|([0-9]+)[\.]([0-9]*))|((([0-9]+)|(([0-9]*)[\.]([0-9]+)|([0-9]+)[\.]([0-9]*)))[eE][+-]?([0-9]+)))\s*)$/", $string ) );
	}
	
	/*
	 * Return is String is valid Integer Hexadecimal number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isHexa( String $string ): Bool
	{
		return( preg_match( "/^(?:(0[xX][0-9a-fA-F]+(_[0-9a-fA-F]+)*)|([0-9a-fA-F]+))$/", $string ) );
	}
	
	/*
	 * Return is String is valid Numeric Int number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isInt( String $string ): Bool
	{
		return( preg_match( "/^(?:\s*[+-]?([0-9]+)\s*)$/", $string ) );
	}
	
	/*
	 * Return is String is valid Integer Type.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isInteger( String $string ): Bool
	{
		return(
			self::isBinary( $string ) ||
			self::isDecimal( $string ) ||
			self::isHexa( $string ) ||
			self::isOctal( $string )
		);
	}
	
	/*
	 * Return is String is valid Numeric Long number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isLong( String $string ): Bool
	{
		return( preg_match( "/^(?:([0-9]+))$/", $string ) );
	}
	
	/*
	 * Return is String is valid Numeric number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isNumber( String $string ): Bool
	{
		return( preg_match( "/^(?:((\s*[+-]?([0-9]+)\s*)|(\s*[+-]?((([0-9]*)[\.]([0-9]+)|([0-9]+)[\.]([0-9]*))|((([0-9]+)|(([0-9]*)[\.]([0-9]+)|([0-9]+)[\.]([0-9]*)))[eE][+-]?([0-9]+)))\s*)))$/", $string ) );
	}
	
	/*
	 * Return is String is valid Numeric type.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isNumeric( String $string ): Bool
	{
		return(
			self::isDouble( $string ) ||
			self::isExponentDouble( $string ) ||
			self::isFloat( $string ) ||
			self::isInt( $string ) ||
			self::isLong( $string ) ||
			self::isNumber( $string )
		);
	}
	
	/*
	 * Return is String is valid Integer Octal number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isOctal( String $string ): Bool
	{
		return( preg_match( "/^(?:(0[oO]?[0-7]+(_[0-7]+)*)|(0[1-7][0-7]*))$/", $string ) );
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
	 * Return if string is UUID.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isUUID( String $string ): Bool
	{
		return( preg_match( "/^[\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12}$/iD", $string ) );
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
	 *
	 * @return String
	 */
	public static function pop( String $subject, String $separator, Bool $last = False ): String
	{
		if( count( $split = explode( $separator, $subject ) ) !== 0 )
		{
			if( $last )
			{
				return( end( $split ) );
			}
			array_pop( $split );
		}
		return( implode( $separator, $split ) );
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
	
	/*
	 * Remove first string with separator.
	 *
	 * @access Public Static
	 *
	 * @params String $subject
	 * @params String $separator
	 * @params Bool $shift
	 *
	 * @return String
	 */
	public static function shift( String $subject, String $separator, Bool $shift = False ): String
	{
		if( count( $split = explode( $separator, $subject ) ) !== 0 )
		{
			if( $shift )
			{
				return( $split[0] );
			}
			array_shift( $split );
		}
		return( implode( $separator, $split ) );
	}
	
	/*
	 * Converts a string to its ASCII codes.
	 *
	 * @access Public Static
	 *
	 * @params String $string The input string to convert.
	 * @params String $outputEncoding The desired output encoding. Defaults to ASCII.
	 *
	 * @return string The ASCII codes of the input string, separated by spaces.
	 */
	public static function toAscii( String $string, String $outputEncoding = "ASCII" ): String
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
	 * @params String $string The input string to convert.
	 * @params String $outputEncoding The desired output Unicode encoding.
	 * @params String $inputEncoding The input encoding of the input string. Defaults to UTF-8.
	 * @params Bool $strict Whether to use strict error handling. Defaults to true.
	 * @params Bool $useBom Whether to include a BOM in the output. Defaults to true.
	 * @params Bool $useBor Whether to reverse the byte order of the output. Defaults to false.
	 *
	 * @return False|String The converted String, or False if the conversion failed.
	 */
	public static function toUnicode( String $string, String $outputEncoding, ? String $inputEncoding = "UTF-8", Bool $strict = True, Bool $useBom = True, Bool $useBor = False ): False | String
	{
		// Check if the output encoding is supported by iconv
		if( in_array( $outputEncoding, iconv_get_encoding( "all" ) ) === False )
		{
			trigger_error( "Unsupported output encoding \"$outputEncoding\"", E_USER_WARNING );
			return( False );
		}
	
		// Determine the BOM for the output encoding.
		if( $useBom ) $bom = match( $outputEncoding )
		{
			"UTF-16BE" => "\xFE\xFF",
			"UTF-16LE" => "\xFF\xFE",
			"UTF-8" => "\xEF\xBB\xBF",
			
			default => ""
		};
	
		// Convert the string to the output encoding.
		$unicode = @iconv( $inputEncoding, $strict ? $outputEncoding . "//IGNORE" : $outputEncoding, $string );
		
		// Check for conversion errors.
		if( $unicode === False )
		{
			trigger_error( "Conversion from \"$inputEncoding\" to \"$outputEncoding\" failed", E_USER_WARNING );
			return( False );
		}
	
		// Reverse the byte order of the output if requested.
		if( $useBor ) $unicode = strrev( $unicode );
		
		// Add the BOM to the output if requested.
		return( sprintf( "%s%s", $bom ?? "", $unicode ) );
	}
	
	public static function toUTF8(): String
	{}
	
	public static function toUTF16(): String
	{}
	
}

?>