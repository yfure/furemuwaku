<?php

namespace Yume\Fure\Util;

/*
 * Number
 *
 * @package Yume\Fure\Util
 */
class Number
{
	
	/*
	 * Numeric string utility.
	 *
	 * @include Numeric
	 */
	use \Yume\Fure\Util\Numeric;
	
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
	
}

?>