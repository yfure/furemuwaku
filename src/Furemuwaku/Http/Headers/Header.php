<?php

namespace Yume\Fure\Http\Header;

use Yume\Fure\Util;
use Yume\Fure\Util\RegExp;

/*
 * Header
 * 
 * @package Yume\Fure\Http\Header
 */
final class Header {

	/*
	 * Assert the header keyset or name.
	 * 
	 * @access Public Static
	 * 
	 * @params String $keyset
	 * 
	 * @return Void
	 */
	public static function assertKeyset( String $keyset ): Void {
		if( RegExp\RegExp::test( "/^[a-zA-Z0-9\'`#$%&*+.^_|~!-]+$/D", $keyset ) === False ) {
			throw new HeaderError( $keyset, HeaderError::KEYSET_ERROR );
		}
	}

	/*
	 * Assert the header value.
	 * 
	 * @access Public Static
	 * 
	 * @params String $value
	 * 
	 * @return Void
	 */
	public static function assertValue( String $value ): Void {
		if( RegExp\RegExp::test( "/^[\x20\x09\x21-\x7E\x80-\xFF]*$/D", $value ) === False ) {
			throw new HeaderError( [], HeaderError::FOLDING_ERROR );
		}
	}

	/*
	 * Normalize header value.
	 * 
	 * @access Public Static
	 * 
	 * @params String $keyset
	 *  Use for give error information when the value of header is invalid
	 * @params Mixed $value
	 *  The header value
	 * 
	 * @return Array<String>
	 * @throws Yume\Fure\Http\Header\HeaderError
	 *  When the value of header is invalid or contain line folding
	 */
	public static function normalizeValue( String $keyset, Mixed $value ): Array {
		if( is_array( $value ) ) {
			if( count( $value ) === 0 ) {
				throw new HeaderError( $keyset, HeaderError::VALUE_ERROR );
			}
			return Util\Arrays::map( $value, function( Int $i, Int|String $index, Mixed $value ) use( $keyset ): String {
				if( is_scalar( $value ) === False && $value !== Null ) {
					throw new HeaderError( $keyset, HeaderError::VALUE_ERROR );
				}
				$value = ( String ) $value;
				$value = trim( $value, "\x20\x09" );
				try {
					self::assertValue( $value );
				}
				catch( HeaderError $e ) {
					throw new HeaderError( f( "{}:{}", $index, $keyset ), HeaderError::VALUE_ERROR, $e );
				}
				return $value;
			});
		}
		return self::normalizeValue( $keyset, [$value] );
	}
}

?>