<?php

namespace Yume\Fure\Util\Letter;

/*
 * SpaceCase
 *
 * @package Yume\Fure\Util\Letter
 */
trait SpaceCase {
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return String
	 */
	public static function toSpaceCase( String $string ): String {
		return( ucwords( preg_replace( "/\s+/u", "\x20", trim( preg_replace( "/[^\p{L}\p{N}]+/u", "\x20", $string ) ) ) ) );
	}
	
	/*
	 * @inherit fromVerbObjectCaseToSpaceCase
	 *
	 */
	public static function fromCamelCaseToSpaceCase( String $string ): String {
		return( self::fromVerbObjectCaseToSpaceCase( $string ) );
	}
	
	/*
	 * @inherit fromVerbObjectCaseToSpaceCase
	 *
	 */
	public static function fromPascalCaseToSpaceCase( String $string ): String {
		return( self::fromVerbObjectCaseToSpaceCase( $string ) );
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
	public static function fromSnakeCaseToSpaceCase( String $string ): String {
		return( ucwords( str_replace( "_", "\x20", $string ) ) );
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
	public static function fromKebabCaseToSpaceCase( String $string ): String {
		return( ucwords( str_replace( "-", "\x20", $string ) ) );
	}
	
	/*
	 * @inherit fromVerbObjectCaseToSpaceCase
	 *
	 */
	public static function fromUpperCamelCaseToSpaceCase( String $string ): String {
		return( self::fromVerbObjectCaseToSpaceCase( $string ) );
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
	public static function fromUpperCaseToSpaceCase( String $string ): String {
		return( ucwords( strtolower( $string ) ) );
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
	public static function fromHungarianCaseToSpaceCase( String $string ): String {
		return( ucwords( preg_replace( "/([A-Z])/", " $1", $string ) ) );
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
	public static function fromVerbObjectCaseToSpaceCase( String $string ): String {
		return( ucwords( preg_replace( "/([a-z])([A-Z])/", "$1 $2", $string ) ) );
	}
	
}

?>