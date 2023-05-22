<?php

namespace Yume\Fure\Support;

use Closure;
use Traversable;

use Yume\Fure\Error;
use Yume\Fure\Util;
use Yume\Fure\Util\Array;

/*
 * Data
 *
 * @extends Yume\Fure\Util\Array\Associative
 *
 * @package Yume\Fure\Support
 */
class Data extends Array\Associative
{
	
	/*
	 * Construct method of class Data.
	 *
	 * @access Public Initialize
	 *
	 * @params Array|Yume\Fure\Util\Array\Arrayable $data
	 * @params Bool $insensitive
	 *
	 * @return Void
	 */
	public function __construct( Array | Array\Arrayable | Traversable $data, Bool $insensitive = False )
	{
		// Copy data from passed Arrayable instance.
		if( $data Instanceof Array\Arrayable ) $data = $data->__toArray();
		
		// Copy data from passed Traversable.
		if( $data Instanceof Traversable ) $data = toArray( $data, True );
		
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
			if( $this->{ $name } Instanceof Closure === False )
			{
				return( call_user_func_array( $this->{ $name }, $args ) );
			}
			throw new Error\MethodError( [ $name, __CLASS__ ], Error\MethodError::CALLBACK_ERROR );
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
				throw new Error\MethodError( [ $name, __CLASS__ ], Error\MethodError::CALLBACK_ERROR );
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
			return( call_user_func( self::${ $name }, $this, ...$args ) );
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
	
	/*
	 * Applies the callback to the elements of the given arrays.
	 *
	 * @access Public
	 *
	 * @params Callable $callback
	 *
	 * @return Static
	 */
	public function map( Callable $callback ): Static
	{
		// Data stack.
		$stack = [];
		
		// Get data keys.
		$keys = $this->keys();
		$vals = $this->values();
		
		// Mapping data.
		for( $i = 0; $i < $this->count(); $i++ )
		{
			// Check if element is exists.
			if( isset( $keys[$i] ) )
			{
				// Get callback return value.
				$stack[$keys[$i]] = call_user_func(
					
					// Callback handler.
					$callback,
					
					// Index iteration.
					$i,
					
					// Array index.
					$keys[$i],
					
					// Array value.
					$vals[$i]
				);
				
				// Check if iteration is stop.
				if( $stack[$keys[$i]] === STOP_ITERATION ) break;
			}
		}
		return( new Data( $stack ) );
	}
	
}

?>