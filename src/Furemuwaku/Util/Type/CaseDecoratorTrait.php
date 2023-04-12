<?php

namespace Yume\Fure\Util\Type;

/*
 * CaseDecoratorTrait
 *
 * @package Yume\Fure\Util\Type
 */
trait CaseDecoratorTrait
{
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function toCamelCase( $string )
	{
		// Remove any leading or trailing white space
		$string = trim( $string );
		
		// Replace underscores, hyphens, spaces and capitalize words
		$string = preg_replace( "/[_\-\s]+/", "\x20", $string );
		$string = str_replace( "\x20", "", ucwords( strtolower( $string ) ) );
		$string = lcfirst( $string );
		
		return $string;
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromPascalCaseToCamelCase( $string )
	{
		// Convert the first character to lowercase
		$string[0] = strtolower( $string[0] );
		
		// Replace underscores, hyphens, spaces and capitalize words
		$string = preg_replace( "/[_\-\s]+/", "", $string );
		
		return $string;
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromSnakeCaseToCamelCase( $string )
	{
		// Replace underscores and capitalize words
		$string = str_replace( "_", "\x20", $string );
		$string = str_replace( "\x20", "", ucwords( strtolower( $string ) ) );
		$string = lcfirst( $string );
		
		return $string;
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromKebabCaseToCamelCase( $string )
	{
		// Replace hyphens and capitalize words
		$string = str_replace( "-", "\x20", $string );
		$string = str_replace( "\x20", "", ucwords( strtolower( $string ) ) );
		$string = lcfirst( $string );
		
		return $string;
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromUpperCamelCaseToCamelCase( $string )
	{
		// Convert the first character to lowercase
		$string[0] = strtolower( $string[0] );
		
		return $string;
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromUpperCaseToCamelCase( $string )
	{
		// Convert the string to lowercase
		$string = strtolower( $string );
		
		return $string;
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromHungarianCaseToCamelCase( $string )
	{
		// Remove the type prefix and convert the first character to lowercase
		$string = substr( $string, strpos( $string, "_" )+ 1 );
		$string[0] = strtolower( $string[0] );
		
		return $string;
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromVerbObjectCaseToCamelCase( $string )
	{
		// Split the string into an array using a hyphen as a separator
		$words = explode( "-", $string );
		
		// Convert the first word to lowercase and capitalize the rest
		$words = array_map( fn( String $word )=> ucfirst( $word ), $words );
		$words[0] = lcfirst( $words[0] );
		
		return implode( "", $words );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function toPascalCase( $str )
	{
		$str = str_replace( ["-", "_", "\x20"], "\x20", $str );
		$str = ucwords( $str );
		$str = str_replace( "\x20", "", $str );
		return $str;
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromCamelCaseToPascalCase( $str )
	{
		$str = lcfirst( $str );
		return ucfirst( $str );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromSnakeCaseToPascalCase( $str )
	{
		$str = str_replace( "_", "\x20", $str );
		$str = ucwords( $str );
		return str_replace( "\x20", "", $str );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromKebabCaseToPascalCase( $str )
	{
		$str = str_replace( "-", "\x20", $str );
		$str = ucwords( $str );
		return str_replace( "\x20", "", $str );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromUpperCamelCaseToPascalCase( $str )
	{
		return ucfirst( $str );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromUpperCaseToPascalCase( $str )
	{
		$str = strtolower( $str );
		return toPascalCase( $str );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromHungarianCaseToPascalCase( $str )
	{
		$parts = explode( "_", $str );
		$newParts = [];
		foreach( $parts as $part )
	{
			$newParts[] = substr( $part, 0, 1 ). ucfirst( substr( $part, 1 ) );
		}
		return implode( "", $newParts );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromVerbObjectCaseToPascalCase( $str )
	{
		$parts = explode( "_", $str );
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
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function toSnakeCase( $str )
	{
		$str = str_replace( "-", "_", $str );
		$str = preg_replace( "/\s+/", "_", $str );
		$str = preg_replace( "/(.)(?=[A-Z])/u", "$1_", $str );
		return strtolower( $str );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromCamelCaseToSnakeCase( $str )
	{
		$str = preg_replace( "/(.)(?=[A-Z])/u", "$1_", $str );
		return strtolower( $str );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromPascalCaseToSnakeCase( $str )
	{
		$str = fromPascalCaseToKebabCase( $str );
		return str_replace( "-", "_", $str );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromKebabCaseToSnakeCase( $str )
	{
		return str_replace( "-", "_", $str );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromUpperCamelCaseToSnakeCase( $str )
	{
		return fromCamelCaseToSnakeCase( lcfirst( $str ) );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromUpperCaseToSnakeCase( $str )
	{
		$str = strtolower( $str );
		return toSnakeCase( $str );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromHungarianCaseToSnakeCase( $str )
	{
		$parts = explode( "_", $str );
		$newParts = [];
		foreach( $parts as $part )
		{
			$newParts[] = strtolower( substr( $part, 0, 1 ) ). substr( $part, 1 );
		}
		return implode( "_", $newParts );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromVerbObjectCaseToSnakeCase( $str )
	{
		$parts = explode( "_", $str );
		$newParts = [];
		foreach( $parts as $part )
		{
			if( $part == "get" || $part == "set" )
			{
				$newParts[] = strtolower( $part );
			}
			else
			{
				$newParts[] = $part;
			}
		}
		return implode( "_", $newParts );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function toKebabCase( $str )
	{
		$str = str_replace( "_", "-", $str );
		$str = preg_replace( "/\s+/", "-", $str );
		$str = preg_replace( "/(.)(?=[A-Z])/u", "$1-", $str );
		return strtolower( $str );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromCamelCaseToKebabCase( $str )
	{
		$str = preg_replace( "/(.)(?=[A-Z])/u", "$1-", $str );
		return strtolower( $str );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromPascalCaseToKebabCase( $str )
	{
		$str = fromPascalCaseToSnakeCase( $str );
		return str_replace( "_", "-", $str );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromSnakeCaseToKebabCase( $str )
	{
		return str_replace( "_", "-", $str );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromUpperCamelCaseToKebabCase( $str )
	{
		return fromCamelCaseToKebabCase( lcfirst( $str ) );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromUpperCaseToKebabCase( $str )
	{
		$str = strtolower( $str );
		return toKebabCase( $str );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromHungarianCaseToKebabCase( $str )
	{
		$parts = explode( "_", $str );
		$newParts = [];
		foreach( $parts as $part )
	{
			$newParts[] = strtolower( substr( $part, 0, 1 ) ). substr( $part, 1 );
		}
		return implode( "-", $newParts );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromVerbObjectCaseToKebabCase( $str )
	{
		$parts = explode( "_", $str );
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
		return implode( "-", $newParts );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function toUpperCamelCase( $string )
	{
		$string = str_replace( ["-", "_"], "\x20", $string );
		$string = ucwords( $string );
		$string = str_replace( "\x20", "", $string );
		return $string;
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromCamelCasetoUpperCamelCase( $string )
	{
		return ucfirst( $string );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromPascalCasetoUpperCamelCase( $string )
	{
		return $string;
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromSnakeCasetoUpperCamelCase( $string )
	{
		return toUpperCamelCase( fromSnakeCasetoCamelCase( $string ) );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromKebabCasetoUpperCamelCase( $string )
	{
		return toUpperCamelCase( fromKebabCasetoCamelCase( $string ) );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromUpperCasetoUpperCamelCase( $string )
	{
		return strtolower( $string );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function toUpperCase( $string )
	{
		return strtoupper( $string );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromCamelCasetoUpperCase( $string )
	{
		$string = preg_replace( "/[A-Z]/", "_$0", $string );
		$string = trim( $string, "_" );
		return toUpperCase( $string );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromPascalCasetoUpperCase( $string )
	{
		return fromCamelCasetoUpperCase( $string );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromSnakeCasetoUpperCase( $string )
	{
		return toUpperCase( $string );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromUpperCamelCasetoUpperCase( $string )
	{
		$string = preg_replace( "/[A-Z]/", "_$0", $string );
		$string = trim( $string, "_" );
		return toUpperCase( $string );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromVerbObjectCasetoUpperCase( $string )
	{
		$string = fromVerbObjectCasetoCamelCase( $string );
		return fromCamelCasetoUpperCase( $string );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function toHungarianCase( String $str, String $type = "str" ): String
	{
		$parts = preg_split( "/(?=[A-Z])|[_-]/", $str );
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
		$words = preg_split( "/(?=[A-Z])/", $str );
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
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromCamelCasetoHungarianCase( String $str, String $type = "str" ): String
	{
		$parts = preg_split( "/(?=[A-Z])/", $str );
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
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromPascalCasetoHungarianCase( String $str, String $type = "str" ): String
	{
		return fromCamelCasetoHungarianCase( $str, $type );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromSnakeCasetoHungarianCase( String $str, String $type = "str" ): String
	{
		$parts = explode( "_", $str );
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
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromKebabCasetoHungarianCase( String $str, String $type = "str" ): String
	{
		$parts = explode( "-", $str );
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
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromUpperCamelCasetoHungarianCase( String $str, String $type = "str" ): String
	{
		$parts = preg_split( "/(?=[A-Z])/", lcfirst( $str ) );
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
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromUpperCasetoHungarianCase( $str )
	{
		$words = preg_split( "/(?=[A-Z])/", $str );
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
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function toVerbObjectCase( String $string ): String
	{
		return strtolower( preg_replace( "/(?<!^)([A-Z])/", " $1", $string ) );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromCamelCasetoVerbObjectCase( String $string ): String
	{
		return toVerbObjectCase( preg_replace( "/([a-z])([A-Z])/", "$1 $2", $string ) );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromPascalCasetoVerbObjectCase( String $string ): String
	{
		return fromCamelCasetoVerbObjectCase( $string );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromSnakeCasetoVerbObjectCase( String $string ): String
	{
		return toVerbObjectCase( str_replace( "_", "\x20", $string ) );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromKebabCasetoVerbObjectCase( String $string ): String
	{
		return toVerbObjectCase( str_replace( "-", "\x20", $string ) );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromUpperCamelCasetoVerbObjectCase( String $string ): String
	{
		return fromCamelCasetoVerbObjectCase( lcfirst( $string ) );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromUpperCasetoVerbObjectCase( String $string ): String
	{
		return toVerbObjectCase( strtolower( $string ) );
	}
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromHungarianCasetoVerbObjectCase( String $string ): String
	{
		$string = preg_replace( "/(?<!^)([A-Z])/", " $1", $string ); // separate words at upper case
		$string = preg_replace( "/(?<=[a-z])([A-Z])/", " $1", $string ); // separate words at mixed case
		return toVerbObjectCase( $string );
	}
	
	/*
	 * ...
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function toSpaceCase( $str )
	{
		$str = preg_replace( "/[^\p{L}\p{N}]+/u", "\x20", $str ); // replace non-letter and non-digit characters with space
		$str = trim( $str ); // trim leading and trailing spaces
		$str = preg_replace( "/\s+/u", "\x20", $str ); // replace multiple spaces with a single space
		$str = ucwords( $str ); // capitalize each word
		return $str;
	}
	
	/*
	 * ...
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromCamelCaseToSpaceCase( $str )
	{
		$str = preg_replace( "/([a-z])([A-Z])/", "$1 $2", $str ); // insert space before each capital letter
		$str = ucwords( $str ); // capitalize each word
		return $str;
	}
	
	/*
	 * ...
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromPascalCaseToSpaceCase( $str )
	{
		$str = preg_replace( "/([a-z])([A-Z])/", "$1 $2", $str ); // insert space before each capital letter
		$str = ucwords( $str ); // capitalize each word
		return $str;
	}
	
	/*
	 * ...
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromSnakeCaseToSpaceCase( $str )
	{
		$str = str_replace( "_", "\x20", $str ); // replace underscore with space
		$str = ucwords( $str ); // capitalize each word
		return $str;
	}
	
	/*
	 * ...
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromKebabCaseToSpaceCase( $str )
	{
		$str = str_replace( "-", "\x20", $str ); // replace hyphen with space
		$str = ucwords( $str ); // capitalize each word
		return $str;
	}
	
	/*
	 * ...
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromUpperCamelCaseToSpaceCase( $str )
	{
		$str = preg_replace( "/([a-z])([A-Z])/", "$1 $2", $str ); // insert space before each capital letter
		$str = ucwords( $str ); // capitalize each word
		return $str;
	}
	
	/*
	 * ...
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromUpperCaseToSpaceCase( $str )
	{
		$str = strtolower( $str ); // convert to lower case
		$str = ucwords( $str ); // capitalize each word
		return $str;
	}
	
	/*
	 * ...
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromHungarianCaseToSpaceCase( $str )
	{
		$str = preg_replace( "/([A-Z])/", " $1", $str ); // insert space before each capital letter
		$str = ucwords( $str ); // capitalize each word
		return $str;
	}
	
	/*
	 * ...
	 * @access Public Static
	 *
	 * @params String $
	 *
	 * @return String
	 */
	public static function fromVerbObjectCaseToSpaceCase( $str )
	{
		$str = preg_replace( "/([a-z])([A-Z])/", "$1 $2", $str ); // insert space before each capital letter
		$str = ucwords( $str ); // capitalize each word
		return $str;
	}
	
}

?>