<?php

namespace Yume\Fure\Util;

use Yume\Fure\Error;

/*
 * Types
 *
 * @package Yume\Fure\Util
 */
enum Types: Int
{
	
	case ARRAY = 67280;
	case ARRAY_ASSOCIATIVE = 67282;
	case ARRAY_LIST = 67284;
	case ARRAY_MULTIDIMENSION = 67286;
	case BOOLEAN = 11420;
	case BOOLEAN_FALSE = 11423;
	case BOOLEAN_TRUE = 11427;
	case CALLABLE = 25678;
	case CHAR = 33558;
	case CLOSURE = 23971;
	case INT = 35530;
	case INT_BINARY = 35532;
	case INT_DECIMAL = 35534;
	case INT_DOUBLE = 35536;
	case INT_FLOAT = 35537;
	case INT_HEXA = 46449;
	case INT_NUMERIC = 35541;
	case INT_OCTAL = 35543;
	case INTEGER = 35550;
	case MIXED = 19688;
	case NAN = 66658;
	case NULL = 0000;
	case OBJECT = 99957;
	case RESOURCE = 96622;
	case SCALAR = 57822;
	case STRING = 79182;
	
	/*
	 * Return if value given is matched with Type.
	 *
	 * @access Public
	 *
	 * @params Mixed $value
	 *
	 * @return Bool
	 */
	public function allow( Mixed $value, ? Types $self = Null ): Bool
	{
		return( match( $self ?? $this )
		{
			default => False
		});
	}
	
	/*
	 * Parse the given value to the appropriate Type.
	 *
	 * @access Public
	 *
	 * @params Mixed $value
	 *
	 * @return Mixed
	 */
	public function parse( Mixed $value ): Mixed
	{
		return( match( $this )
		{
			self::INT,
			self::INT_DECIMAL
			self::INT_DOUBLE,
			self::INT_FLOAT,
			self::INT_NUMERIC,
			self::INTEGER => Number::parse( $value ),
			
			self::INT_BINARY => decbin( Number::parse( $value ) ),
			self::INT_HEXA => dechex( Number::parse( $value ) ),
			self::INT_OCTAL => decoct( Number::parse( $value ) ),
			
			default => $value
		});
	}
	
}

?>