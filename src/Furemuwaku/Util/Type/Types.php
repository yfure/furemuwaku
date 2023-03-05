<?php

namespace Yume\Fure\Util\Type;

use Closure;
use Throwable;

use Yume\Fure\Error;
use Yume\Fure\HTTP\IP;
use Yume\Fure\Util\Array;

/*
 * Types
 *
 * @package Yume\Fure\Util\Type
 */
enum Types: Int
{
	
	/*
	 * Contants for Array types.
	 *
	 * @access Public Static
	 */
	case ARRAY = 14440;
	case ARRAY_ASSOCIATIVE = 14445;
	case ARRAY_LIST = 14447;
	case ARRAY_MULTIDIMENSION = 14449;
	
	/*
	 * Constant for Binary type.
	 *
	 * @access Public Static
	 */
	case BINARY = 16700;
	
	/*
	 * Constant for Boolean types.
	 *
	 * @access Public Static
	 */
	case BOOLEAN = 19540;
	case BOOLEAN_FALSE = 19543;
	case BOOLEAN_TRUE = 19547;
	
	/*
	 * Constant for Function types.
	 *
	 * @access Public Static
	 */
	case CALLABLE = 20680;
	case CLOSURE = 20682;
	
	/*
	 * Constant for Decimal type.
	 *
	 * @access Public Static
	 */
	case DECIMAL = 21150;
	
	/*
	 * Constant for Double type.
	 *
	 * @access Public Static
	 */
	case DOUBLE = 85600;
	
	/*
	 * Constant for Email Address type.
	 *
	 * @access Public Static
	 */
	case EMAIL = 20160;
	
	/*
	 * Constant for Exponent Double type.
	 *
	 * @access Public Static
	 */
	case EXPONENT_DOUBLE = 95205;
	
	/*
	 * Constant for Float type.
	 *
	 * @access Public Static
	 */
	case FLOAT = 54413;
	
	/*
	 * Constant for Finites type.
	 *
	 * @access Public Static
	 */
	case FINITE = 22900;
	case INFINITE = 22908;
	
	/*
	 * Constant for Hexadecimal type.
	 *
	 * @access Public Static
	 */
	case HEXA = 23320;
	
	/*
	 * Constant for Int type.
	 *
	 * @access Public Static
	 */
	case INT = 30380;
	
	/*
	 * Constant for Integers type.
	 *
	 * @access Public Static
	 */
	case INTEGER = 30490;
	case INTEGER_BINARY = 30492;
	case INTEGER_DECIMAL = 30494;
	case INTEGER_HEXA = 30496;
	case INTEGER_OCTAL = 30498;
	
	/*
	 * Constant for IP Address type.
	 *
	 * @access Public Static
	 */
	case IP = 11017;
	
	/*
	 * Constant for JSON type.
	 *
	 * @access Public Static
	 */
	case JSON = 61159;
	
	/*
	 * Constant for Long type.
	 *
	 * @access Public Static
	 */
	case LONG = 71755;
	
	/*
	 * Constant for Mixed type.
	 *
	 * @access Public Static
	 */
	case MIXED = 88678;
	
	/*
	 * Constant for NaN type.
	 *
	 * @access Public Static
	 */
	case NAN = 46480;
	
	/*
	 * Constant for Number type.
	 *
	 * @access Public Static
	 */
	case NUMBER = 53020;
	
	/*
	 * Constant for Null type.
	 *
	 * @access Public Static
	 */
	case NULL = 0;
	
	/*
	 * Constant for Numerics type.
	 *
	 * @access Public Static
	 */
	case NUMERIC = 22340;
	case NUMERIC_DOUBLE = 22341;
	case NUMERIC_EXPONENT_DOUBLE = 22342;
	case NUMERIC_FLOAT = 22344;
	case NUMERIC_INT = 22346;
	case NUMERIC_LONG = 22348;
	case NUMERIC_NUMBER = -22348;
	
	/*
	 * Constant for Object type.
	 *
	 * @access Public Static
	 */
	case OBJECT = 65507;
	
	/*
	 * Constant for Resource type.
	 *
	 * @access Public Static
	 */
	case RESOURCE = 70032;
	
	/*
	 * Constant for Scalar type.
	 *
	 * @access Public Static
	 */
	case SCALAR = 10200;
	
	/*
	 * Constant for String type.
	 *
	 * @access Public Static
	 */
	case STRING = 90205;
	
	/*
	 * Constant for Octal type.
	 *
	 * @access Public Static
	 */
	case OCTAL = 32895;
	
	/*
	 * Constant for UUID type.
	 *
	 * @access Public Static
	 */
	case UUID = 15600;
	
