<?php

namespace Yume\Fure\Util;

use Throwable;

use Yume\Fure\Error;

/*
 * Format
 * 
 * @package Yume\Fure\Util
 */
trait Format {

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
	 * @syntax default
	 *  format( "{}", ...$values )
	 * @syntax iteration
	 *  format( "{+}", ...$values )
	 *  format( "{-}", ...$values )
	 * @syntax index|key
	 *  format( "{k}", ...$values )
	 *  format( "{k.v}", ...$values )
	 *  format( "{k.v[i]}", ...$values )
	 *  format( "{k.v[i].k}", ...$values )
	 *  format( "{k.v[i].k[i]}", ...$values )
	 * @syntax function|method
	 *  format( "{test(?)}", ...$values )
	 *  format( "{Test\Test::test(?)}", ...$values )
	 * @syntax callback
	 *  format( "{+:lower}", ...$values )
	 *  format( "{k:lower}", ...$values )
	 *  format( "{f(?):lower}", ...$values )
	 *
	 * @params String $format
	 *  The raw string to be formatted.
	 * @params Mixed ...$values
	 *  Value to override format.
	 *
	 * @return String
	 */
	public static function format( String $format, Mixed ...$values ): String {
		return( self::formatNormalize( preg_replace_callback(
			pattern: "/(?<matched>(?<!\\\)\{(?<syntax>.*?)(?<!\\\)\})/ms",
			subject: $format,
			callback: function( Array $match ) use( $values ): String {
				static $i = 0;
				return( self::formatHandler( $match, $values, $i ) );
			})
		));
	}

	/*
	 * Format handler.
	 * 
	 * @access Private Static
	 * 
	 * @params Array $match
	 * @params Array<Int|String,Mixed> &$values
	 * @params Int &$i
	 *
	 * @return String
	 */
	private static function formatHandler( Array $match, Array &$values, Int &$i ): String {
		$array = "(?<array>(?:[a-zA-Z0-9_\x80-\xff](?:[a-zA-Z0-9_\.\x80-\xff]{0,}[a-zA-Z0-9_\x80-\xff]{1})*)*(?:\\[[^\\[\\]]+\\]|[a-zA-Z0-9_\x80-\xff](?:[a-zA-Z0-9_\.\x80-\xff]{0,}[a-zA-Z0-9_\x80-\xff]{1})*)+(?:\\.[a-zA-Z0-9_\x80-\xff](?:[a-zA-Z0-9_\.\x80-\xff]{0,}[a-zA-Z0-9_\x80-\xff]{1})*|\\[[^\\[\\]]+\\])*)";
		$curly = "(?<curly>\{\})";
		$iterator = "(?<iterator>(\\\x2b{1,2}|\\\x2d{1,2}))";
		$argument = "(?<argument>\((?:{$array}|{$curly}|{$iterator})*\))";
		$callback = "(?<callback>[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(?:\\\[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)*)";
		$function = "(?<function>[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(?:\\\[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)*)";
		$statical = "(?<statical>(?<class>[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(?:\\\[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)*)\:{2}(?<method>[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*))";
		$closure = "(?<closure>\\\{0,1}(?:{$function}|{$statical}){$argument})";
		$pattern = "/^(?:{$array}|{$curly}|{$iterator}|{$closure})(?:\:\\\{0,1}{$callback})*$/Jms";
		$matched = self::formatNormalize( trim( $match['matched'] ) );
		$syntax = self::formatNormalize( trim( $match['syntax'] ) );
		$value = "";
		try {
			if( preg_match( $pattern, str_replace( [ "\n", "\x20" ], "", $syntax ), $match, PREG_UNMATCHED_AS_NULL ) ) {
				$value = self::formatValue( $match, $values, $i );
				if( isset( $match['closure'] ) ) {
					$closure = $match['statical'] ?? $match['function'];
					$params = is_array( $value ) ? $value : [$value];
					$value = call_user_func_array( $closure, $params );
				}

				// Avoid argument error when the value is not String type.
				$value = Strings::parse( $value );
				if( isset( $match['callback'] ) ) {
					$value = match( strtolower( $match['callback'] ) ) {
						
						// Supported shorthands callback.
						"b64decode" => @base64_decode( $value ),
						"b64encode" => @base64_encode( $value ),
						"bin2hex" => @bin2hex( $value ),
						"lcfirst" => @lcfirst( $value ),
						"lower" => @strtolower( $value ),
						"ucfirst" => @ucfirst( $value ),
						"upper" => @strtoupper( $value ),
						"htmlspecialchars" => @htmlspecialchars( $value ),

						// Handle unsupported callback.
						default => match( True ) {
							
							// When the callback doesn't supported,
							// We will check if callback is callable.
							is_callable( $match['callback'] ) => Strings::parse(
								call_user_func( $match['callback'], $value )
							),

							// When the callback is invalid.
							default => "#[Unsuported callback `{$match['callback']}`]"
						}
					};
				}
			}
			else {
				$value = Strings::parse(
					self::formatValue( i: $i, values: $values, match: [
						"matched" => $matched
					])
				);
			}
		}
		catch( Throwable $e ) {
			$value = sprintf( "\x23\x5b\x25\x73\x28\x20\x25\x73\x20\x29\x5d", $e::class, $e->getMessage() );
		}
		return( $value );
	}

	/*
	 * Normalize string with syntax format.
	 * 
	 * @access Public Static
	 * 
	 * @params String $string
	 * 
	 * @return String
	 */
	public static function formatNormalize( String $string ): String {
		return( preg_replace_callback( pattern: "/(?:(?<backslash>\\\{1,})(?<curly>\{|\}))/ms", subject: $string, callback: fn( Array $match ) => sprintf( "%s%s", $match['backslash'] === "\x5c" ? "" : str_repeat( "\x5c", strlen( $match['backslash'] ) -1 ), $match['curly'] ) ) );
	}

	/*
	 * Normalize format value.
	 * 
	 * @access Private Static
	 * 
	 * @params Array<String,String> $match
	 * @params Array<Int|String,Mixed> $values
	 * @params Int &$i
	 * 
	 * @return Mixed
	 * 
	 * @throws Yume\Fure\Error\IndexError
	 *  When the value of index is not available.
	 * @throws Yume\Fure\Error\SyntaxError
	 *  When the syntax is invalid or doesn't supported.
	 */
	private static function formatValue( Array $match, Array $values, Int &$i ): Mixed {
		if( isset( $match['array'] ) ) {
			return( Arrays::ify( $match['array'], $values ) );
		}
		if( isset( $match['iterator'] ) ) {
			$index = $match['iterator'][0] === "\x2b" ? $i++ : $i--;
			if( isset( $values[$index] ) ) {
				return( $values[$index] );
			}
			throw new Error\IndexError( $index );
		}
		if( $match['matched'] === "\x7b\x7d" || isset( $match['curly'] ) ) {
			if( isset( $values[$i] ) ) {
				return( $values[$i++] );
			}
			var_dump([
				$match,
				$values
			]);
			throw new Error\IndexError( $i++ );
		}
		throw new Error\SyntaxError( sprintf( "Unsupported syntax %s in string formater", $match['matched'] ) );
	}

}

?>
