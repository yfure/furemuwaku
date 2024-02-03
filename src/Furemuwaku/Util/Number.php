<?php

namespace Yume\Fure\Util;

/*
 * Number
 *
 * @package Yume\Fure\Util
 */
class Number {
	
	/*
	 * Numeric string utility.
	 *
	 * @include Numeric
	 */
	use \Yume\Fure\Util\Numeric;
	
	/*
	 * Return whether the number is even.
	 *
	 * @access Public Static
	 *
	 * @params Int $n
	 *
	 * @return Bool
	 */
	public static function isEven( Int $n ): Bool {
		return( $n % 2 === 0 );
	}
	
	/*
	 * Return whether the number is odd.
	 *
	 * @access Public Static
	 *
	 * @params Int $n
	 *
	 * @return Bool
	 */
	public static function isOdd( Int $n ): Bool {
		return( $n % 2 !== 0 );
	}
	
	/*
	 * Parses any data type to Double/Float|Int|Integer.
	 *
	 * @access Public Static
	 *
	 * @params Mixed $args
	 *
	 * @return Double|Float|Int|Integer
	 */
	public static function parse( Mixed $args ): Float | Int {
		return( match( True ) {
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
	
}

?>