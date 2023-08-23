<?php

namespace Yume\Fure\Util;

use Throwable;

use Yume\Fure\Error;

/*
 * Format
 *
 * @package Yume\Fure\Util
 */
trait Format
{
	
	/*
	 * Pattern for match iteration syntax.
	 *
	 * @access Private Static
	 *
	 * @values String
	 */
	private static String $iterate = "(?<iterate>(\+|\-){1,2})";
	
	/*
	 * Pattern for match curly syntax.
	 *
	 * @access Private Static
	 *
	 * @values String
	 */
	private static String $curly = "(?<curly>(?:\\\*)\{[\s\t]*(?:\\\*)\})";
	
	/*
	 * Pattern for match array syntax.
	 *
	 * @access Private Static
	 *
	 * @values String
	 */
	private static String $array = "(?<array>(?:[a-zA-Z0-9_\x80-\xff](?:[a-zA-Z0-9_\.\x80-\xff]{0,}[a-zA-Z0-9_\x80-\xff]{1})*)*(?:\\[[^\\[\\]]+\\]|[a-zA-Z0-9_\x80-\xff](?:[a-zA-Z0-9_\.\x80-\xff]{0,}[a-zA-Z0-9_\x80-\xff]{1})*)+(?:\\.[a-zA-Z0-9_\x80-\xff](?:[a-zA-Z0-9_\.\x80-\xff]{0,}[a-zA-Z0-9_\x80-\xff]{1})*|\\[[^\\[\\]]+\\])*)";
	
	/*
	 * Pattern for match static method syntax.
	 *
	 * @access Private Static
	 *
	 * @values String
	 */
	private static String $method = "(?<method>(?<class>[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(?:\\\[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)*)\:\:(?<method_name>[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*))";
	
	/*
	 * Pattern for match callback syntax.
	 *
	 * @access Private Static
	 *
	 * @values String
	 */
	private static String $callback = "(?<callback>[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)";
	
	/*
	 * Pattern for match function/ method argument syntax.
	 *
	 * @access Private Static
	 *
	 * @values String
	 */
	private static String $argument = "(?<argument>(?:[\s\t]*)\((?:[\s\t]*)(?<values>%1\$s|%2\$s|%3\$s)(?:[\s\t]*)\))";
	
	/*
	 * Pattern for match function syntax.
	 *
	 * @access Private Static
	 *
	 * @values String
	 */
	private static String $function = "(?<function>(?<function_name>[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*))";
	
