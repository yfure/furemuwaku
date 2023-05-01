<?php

namespace Yume\Fure\Util\Letter;

/*
 * KebabCase
 *
 * @package Yume\Fure\Util\Letter
 */
trait KebabCase
{
	
	use \Yume\Fure\Util\Letter\SnakeCase;
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return String
	 */
	public static function toKebabCase( String $string ): String
	{
		return( strtolower( preg_replace( "/(.)(?=[A-Z])/u", "$1-", preg_replace( "/\s+/", "-", str_replace( "_", "-", $string ) ) ) ) );
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
	public static function fromCamelCaseToKebabCase( String $string ): String
	{
		return( strtolower( preg_replace( "/(.)(?=[A-Z])/u", "$1-", $string ) ) );
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
	public static function fromPascalCaseToKebabCase( String $string ): String
	{
		return( str_replace( "_", "-", self::fromPascalCaseToSnakeCase( $string ) ) );
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
	public static function fromSnakeCaseToKebabCase( String $string ): String
	{
		return( str_replace( "_", "-", $string ) );
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
	public static function fromUpperCamelCaseToKebabCase( String $string ): String
	{
		return( self::fromCamelCaseToKebabCase( lcfirst( $string ) ) );
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
	public static function fromUpperCaseToKebabCase( String $string ): String
	{
		return( self::toKebabCase( strtolower( $string ) ) );
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
	public static function fromHungarianCaseToKebabCase( String $string ): String
	{
		return( implode( "-", array_map( fn( String $part ) => strtolower( substr( $part, 0, 1 ) ) . substr( $part, 1 ), explode( "_", $string ) ) ) );
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
	public static function fromVerbObjectCaseToKebabCase( String $string ): String
	{
		$parts = explode( "_", $string );
		$newParts = [];
		foreach( $parts as $part )
		{
			if( $part == "get" || $part == "set" )
			{
				$newParts[] = strtolower( $part );
			} else
			{
				$newParts[] = $part;
			}
		}
		return( implode( "-", $newParts ) );
	}
	
}

?>