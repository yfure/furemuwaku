<?php

namespace Yume\Fure\Util;

use Yume\Fure\Error;
use Yume\Fure\Util\RegExp;

/*
 * Number
 *
 * @package Yume\Fure\Util
 */
abstract class Number
{
	
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
	 * Check if string is number only.
	 *
	 * @access Public Static
	 *
	 * @params String $ref
	 *
	 * @return Bool
	 */
	public static function valid( String $ref ): Bool
	{
		return( RegExp\RegExp::test( "/^(?:\d+)$/", $ref ) );
	}
	
	/*
	 * Generate random int.
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