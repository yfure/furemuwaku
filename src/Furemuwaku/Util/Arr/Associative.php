<?php

namespace Yume\Fure\Util\Arr;

use Traversable;

use Yume\Fure\util;

/*
 * Associative
 *
 * @extends Yume\Fure\Util\Arr\Arrayable
 *
 * @package Yume\Fure\Util\Arr
 */
class Associative extends Arrayable {
	
	/*
	 * Construct method of class Associative.
	 *
	 * @access Public Instance
	 *
	 * @params Array|Yume\Fure\Util\Arr\Arrayable|Traversable $data
	 * @params Bool $insensitive
	 *
	 * @return Void
	 */
	public function __construct( Array | Arrayable | Traversable $data = [], public Readonly Bool $insensitive = False ) {
		
		// Copy data from passed Arrayable instance.
		if( $data Instanceof Arrayable ) $data = $data->data;
		
		// Copy data from passed Traversable.
		if( $data Instanceof Traversable ) $data = Util\Arrays::toArray( $data );
		
		parent::__construct( $data );
	}
	
	/*
	 * Return if array key is not case sensitive character.
	 *
	 * @access Public
	 *
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public function isInsensitive( ? Bool $optional = Null ): Bool {
		return( $optional !== Null ? ( $this->insensitive ?: $optional ): $this->insensitive );
	}
	
	/*
	 * Normalize array key.
	 *
	 * @access Protected
	 *
	 * @params Mixed $key
	 *
	 * @return String
	 */
	protected function normalize( Mixed $key ): String {
		if( $this->insensitive && is_string( $key ) ) {
			return( strtolower( ( String ) $key ) );
		}
		return( ( String ) $key );
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
	public function offsetExists( Mixed $offset ): Bool {
		return( isset( $this->data[$this->normalize( $this->keys[( is_numeric( $idx = array_search( $offset, $this->keys ) ) ? $idx : Null )] ?? Null )] ) );
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
	public function offsetGet( Mixed $offset ): Mixed {
		return( $this->data[$this->normalize( $this->keys[is_numeric( $idx = array_search( $offset, $this->keys ) ) ? $idx : Null ] ?? Null )] ?? Null );
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
	public function offsetSet( Mixed $offset, Mixed $value ): Void {
		if( is_array( $value ) ) {
			if( array_is_list( $value ) ) {
				$value = new Lists( $value );
			}
			else {
				$value = new Associative( $value );
			}
		}
		if( $offset === Null ) {
			$this->data[] = $value;
		}
		else {
			$this->data[$offset] = $value;
		}
		$this->keys = array_map( fn( $key ) => $this->normalize( $key ), array_keys( $this->data ) );
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
	public function offsetUnset( Mixed $offset ): Void {
		if( is_numeric( $index = array_search( $this->normalize( $offset ), $this->keys ) ) ) {
			unset( $this->keys[$index] );
		}
		unset( $this->data[$offset] );
	}
	
}

?>