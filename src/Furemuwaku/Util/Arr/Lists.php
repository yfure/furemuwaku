<?php

namespace Yume\Fure\Util\Arr;

use Traversable;

use Yume\Fure\Error;
use Yume\Fure\Util;

/*
 * Lists
 *
 * @extends Yume\Fure\Util\Arr\Arrayable
 *
 * @package Yume\Fure\Util\Arr
 */
class Lists extends Arrayable
{
	
	/*
	 * Construct method of class Lists.
	 *
	 * @access Public Instance
	 *
	 * @params Array|Yume\Fure\Util\Arr\Arrayable|Traversable $data
	 * @params Bool $keep
	 *
	 * @return Void
	 */
	public function __construct( Array | Arrayable $data = [], Bool $keep = False )
	{
		// Copy data from passed Arrayable instance.
		if( $data Instanceof Arrayable ) $data = $data->data;
		
		// Copy data from passed Traversable.
		if( $data Instanceof Traversable ) $data = Util\Arrays::toArray( $data );
		
		// Check if keep position is enabled.
		if( $keep )
		{
			// Check if array is not lists.
			if( array_is_list( $data ) === False )
			{
				throw new Error\UnexpectedError( "Can't keep array position because the array passed is not Lists" );
			}
		}
		else {
			
			// Allowed any array type to pass.
			// Because the array list just need value only.
			$data = array_values( $data );
		}
		
		// Initialize data.
		$this->data = [];
		$this->keys = [];
		
		foreach( $data As $idx => $val )
		{
			$this->offsetSet( $idx, $val );
		}
	}
	
	/*
	 * Assertion array key/ index.
	 *
	 * @access Private
	 *
	 * @params Mixed $offset
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\Error\AssertionError
	 */
	private function assert( Mixed &$offset ): Void
	{
		// Throw if offset is invalid numeric value.
		if( is_numeric( $offset ) === False )
		{
			throw new Error\AssertionError([ \Numeric::class, type( $offset ) ]);
		}
		$offset = ( Int ) $offset;
	}
	
	/*
	 * Whether an offset exists.
	 *
	 * @access Public
	 *
	 * @params Mixed $offset
	 *
	 * @return Bool
	 */
	public function offsetExists( Mixed $offset ): Bool
	{
		$this->assert( $offset );
		return( isset( $this->data[( $this->keys[( is_numeric( $idx = array_search( $offset, $this->keys ) ) ? $idx : Null )] ?? Null )] ) );
	}
	
	/*
	 * Offset to retrieve.
	 *
	 * @access Public
	 *
	 * @params Mixed $offset
	 *
	 * @return Mixed
	 */
	public function offsetGet( Mixed $offset ): Mixed
	{
		$this->assert( $offset );
		return( $this->data[( $this->keys[is_numeric( $idx = array_search( $offset, $this->keys ) ) ? $idx : Null ] ?? Null )] ?? Null );
	}
	
	/*
	 * Assign a value to the specified offset.
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
		// Check if value is array.
		if( is_array( $value ) )
		{
			// If value is Array list.
			if( array_is_list( $value ) )
			{
				$value = new Lists( $value );
			}
			else {
				$value = new Associative( $value );
			}
		}
		
		// Check if position is passed by iteration/ push e.g $x[]
		if( $offset === Null )
		{
			$this->data[] = $value;
			$this->keys = array_keys( $this->data );
		}
		else {
			$this->assert( $offset );
			$this->data[( $this->keys[$offset] ?? $this->keys[] = $offset )] = $value;
		}
	}
	
	/*
	 * Unset an offset.
	 *
	 * @access Public
	 *
	 * @params Mixed $offset
	 *
	 * @return Void
	 */
	public function offsetUnset( Mixed $offset ): Void
	{
		$this->assert( $offset );
		
		// Check if array key is exists.
		if( is_numeric( $index = array_search( $offset, $this->keys ) ) )
		{
			unset( $this->keys[$index] );
		}
		unset( $this->data[$offset] );
	}
	
}

?>