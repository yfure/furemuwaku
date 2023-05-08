<?php

namespace Yume\Fure\Util;

/*
 * Numeric
 *
 * @package Yume\Fure\Util
 */
trait Numeric
{
	
	/*
	 * Return if String is valid Integer Binary number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isBinary( String $string ): Bool
	{
		return( preg_match( "/^(?:(0[bB][01]+(_[01]+)*)|([01]+))$/", $string ) );
	}
	
	/*
	 * Return if String is valid Integer Decimal number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isDecimal( String $string ): Bool
	{
		return( preg_match( "/^(?:([1-9][0-9]*(_[0-9]+)*|0)|([0-9]+))$/", $string ) );
	}
	
	/*
	 * Return is String is valid Numeric Double number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isDouble( String $string ): Bool
	{
		return( preg_match( "/^(?:([0-9]*)[\.]([0-9]+)|([0-9]+)[\.]([0-9]*))$/", $string ) );
	}
	
	/*
	 * Return is String is valid Numeric Double Exponent number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isExponentDouble( String $string ): Bool
	{
		return( preg_match( "/^(?:((([0-9]+)|(([0-9]*)[\.]([0-9]+)|([0-9]+)[\.]([0-9]*)))[eE][+-]?([0-9]+)))$/", $string ) );
	}
	
	/*
	 * Return is String is valid Numeric Floating number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isFloat( String $string ): Bool
	{
		return( preg_match( "/^(?:\s*[+-]?((([0-9]*)[\.]([0-9]+)|([0-9]+)[\.]([0-9]*))|((([0-9]+)|(([0-9]*)[\.]([0-9]+)|([0-9]+)[\.]([0-9]*)))[eE][+-]?([0-9]+)))\s*)$/", $string ) );
	}
	
	/*
	 * Return is String is valid Integer Hexadecimal number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isHexa( String $string ): Bool
	{
		return( preg_match( "/^(?:(0[xX][0-9a-fA-F]+(_[0-9a-fA-F]+)*)|([0-9a-fA-F]+))$/", $string ) );
	}
	
	/*
	 * Return is String is valid Numeric Int number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isInt( String $string ): Bool
	{
		return( preg_match( "/^(?:\s*[+-]?([0-9]+)\s*)$/", $string ) );
	}
	
	/*
	 * Return is String is valid Integer Type.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isInteger( String $string ): Bool
	{
		return(
			self::isBinary( $string ) ||
			self::isDecimal( $string ) ||
			self::isHexa( $string ) ||
			self::isOctal( $string )
		);
	}
	
	/*
	 * Return is String is valid Numeric Long number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isLong( String $string ): Bool
	{
		return( preg_match( "/^(?:([0-9]+))$/", $string ) );
	}
	
	/*
	 * Return is String is valid Numeric number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isNumber( String $string ): Bool
	{
		return( preg_match( "/^(?:((\s*[+-]?([0-9]+)\s*)|(\s*[+-]?((([0-9]*)[\.]([0-9]+)|([0-9]+)[\.]([0-9]*))|((([0-9]+)|(([0-9]*)[\.]([0-9]+)|([0-9]+)[\.]([0-9]*)))[eE][+-]?([0-9]+)))\s*)))$/", $string ) );
	}
	
	/*
	 * Return is String is valid Numeric type.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isNumeric( String $string ): Bool
	{
		return(
			self::isDouble( $string ) ||
			self::isExponentDouble( $string ) ||
			self::isFloat( $string ) ||
			self::isInt( $string ) ||
			self::isLong( $string ) ||
			self::isNumber( $string )
		);
	}
	
	/*
	 * Return is String is valid Integer Octal number.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isOctal( String $string ): Bool
	{
		return( preg_match( "/^(?:(0[oO]?[0-7]+(_[0-7]+)*)|(0[1-7][0-7]*))$/", $string ) );
	}
	
	/*
	 * Return if string is UUID.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function isUUID( String $string ): Bool
	{
		return( preg_match( "/^[\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12}$/iD", $string ) );
	}
	
}

?>