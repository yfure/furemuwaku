<?php

namespace Yume\Fure\Util\Random;

/*
 * Random
 *
 * @package Yume\Fure\Util\Random
 */
abstract class Random
{
	
	/*
	 * Generate random int.
	 *
	 * @source http://stackoverflow.com/a/13733588/179104
	 *
	 * @access Public Static
	 *
	 * @params Int $min
	 * @params Int $max
	 *
	 * @return Int
	 */
	public static function number( Int $min, Int $max ): Int
	{
		// Not so random...
		if( ( $range = $max - $min ) < 0 ) return $min;
		
		// Length in bytes.
		$bytes = ( Int ) ( ( $log = log( $range, 2 ) ) / 8 ) + 1;
		
		// Set all lower bits to 1.
		$filter = ( Int ) ( 1 << ( $bits = ( Int ) $log + 1 ) ) - 1;
		
		do {
			
			$rnd = $bytes;
			$rnd = openssl_random_pseudo_bytes( $rnd );
			$rnd = bin2hex( $rnd );
			$rnd = hexdec( $rnd );
			
			// Discard irrelevant bits.
			$rnd = $rnd & $filter;
			
		}
		while( $rnd >= $range );
		
		// Returns the result of a minimal and random number.
		return( $min + $rnd );
	}
	
	/*
	 * Generate random string.
	 *
	 * @source http://stackoverflow.com/a/13733588/179104
	 *
	 * @access Public Static
	 *
	 * @params Int $length
	 * @params String $alphabet
	 *
	 * @return String
	 */
	public static function strings( Int $length = 32, ? String $alphabet = Null ): String
	{
		// Token stack.
		$token = [];
		
		// Check if length is zero.
		if( $length < 1 ) throw new Error\ValueError( "Random string length must be >=1" );
		
		// Check if alphabet is empty.
		if( valueIsEmpty( $alphabet ) )
		{
			$alphabet = implode( "", [
				"ABCDEFGHIJKLMNOPQRSTUVWXYZ",
				"abcdefghijklmnopqrstuvwxyz",
				"0123456789"
			]);
		}
		
		// Get alphabet length.
		$max = strlen( $alphabet );
		
		// Looping by length given.
		for( $i = 0; $i < $length; $i++ )
		{
			$token[] = $alphabet[self::number( 0, $max -1 )];
		}
		
		// Return tokens.
		return( implode( "", $token ) );
	}
	
}

?>