	/*
	 * Return if value given is matched with Type.
	 *
	 * @access Public
	 *
	 * @params Mixed $value
	 * @params Yume\Fure\Util\Type\Types $self
	 *
	 * @return Bool
	 */
	public function valid( Mixed $value, ? Types $self = Null ): Bool
	{
		return( match( $self ?? $this )
		{
			self::ARRAY => is_array( $value ),
			self::ARRAY_ASSOCIATIVE => is_array( $value ) && Array\Arr::isAssoc( $value ),
			self::ARRAY_LIST => is_array( $value ) && Array\Arr::isList( $value ),
			self::ARRAY_MULTIDIMENSION => is_array( $value ) && Array\Arr::isMulti( $value ),
			self::BINARY,
			self::INTEGER_BINARY => Num::isBinary( $value ),
			self::BOOLEAN => is_bool( $value ),
			self::BOOLEAN_FALSE => $value === False,
			self::BOOLEAN_TRUE => $value === True,
			self::CALLABLE => is_callable( $value ),
			self::CLOSURE => is_callable( $value ) && $value Instanceof Closure,
			self::DECIMAL,
			self::INTEGER_DECIMAL => Num::isDecimal( $value ),
			self::DOUBLE,
			self::NUMERIC_DOUBLE => is_double( $value ) || Num::isDouble( $value ),
			self::EMAIL => Str::isEmail( $value ),
			self::EXPONENT_DOUBLE,
			self::NUMERIC_EXPONENT_DOUBLE => Num::isExponentDouble( $value ),
			self::FLOAT,
			self::NUMERIC_FLOAT => is_float( $value ) || Num::isFloat( $value ),
			self::FINITE => is_float( $value ) && is_finite( $value ),
			self::INFINITE => is_float( $value ) && is_infinite( $value ),
			self::HEXA,
			self::INTEGER_HEXA => Num::isHexa( $value ),
			self::INT,
			self::NUMERIC_INT => is_int( $value ) || Num::isInt( $value ),
			self::INTEGER => is_integer( $value ) || Num::isInteger( $value ),
			self::IP => is_string( $value ) && IP\IP::valid( $value ),
			self::JSON => Str::isJson( $value ),
			self::LONG,
			self::MIXED => True,
			self::NUMERIC_LONG => is_long( $value ) || Num::isLong( $value ),
			self::NAN => is_nan( $value ),
			self::NUMBER,
			self::NUMERIC_NUMBER => Num::isNumber( $value ),
			self::NULL => $value === Null,
			self::NUMERIC => is_numeric( $value ) || Num::isNumeric( $value ),
			self::OBJECT => is_object( $value ),
			self::RESOURCE => is_resource( $value ),
			self::SCALAR => is_scalar( $value ),
			self::STRING => is_string( $value ),
			self::OCTAL,
			self::INTEGER_OCTAL => Num::isOctal( $value ),
			self::UUID => Str::isUUID( $value ),
			
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
			self::ARRAY => 0,
			self::ARRAY_ASSOCIATIVE => 0,
			self::ARRAY_LIST => 0,
			self::ARRAY_MULTIDIMENSION => 0,
			
			self::BINARY,
			self::INTEGER_BINARY => 0,
			
			self::BOOLEAN => 0,
			self::BOOLEAN_FALSE => 0,
			self::BOOLEAN_TRUE => 0,
			
			self::CALLABLE,
			self::CLOSURE => fn() => $value,
			
			self::DECIMAL,
			self::INTEGER_DECIMAL => 0,
			
			self::DOUBLE,
			self::NUMERIC_DOUBLE => 0,
			
			self::EMAIL => 0,
			
			self::EXPONENT_DOUBLE,
			self::NUMERIC_EXPONENT_DOUBLE => 0,
			
			self::FLOAT,
			self::NUMERIC_FLOAT => 0,
			
			self::FINITE => 0,
			self::INFINITE => 0,
			
			self::HEXA,
			self::INTEGER_HEXA => 0,
			
			self::INT,
			self::NUMERIC_INT => 0,
			
			self::INTEGER => 0,
			
			self::IP => 0,
			
			self::JSON => 0,
			
			self::LONG,
			self::NUMERIC_LONG => 0,
			
			self::NAN => 0,
			
			self::NUMBER,
			self::NUMERIC_NUMBER => 0,
			
			self::NULL => 0,
			
			self::NUMERIC => 0,
			
			self::OBJECT => 0,
			
			self::RESOURCE => 0,
			
			self::SCALAR => 0,
			
			self::STRING => 0,
			
			self::OCTAL,
			self::INTEGER_OCTAL => 0,
			
			self::UUID => 0,
			
			self::MIXED => $value,
			
			default => throw new Error\ValueError( f( "Can't parse {} into {} value type", type( $value ), $this->name ) )
		});
	}
	
}

?>