	/*
	 * Combined patterns syntax.
	 *
	 * @access Private Static
	 *
	 * @values String
	 */
	private static ? String $combine = Null;
	
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
	 * @syntax {}
	 * @syntax {+|++|-|--}
	 * @syntax {[a-zA-Z0-9]+}
	 * @syntax {Function|Method(?)}
	 *
	 * @params String $format
	 *  The raw string to be formatted.
	 * @params Mixed ...$values
	 *  Value to override format.
	 *
	 * @return String
	 */
	public static function format( String $format, Mixed ...$values ): String
	{
		// Check if pattern syntax has not combine.
		if( self::$combine === Null )
		{
			// Create combine pattern syntax.
			self::$combine = sprintf( "/^(?:(?<matched>(%1\$s|%2\$s|%3\$s|(?<func>(?:\\\*)(?:%5\$s|%6\$s)%7\$s))(\:(%4\$s))*)(?<skip>\#[^\n]*)*)$/iJ", ...[
				self::$curly,
				self::$iterate,
				self::$array,
				self::$callback,
				self::$function,
				self::$method,
				sprintf( self::$argument, ...[
					self::$curly,
					self::$iterate,
					self::$array
				])
			]);
		}
		
		// Replacing formats.
		$replace = preg_replace_callback( "/(?:(?<matched>(?<!\\\)\{(?:[\s\t]*)(?<syntax>.*?)(?:[\s\t]*)(?<!\\\)\}))/", subject: $format, callback: function( Array $match ) use( &$values )
		{
			// Statically variable.
			static $i = 0;
			
			// Extract variables.
			$matched = $match['matched'];
			$syntax = $match['syntax'];
			
			try
			{
				// Check if syntax is valid.
				if( preg_match( self::$combine, $syntax, $match, PREG_UNMATCHED_AS_NULL ) )
				{
					// Get format values.
					$value = self::formatValue( $match, $values, $i );
					
					// Check if syntax function is exists.
					if( isset( $match['func'] ) )
					{
						// Check if values is not Array type.
						if( is_array( $value ) === False ) $value = [ $value ];
						
						// Get function/ method return values.
						$value = call_user_func_array( $match['method'] ?? $match['function'], $value );
					}
					
					// Check if method is available.
					if( isset( $match['callback'] ) )
					{
						// Avoid argument error when the value is not String type.
						$value = Strings::parse( $value );
						
						// Matching supported method.
						return( match( @strtolower( $match['callback'] ) )
						{
							// Supported methods.
							"b64decode",
							"base64_decode" => @base64_decode( $value ),
							"b64encode",
							"base64_encode" => @base64_encode( $value ),
							"bin2hex" => @bin2hex( $value ),
							"lcfirst" => @lcfirst( $value ),
							"lower" => @strtolower( $value ),
							"ucfirst" => @ucfirst( $value ),
							"upper" => @strtoupper( $value ),
							"htmlspecialchars" => @htmlspecialchars( $value ),
							
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
							"haval256x2c5" => @hash( preg_replace_callback( "/x([a-fA-F0-9]{2})/", fn( Array $m ) => @hex2bin( $m[1] ), $match['callback'] ), $value ),
							
							// When unsupported callback passed.
							default => sprintf( "#[value(Unsuported callback %s)]", $match['callback'] )
						});
					}
					return( Strings::parse( $value ) );
				}
				else {
					return( Strings::parse( self::formatValue( [ "matched" => $matched ], $values, $i ) ) );
				}
			}
			catch( Throwable $e )
			{
				return( sprintf( "#[%s(%s on line %d in file %s)]", $e::class, $e->getMessage(), $e->getLine(), /**path(*/ $e->getFile()/*, True )*/ ) );
			}
			$i++;
		});
		return( preg_replace_callback( pattern: "/(?:(?<backslash>\\\{1,})(?<curly>\{|\}))/ms", subject: $replace, callback: fn( Array $match ) => sprintf( "%s%s", $match['backslash'] === "\x5c" ? "" : str_repeat( "\x5c", strlen( $match['backslash'] ) -1 ), $match['curly'] ) ) );
	}
	
	/*
	 * Function for get value by match.
	 *
	 * @params Array $match
	 * @params Array $values
	 * @params Int $i
	 *
	 * @return Mixed
	 */
	private static function formatValue( Array $match, Array $values, Int &$i ): Mixed
	{
		// Get values form format parameter by key name.
		if( isset( $match['array'] ) ) return( Arrays::ify( $match['array'], $values ) );
		
		// Check if matched by iteration symbols.
		if( isset( $match['iterate'] ) )
		{
			// Get current index by symbol.
			$index = $match['iterate'][0] === "\x2b" ? $i++ : $i--;
			
			// Check if index by iteration is exists.
			if( isset( $values[$index] ) )
			{
				return( $values[$index] );
			}
			throw new Error\IndexError( $index );
		}
		
		// Check if matched is only by iteration.
		if( $match['matched'] === "\x7b\x7d" || isset( $match['curly'] ) )
		{
			// Check if index by iteration is exists.
			if( isset( $values[$i] ) )
			{
				return( $values[$i++] );
			}
			throw new Error\IndexError( $i++ );
		}
		throw new Error\SyntaxError( sprintf( "Unsupported syntax %s in string formater", $match['matched'] ) );
	}
	
}

?>