<?php

namespace Yume\Fure\Util\Letter;

use Yume\Fure\Util;

/*
 * SnakeCase
 *
 * @package Yume\Fure\Util\Letter
 */
trait SnakeCase {
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return String
	 */
	public static function toSnakeCase( String $string ): String {
		return( strtolower( preg_replace( "/(.)(?=[A-Z])/u", "$1_", preg_replace( "/\s+/", "_", str_replace( "-", "_", $string ) ) ) ) );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return String
	 */
	public static function fromCamelCaseToSnakeCase( String $string ): String {
		return( strtolower( preg_replace( "/(.)(?=[A-Z])/u", "$1_", $string ) ) );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return String
	 */
	public static function fromPascalCaseToSnakeCase( String $string ): String {
		return( str_replace( "-", "_", Util\Strings::fromPascalCaseToKebabCase( $string ) ) );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return String
	 */
	public static function fromKebabCaseToSnakeCase( String $string ): String {
		return( str_replace( "-", "_", $string ) );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return String
	 */
	public static function fromUpperCamelCaseToSnakeCase( String $string ): String {
		return( Util\Strings::fromCamelCaseToSnakeCase( lcfirst( $string ) ) );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return String
	 */
	public static function fromUpperCaseToSnakeCase( String $string ): String {
		return( self::toSnakeCase( strtolower( $string ) ) );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return String
	 */
	public static function fromHungarianCaseToSnakeCase( String $string ): String {
		$parts = explode( "_", $string );
		$newParts = [];
		foreach( $parts as $part ) {
			$newParts[] = strtolower( substr( $part, 0, 1 ) ). substr( $part, 1 );
		}
		return implode( "_", $newParts );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return String
	 */
	public static function fromVerbObjectCaseToSnakeCase( String $string ): String {
		$parts = explode( "_", $string );
		$newParts = [];
		foreach( $parts as $part ) {
			if( $part == "get" || $part == "set" ) {
				$newParts[] = strtolower( $part );
			}
			else {
				$newParts[] = $part;
			}
		}
		return implode( "_", $newParts );
	}
	
}

?>