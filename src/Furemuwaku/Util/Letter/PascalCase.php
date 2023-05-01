<?php

namespace Yume\Fure\Util\Letter;

/*
 * PascalCase
 *
 * @package Yume\Fure\Util\Letter
 */
trait PascalCase
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
	public static function toPascalCase( String $string ): String
	{
		return( str_replace( "\x20", "", ucwords( str_replace( [ "-", "_", "\x20" ], "\x20", $string ) ) ) );
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
	public static function fromCamelCaseToPascalCase( String $string ): String
	{
		return( ucfirst( lcfirst( $string ) ) );
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
	public static function fromSnakeCaseToPascalCase( String $string ): String
	{
		return( str_replace( "\x20", "", ucwords( str_replace( "_", "\x20", $string ) ) ) );
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
	public static function fromKebabCaseToPascalCase( String $string ): String
	{
		return( str_replace( "\x20", "", ucwords( str_replace( "-", "\x20", $string ) ) ) );
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
	public static function fromUpperCamelCaseToPascalCase( String $string ): String
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
	public static function fromUpperCaseToPascalCase( String $string ): String
	{
		return( self::toPascalCase( strtolower( $string ) ) );
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
	public static function fromHungarianCaseToPascalCase( String $string ): String
	{
		return( implode( "", array_map( fn( String $part ) => substr( $part, 0, 1 ) . ucfirst( substr( $part, 1 ) ), explode( "_", $string ) ) ) );
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
	public static function fromVerbObjectCaseToPascalCase( String $string ): String
	{
		$parts = explode( "_", $string );
		$newParts = [];
		foreach( $parts as $part )
		{
			if( $part == "get" || $part == "set" )
			{
				$newParts[] = ucfirst( $part );
			}
			else
			{
				$newParts[] = ucfirst( $part );
			}
		}
		return implode( "", $newParts );
	}
	
}

?>