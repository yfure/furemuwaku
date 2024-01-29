<?php

namespace Yume\Fure\Util\Letter;

/*
 * VerbObjectCase
 *
 * @package Yume\Fure\Util\Letter
 */
trait VerbObjectCase {
	
	/*
	 * ...
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return String
	 */
	public static function toVerbObjectCase( String $string ): String {
		return( strtolower( preg_replace( "/(?<!^)([A-Z])/", " $1", $string ) ) );
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
	public static function fromCamelCasetoVerbObjectCase( String $string ): String {
		return( self::toVerbObjectCase( preg_replace( "/([a-z])([A-Z])/", "$1 $2", $string ) ) );
	}
	
	/*
	 * @inherit fromCamelCasetoVerbObjectCase
	 *
	 */
	public static function fromPascalCasetoVerbObjectCase( String $string ): String {
		return( self::fromCamelCasetoVerbObjectCase( $string ) );
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
	public static function fromSnakeCasetoVerbObjectCase( String $string ): String {
		return( self::toVerbObjectCase( str_replace( "_", "\x20", $string ) ) );
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
	public static function fromKebabCasetoVerbObjectCase( String $string ): String {
		return( self::toVerbObjectCase( str_replace( "-", "\x20", $string ) ) );
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
	public static function fromUpperCamelCasetoVerbObjectCase( String $string ): String {
		return( self::fromCamelCasetoVerbObjectCase( lcfirst( $string ) ) );
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
	public static function fromUpperCasetoVerbObjectCase( String $string ): String {
		return( self::toVerbObjectCase( strtolower( $string ) ) );
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
	public static function fromHungarianCasetoVerbObjectCase( String $string ): String {
		return(
			self::toVerbObjectCase(
				
				// separate words at mixed case
				preg_replace( "/(?<=[a-z])([A-Z])/", " $1", 
					
					// Separate words at upper case
					preg_replace( "/(?<!^)([A-Z])/", " $1", $string )
				)
			)
		);
	}
	
}

?>