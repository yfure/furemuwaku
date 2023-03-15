<?php

namespace Yume\Fure\Support\Data;

use Yume\Fure\Error;
use Yume\Fure\Support\Collection;
use Yume\Fure\Util\Array;
use Yume\Fure\Util\Json;

/*
 * Data
 *
 * @package Yume\Fure\Support\Data
 */
class Data extends Collection\Collection implements DataInterface
{
	
	/*
	 * Construct method of Data.
	 *
	 * @access Public Instance
	 *
	 * @params Array $data
	 *
	 * @return Void
	 */
	public function __construct( Array | DataInterface $data = [] )
	{
		// Check if data is DataInterface.
		if( $data Instanceof DataInterface )
		{
			$data = $data->__toArray();
		}
		
		// Mapping data.
		Array\Arr::map( $data, static function( $i, $key, $value ) use( &$data ): Void
		{
			// If data value is Array type.
			if( is_array( $value ) )
			{
				$value = new Data( $value );
			}
			$data[$key] = $value;
		});
		parent::__construct( $data );
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
			// Check if property is callable.
			if( is_callable( $this->{ $name } ) === False )
			{
				throw new Error\MethodError( [ $name, __CLASS__ ], Error\MethodError::CALLBACK_ERROR );
			}
			return( call_user_func_array( $this->{ $name }, $args ) );
		}
		
		// Check if data overload is array.
		if( is_array( $this->data ) )
		{
			// Check if data is exists.
			if( isset( $this->data[$name] ) )
			{
				// Check if data is callable.
				if( is_callable( $this->data[$name] ) === False )
				{
					throw new Error\MethodError( [ $name, __CLASS__ ], Error\MethodError::CALLBACK_ERROR );
				}
				return( call_user_func_array( $this->data[$name], $args ) );
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
			return( call_user_func_array( self::${ $name }, $args ) );
		}
		throw new Error\MethodError( [ $name, __CLASS__ ], Error\MethodError::NAME_ERROR );
	}
	
	/*
	 * Get hidden data.
	 *
	 * @access Public
	 *
	 * @params String <name>
	 *
	 * @return Mixed
	 */
	public function __get( String $name ): Mixed
	{
		return( $this )->offsetGet( $name );
	}
	
	/*
	 * Is data set.
	 *
	 * @access Public
	 *
	 * @params String <name>
	 *
	 * @return Bool
	 */
	public function __isset( String $name ): Bool
	{
		return( $this )->offsetExists( $name );
	}
	
	/*
	 * Reset data saved.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function __reset( String | Int $name = 0, Mixed $value = Null ): Void
	{
		$this->data = [];
		$this->keys = [];
		
		if( $value !== Null )
		{
			$this->data[$name] = $value;
			$this->keys[] = $name;
		}
	}
	
	/*
	 * Set new data.
	 *
	 * @access Public
	 *
	 * @params String <name>
	 * @params Mixed <value>
	 *
	 * @return Void
	 */
	public function __set( String $name, Mixed $value ): Void
	{
		$this->offsetSet( $name, $value );
	}
	
	/*
	 * Parse class into array.
	 *
	 * @access Public
	 *
	 * @return Array
	 */
	public function __toArray(): Array
	{
		$data = [];
		
		// Mapping data.
		foreach( $this->data As $key => $val )
		{
			// Check if data value is Data class.
			if( $val Instanceof Data )
			{
				// Get deep data.
				$val = $val->__toArray();
			}
			// Push data value.
			$data[$key] = $val;
		}
		return( $data );
	}
	
	/*
	 * Parse class into string.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function __toString(): String
	{
		return( Json\Json::encode( $this->__toArray(), JSON_INVALID_UTF8_SUBSTITUTE | JSON_PRETTY_PRINT ) );
	}
	
	/*
	 * Unset hidden data.
	 *
	 * @access Public
	 *
	 * @params String <name>
	 *
	 * @return Void
	 */
	public function __unset( String $name ): Void
	{
		$this->offsetUnset( $name );
	}
	
