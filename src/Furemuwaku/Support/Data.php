<?php

namespace Yume\Fure\Support;

use Closure;
use Traversable;

use Yume\Fure\Error;
use Yume\Fure\Util;
use Yume\Fure\Util\Arr;

/*
 * Data
 *
 * @extends Yume\Fure\Util\Arr\Associative
 *
 * @package Yume\Fure\Support
 */
class Data extends Arr\Associative
{
	
	/*
	 * Construct method of class Data.
	 *
	 * @access Public Initialize
	 *
	 * @params Array|Yume\Fure\Util\Arr\Arrayable $data
	 * @params Bool $insensitive
	 *
	 * @return Void
	 */
	public function __construct( Array | Arr\Arrayable | Traversable $data = [], Bool $insensitive = False )
	{
		// Copy data from passed Arrayable instance.
		if( $data Instanceof Arr\Arrayable ) $data = $data->__toArray();
		
		// Copy data from passed Traversable.
		if( $data Instanceof Traversable ) $data = Util\Arrays::toArray( $data );
		
		// Mapping data.
		Util\Arrays::map( $data, static function( Int $i, Mixed $key, Mixed $value ) use( &$data ): Void
		{
			// If data value is Array type.
			if( is_array( $value ) )
			{
				$data[$key] = new Data( $value );
			}
		});
		parent::__construct( $data, $insensitive );
	}
	
	/*
	 * Triggered when invoking inaccessible methods in an object context.
	 *
	 * @access Public
	 *
	 * @params String $name
	 * @params Array $args
	 *
	 * @return Mixed
	 *
	 * @throws Yume\Fure\Eror\MethodError
	 */
	public function __call( String $name, Array $args ): Mixed
	{
		// Check if property exists.
		if( property_exists( $this, $name ) )
		{
			// Check if property is Closure.
			if( $this->{ $name } Instanceof Closure )
			{
				return( call_user_func_array( $this->{ $name }, $args ) );
			}
			throw new Error\MethodError( [ $name, __CLASS__ ], Error\MethodError::INVOKE_ERROR );
		}
		
		// Check if data overload is array.
		if( is_array( $this->data ) )
		{
			// Check if data is exists.
			if( isset( $this->data[$name] ) )
			{
				// Check if data is Closure.
				if( $this->data[$name] Instanceof Closure )
				{
					return( call_user_func( $this->data[$name], ...$args ) );
				}
				throw new Error\MethodError( [ $name, __CLASS__ ], Error\MethodError::INVOKE_ERROR );
			}
		}
		throw new Error\MethodError( [ $name, __CLASS__ ], Error\MethodError::NAME_ERROR );
	}
	
	/*
	 * Triggered when invoking inaccessible methods in a static context.
	 *
	 * @access Public Static
	 *
	 * @params String $name
	 * @params Array $args
	 *
	 * @return Mixed
	 *
	 * @throws Yume\Fure\Error\MethodError
	 */
	public static function __callStatic( String $name, Array $args ): Mixed
	{
		// Check if property exists.
		if( property_exists( __CLASS__, $name ) )
		{
			return( call_user_func( self::${ $name }, ...$args ) );
		}
		throw new Error\MethodError( [ $name, __CLASS__ ], Error\MethodError::NAME_ERROR );
	}
	
	/*
	 * Copy data value.
	 *
	 * @access Public
	 *
	 * @return Static
	 */
	public function copy(): Static
	{
		return( new Data( $this->__toArray() ) );
	}
	
}

?>