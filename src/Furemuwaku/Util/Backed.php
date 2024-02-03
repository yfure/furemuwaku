<?php

namespace Yume\Fure\Util;

use Closure;

/*
 * Backed
 *
 * This trait only usage for Enum.
 *
 * @package Yume\Fure\Util
 */
trait Backed {
	
	/*
	 * Mapping enums.
	 *
	 * @access Public Static
	 *
	 * @params Closure $callback
	 *
	 * @return Array
	 */
	public static function map( Closure $callback ): Array {
		return( Arrays::map( array_combine( self::names(), self::cases() ), $callback ) );
	}
	
	/*
	 * Return enum unit cases.
	 *
	 * @access Public Static
	 *
	 * @return Array
	 */
	public static function names(): Array {
		return( array_column( self::cases(), "name" ) );
	}
	
	/*
	 * Return enum backed cases.
	 *
	 * @access Public Static
	 *
	 * @return Array
	 */
	public static function values(): Array {
		return( array_column( self::cases(), "value" ) );
	}
	
	/*
	 * Return enum case combined unit with backed.
	 *
	 * @access Public Static
	 *
	 * @params Bool $reverse
	 *
	 * @return Array
	 */
	public static function array( Bool $reverse = False ): array {
		if( $reverse ) {
			return( array_combine( self::values(), self::names() ) );
		}
		return( array_combine( self::names(), self::values() ) );
	}
	
}

?>