<?php

namespace Yume\Fure\Util;

/*
 * Boolean
 *
 * @package Yume\Fure\Util
 */
final class Boolean {
	
	/*
	 * Parse any value to Boolean type.
	 *
	 * @access Public Static
	 *
	 * @params Mixed $value
	 *
	 * @return Bool
	 */
	public static function parse( Mixed $value ): Bool {
		if( is_bool( $value ) ) return( $value );
		if( is_string( $value ) ) {
			if( preg_match( "/^(?<type>True|False)$/i", trim( $value ), $match ) ) {
				return( Strings::toUpperCamelCase( $match['type'] ) === "True" );
			}
		}
		return( ( Bool ) $value );
	}
	
}

?>