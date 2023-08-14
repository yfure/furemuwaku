<?php

namespace Yume\Fure\Util\Letter;

/*
 * HungarianCase
 *
 * @package Yume\Fure\Util\Letter
 */
trait HungarianCase
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
	public static function toHungarianCase( String $string, String $type = "str" ): String
	{
		$parts = preg_split( "/(?=[A-Z])|[_-]/", $string );
		$prefix = "";
		switch( $type )
		{
			case "str":
				$prefix = "str";
				break;
			case "int":
				$prefix = "n";
				break;
			case "bool":
				$prefix = "b";
				break;
			case "arr":
				$prefix = "arr";
				break;
			case "obj":
				$prefix = "obj";
				break;
		}
		return $prefix . implode( "", array_map( "ucfirst", $parts ) );
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
	public static function fromCamelCasetoHungarianCase( String $string, String $type = "str" ): String
	{
		$parts = preg_split( "/(?=[A-Z])/", $string );
		$prefix = "";
		switch( $type )
		{
			case "str":
				$prefix = "str";
				break;
			case "int":
				$prefix = "n";
				break;
			case "bool":
				$prefix = "b";
				break;
			case "arr":
				$prefix = "arr";
				break;
			case "obj":
				$prefix = "obj";
				break;
		}
		return $prefix . implode( "", array_map( "ucfirst", $parts ) );
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
	public static function fromPascalCasetoHungarianCase( String $string, String $type = "str" ): String
	{
		return self::fromCamelCasetoHungarianCase( $string, $type );
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
	public static function fromSnakeCasetoHungarianCase( String $string, String $type = "str" ): String
	{
		$parts = explode( "_", $string );
		$prefix = "";
		switch( $type )
		{
			case "str":
				$prefix = "str";
				break;
			case "int":
				$prefix = "n";
				break;
			case "bool":
				$prefix = "b";
				break;
			case "arr":
				$prefix = "arr";
				break;
			case "obj":
				$prefix = "obj";
				break;
		}
		return $prefix . implode( "", array_map( "ucfirst", $parts ) );
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
	public static function fromKebabCasetoHungarianCase( String $string, String $type = "str" ): String
	{
		$parts = explode( "-", $string );
		$prefix = "";
		switch( $type )
		{
			case "str":
				$prefix = "str";
				break;
			case "int":
				$prefix = "n";
				break;
			case "bool":
				$prefix = "b";
				break;
			case "arr":
				$prefix = "arr";
				break;
			case "obj":
				$prefix = "obj";
				break;
		}
		return $prefix . implode( "", array_map( "ucfirst", $parts ) );
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
	public static function fromUpperCamelCasetoHungarianCase( String $string, String $type = "str" ): String
	{
		$parts = preg_split( "/(?=[A-Z])/", lcfirst( $string ) );
		$prefix = "";
		switch( $type )
		{
			case "str":
				$prefix = "str";
				break;
			case "int":
				$prefix = "n";
				break;
			case "bool":
				$prefix = "b";
				break;
			case "arr":
				$prefix = "arr";
				break;
			case "obj":
				$prefix = "obj";
				break;
		}
		return $prefix . implode( "", array_map( "ucfirst", $parts ) );
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
	public static function fromUpperCasetoHungarianCase( String $string ): String
	{
		$words = preg_split( "/(?=[A-Z])/", $string );
		$result = "";
		foreach( $words as $word )
		{
			if( $result !== "" )
			{
				$result .= "_";
			}
			$result .= strtolower( $word );
		}
		return $result;
	}
	
}

?>