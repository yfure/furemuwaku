<?php

namespace Yume\Fure\Util\Letter;

/*
 * UpperCase
 *
 * @package Yume\Fure\Util\Letter
 */
trait UpperCase
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
	public static function toUpperCase( String $string ): String
	{
		return( strtoupper( $string ) );
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
	public static function fromCamelCasetoUpperCase( String $string ): String
	{
		return( self::toUpperCase( trim( preg_replace( "/[A-Z]/", "_$0", $string ), "_" ) ) );
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
	public static function fromPascalCasetoUpperCase( String $string ): String
	{
		return( self::fromCamelCasetoUpperCase( $string ) );
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
	public static function fromSnakeCasetoUpperCase( String $string ): String
	{
		return( self::toUpperCase( $string ) );
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
	public static function fromUpperCamelCasetoUpperCase( String $string ): String
	{
		return( self::toUpperCase( trim( preg_replace( "/[A-Z]/", "_$0", $string ), "_" ) ) );
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
	public static function fromVerbObjectCasetoUpperCase( String $string ): String
	{
		return( self::fromCamelCasetoUpperCase( self::fromVerbObjectCasetoCamelCase( $string ) ) );
	}
	
}

?>