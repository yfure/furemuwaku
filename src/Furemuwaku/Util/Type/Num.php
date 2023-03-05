<?php

namespace Yume\Fure\Util\Type;

use Yume\Fure\Error;
use Yume\Fure\Util\RegExp;

/*
 * Num
 *
 * @package Yume\Fure\Util\Type
 */
final class Num
{
	
	/*
	 * @inherit Yume\Fure\Util\Type\Str::isBinary
	 *
	 */
	public static function isBinary( String $string ): Bool
	{
		return( Str::isBinary( $string ) );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Type\Str::isDecimal
	 *
	 */
	public static function isDecimal( String $string ): Bool
	{
		return( Str::isDecimal( $string ) );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Type\Str::isDouble
	 *
	 */
	public static function isDouble( String $string ): Bool
	{
		return( Str::isDouble( $string ) );
	}
	
	/*
	 * PHP code to check whether the number is even.
	 *
	 * @access Public Static
	 *
	 * @params Int $n
	 *
	 * @return Bool
	 */
	public static function isEven( Int $n ): Bool
	{
		return( $n % 2 === 0 );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Type\Str::isExponentDouble
	 *
	 */
	public static function isExponentDouble( String $string ): Bool
	{
		return( Str::isExponentDouble( $string ) );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Type\Str::isFloat
	 *
	 */
	public static function isFloat( String $string ): Bool
	{
		return( Str::isFloat( $string ) );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Type\Str::isHexa
	 *
	 */
	public static function isHexa( String $string ): Bool
	{
		return( Str::isHexa( $string ) );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Type\Str::isInt
	 *
	 */
	public static function isInt( String $string ): Bool
	{
		return( Str::isInt( $string ) );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Type\Str::isInteger
	 *
	 */
	public static function isInteger( String $string ): Bool
	{
		return( Str::isInteger( $string ) );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Type\Str::isLong
	 *
	 */
	public static function isLong( String $string ): Bool
	{
		return( Str::isLong( $string ) );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Type\Str::isNumber
	 *
	 */
	public static function isNumber( String $string ): Bool
	{
		return( Str::isNumber( $string ) );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Type\Str::isNumeric
	 *
	 */
	public static function isNumeric( String $string ): Bool
	{
		return( Str::isNumeric( $string ) );
	}
	
	/*
	 * PHP code to check whether the number is odd.
	 *
	 * @access Public Static
	 *
	 * @params Int $n
	 *
	 * @return Bool
	 */
	public static function isOdd( Int $n ): Bool
	{
		return( $n % 2 !== 0 );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Type\Str::isOctal
	 *
	 */
	public static function isOctal( String $string ): Bool
	{
		return( Str::isOctal( $string ) );
	}
	
	/*
	 * Parses any data type to Double/Float|Int|Integer.
	 *
	 * @access Public Static
	 *
	 * @params Mixed $args
	 *
	 * @return Double/Float|Int|Integer
	 */
	public static function parse( Mixed $args ): Double | Float | Int | Integer
	{
		return( match( True )
		{
			is_bool( $args ),
			is_double( $args ),
			is_float( $args ),
			is_finite( $args ),
			is_infinite( $args ),
			is_int( $args ),
			is_integer( $args ),
			is_long( $args ),
			is_nan( $args ),
			is_null( $args ),
			is_numeric( $args ) => $args,
			
			// Force convert into Int type.
			default => (Int) $args
		});
	}
	
	/*
	 * Check if string is number only.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 *
	 * @return Bool
	 */
	public static function valid( Int | String $string ): Bool
	{
		if( is_string( $string ) )
		{
			return( self::isInteger( $string ) || self::isNumeric( $string ) );
		}
		return( True );
	}
	
	/*
	 * Generate random int.
	 *
	 * This function has deprecated on Yume on v3.0.6
	 *
	 * @source http://stackoverflow.com/a/13733588/
	 *
	 * @access Public Static
	 *
	 * @params Int $min
	 * @params Int $max
	 *
	 * @return Int
	 *
	 * @throws Yume\Fure\Error\DeprecatedError
	 */
	public static function random( Int $min, Int $max )
	{
		throw new Error\DeprecatedError( f( "Method {} has been deprecated, instead use {}", __METHOD__, "Yume\Fure\Util\Random\Random::number" ) );
	}
	
}

?>