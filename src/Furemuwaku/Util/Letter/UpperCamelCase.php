<?php

namespace Yume\Fure\Util\Letter;

/*
 * UpperCamelCase
 *
 * @package Yume\Fure\Util\Letter
 */
trait UpperCamelCase
{
	
	use \Yume\Fure\Util\Letter\CamelCase;
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return String
	 */
	public static function toUpperCamelCase( String $string ): String
	{
		return( str_replace( "\x20", "", ucwords( str_replace( [ "-", "_" ], "\x20", $string ) ) ) );
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
	public static function fromCamelCasetoUpperCamelCase( String $string ): String
	{
		return( ucfirst( $string ) );
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
	public static function fromPascalCasetoUpperCamelCase( String $string ): String
	{
		return( $string );
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
	public static function fromSnakeCasetoUpperCamelCase( String $string ): String
	{
		return( self::toUpperCamelCase( self::fromSnakeCasetoCamelCase( $string ) ) );
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
	public static function fromKebabCasetoUpperCamelCase( String $string ): String
	{
		return( self::toUpperCamelCase( self::fromKebabCasetoCamelCase( $string ) ) );
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
	public static function fromUpperCasetoUpperCamelCase( String $string ): String
	{
		return( strtolower( $string ) );
	}
	
}

?>