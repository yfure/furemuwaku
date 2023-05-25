<?php

namespace Yume\Fure\Util\Letter;

/*
 * CamelCase
 *
 * @package Yume\Fure\Util\Letter
 */
trait CamelCase
{
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return String
	 */
	public static function toCamelCase( String $string ): String
	{
		// Remove any leading or trailing white space
		$string = trim( $string );
		
		// Replace underscores, hyphens, spaces and capitalize words
		$string = preg_replace( "/[_\-\s]+/", "\x20", $string );
		$string = str_replace( "\x20", "", ucwords( strtolower( $string ) ) );
		$string = lcfirst( $string );
		
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
	public static function fromPascalCaseToCamelCase( String $string ): String
	{
		// Convert the first character to lowercase
		$string[0] = strtolower( $string[0] );
		
		// Return replaced underscores, hyphens, spaces and capitalize words.
		return( preg_replace( "/[_\-\s]+/", "", $string ) );
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
	public static function fromSnakeCaseToCamelCase( String $string ): String
	{
		// Replace underscores and capitalize words
		$string = str_replace( "_", "\x20", $string );
		$string = str_replace( "\x20", "", ucwords( strtolower( $string ) ) );
		$string = lcfirst( $string );
		
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
	public static function fromKebabCaseToCamelCase( String $string ): String
	{
		// Replace hyphens and capitalize words
		$string = str_replace( "-", "\x20", $string );
		$string = str_replace( "\x20", "", ucwords( strtolower( $string ) ) );
		$string = lcfirst( $string );
		
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
	public static function fromUpperCamelCaseToCamelCase( String $string ): String
	{
		// Convert the first character to lowercase
		$string[0] = strtolower( $string[0] );
		
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
	public static function fromUpperCaseToCamelCase( String $string ): String
	{
		return( strtolower( $string ) );
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
	public static function fromHungarianCaseToCamelCase( String $string ): String
	{
		// Remove the type prefix and convert the first character to lowercase
		$string = substr( $string, strpos( $string, "_" )+ 1 );
		$string[0] = strtolower( $string[0] );
		
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
	public static function fromVerbObjectCaseToCamelCase( String $string ): String
	{
		// Convert the first word to lowercase and capitalize the rest
		$words = array_map( fn( String $word )=> ucfirst( $word ), explode( "-", $string ) );
		$words[0] = lcfirst( $words[0] );
		
		return( implode( "", $words ) );
	}
	
}

?>