	/*
	 * Copy data value.
	 *
	 * @access Public
	 *
	 * @return Yume\Fure\Support\Data\DataInterface
	 */
	public function copy(): DataInterface
	{
		return( new Data( $this->__toArray() ) );
	}
	
	/*
	 * Return the total value of data.
	 *
	 * @access Public
	 *
	 * @return Int
	 */
	public function count(): Int
	{
		if( is_array( $this->data ) )
		{
			return( count( $this->data ) );
		}
		return( 0 );
	}
	
	/*
	 * Join array elements with a string.
	 *
	 * @access Public
	 *
	 * @params String $separator
	 * @params Bool $key
	 *
	 * @return String
	 */
	public function join( String $separator, Bool $key = False ): String
	{
		if( $key )
		{
			return( implode( $separator, $this->keys() ) );
		}
		return( implode( $separator, array_values( $this->__toArray() ) ) );
	}
	
	/*
	 * Return array keys.
	 *
	 * @access Public
	 *
	 * @return Array
	 */
	public function keys(): Array
	{
		return( $this )->keys;
	}
	
	/*
	 * Applies the callback to the elements of the given arrays.
	 *
	 * @access Public
	 *
	 * @params Callable $callback
	 *
	 * @return Yume\Fure\Support\Data\DataInterface
	 */
	public function map( Callable $callback ): DataInterface
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
				if( $stack[$keys[$i]] === STOP_ITERATION )
				{
					break;
				}
			}
		}
		
		// Return new Data instance.
		return( new Data( $stack ) );
	}
	
	/*
	 * Assigns a value to the specified offset.
	 *
	 * @access Public
	 *
	 * @params Mixed $offset
	 * @params Mixed $value
	 *
	 * @return Void
	 */
	public function offsetSet( Mixed $offset, Mixed $value ): Void
	{
		if( is_array( $value ) )
		{
			$value = new Data( $value );
		}
		if( is_null( $offset ) )
		{
			$this->data[] = $value;
			$this->keys = array_keys( $this->data );
		}
		else {
			$this->data[$offset] = $value;
			$this->keys[] = $offset;
		}
	}
	
	/*
	 * Whether or not an offset exists.
	 *
	 * @access Public
	 *
	 * @params Mixed $offset
	 *
	 * @return Bool
	 */
	public function offsetExists( Mixed $offset ): Bool
	{
		return( in_array( $offset, $this->keys ) && isset( $this->data[$offset] ) );
	}
	
	/*
	 * Unsets an offset.
	 *
	 * @access Public
	 *
	 * @params Mixed $offset
	 *
	 * @return Void
	 */
	public function offsetUnset( Mixed $offset ): Void
	{
		if( $this->offsetExists( $offset ) )
		{
			if( False !== $index = array_search( $offset, $this->keys ) )
			{
				unset( $this->keys[$index] );
			}
			unset( $this->data[$offset] );
		}
	}
	
	/*
	 * Returns the value at specified offset.
	 *
	 * @access Public
	 *
	 * @params Mixed $offset
	 *
	 * @return Mixed
	 */
	public function offsetGet( Mixed $offset ): Mixed
	{
		return( $this->offsetExists( $offset ) ? $this->data[$offset] : Null );
	}
	
	/*
	 * @inherit Yume\Fure\Support\Collection\Collection::current
	 * 
	 */
	public function value(): Mixed
	{
		return( $this )->current();
	}
	
	/*
	 * Get value of array.
	 *
	 * @access Public
	 *
	 * @return Array
	 */
	public function values(): Array
	{
		return( array_values( $this->data ) );
	}
	
	/*
	 * Check if element exists by value.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function valueExists( Mixed $value ): Bool
	{
		return( in_array( $value, $this->data ) );
	}
	
}

?>