<?php

namespace Yume\Fure\Util\Arr;

use ArrayAccess;
use Countable;
use JsonSerializable;
use SeekableIterator;
use Stringable;

use Yume\Fure\Support;
use Yume\Fure\Util\Json;

/*
 * Arrayable
 *
 * @extends Yume\Fure\Support\Iterate
 *
 * @package Yume\Fure\Util\Arr
 */
abstract class Arrayable extends Support\Iterate implements ArrayAccess, Countable, JsonSerializable, SeekableIterator, Stringable {
	
	/*
	 * Get hidden data.
	 *
	 * @access Public
	 *
	 * @params String $name
	 *
	 * @return Mixed
	 */
	public function __get( String $name ): Mixed {
		return $this->offsetGet( $name );
	}
	
	/*
	 * Is data set.
	 *
	 * @access Public
	 *
	 * @params String $name
	 *
	 * @return Bool
	 */
	public function __isset( String $name ): Bool {
		return $this->offsetExists( $name );
	}
	
	/*
	 * Set new data.
	 *
	 * @access Public
	 *
	 * @params String $name
	 * @params Mixed $value
	 *
	 * @return Void
	 */
	public function __set( String $name, Mixed $value ): Void {
		$this->offsetSet( $name, $value );
	}
	
	/*
	 * Parse class to array.
	 *
	 * @access Public
	 *
	 * @return Array
	 */
	public function __toArray(): Array {
		$data = [];
		foreach( $this->data As $key => $val ) {
			if( $val Instanceof Arrayable ) {
				$val = $val->__toArray();
			}
			$data[$key] = $val;
		}
		return $data;
	}
	
	/*
	 * Parse class into string.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function __toString(): String {
		return Json\Json::encode( $this->__toArray(), JSON_INVALID_UTF8_SUBSTITUTE | JSON_PRETTY_PRINT );
	}
	
	/*
	 * Unset hidden data.
	 *
	 * @access Public
	 *
	 * @params String $name
	 *
	 * @return Void
	 */
	public function __unset( String $name ): Void {
		$this->offsetUnset( $name );
	}
	
	/*
	 * Split array into chunks.
	 *
	 * @access Public
	 *
	 * @params Int $length
	 * @params Bool $preserveKeys
	 *
	 * @return Static
	 */
	public function chunk( Int $length, Bool $preserveKeys = False ): Static {
		return new Static( array_chunk( $this->data, ...func_get_args() ) );
	}
	
	/*
	 * Filters elements of an array using a callback function.
	 *
	 * @access Public
	 *
	 * @params Callable $callback
	 * @params Int $mode
	 *  Please see https://www.php.net/manual/en/function.array-filter.php
	 *
	 * @return Static
	 */
	public function filter( ? Callable $callback = Null, Int $mode = ARRAY_FILTER_USE_BOTH ): Static {
		return new Static( array_filter( $this->data, $callback, $mode ) );
	}
	
	/*
	 * Return index of value.
	 *
	 * @access Public
	 *
	 * @params Mixed $value
	 *
	 * @return Int
	 */
	public function indexOf( Mixed $value ): Int {
		return in_array( $this->data, $value );
	}
	
	/*
	 * Check if Instance is Lists.
	 *
	 * @access Public
	 *
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public function isList( ? Bool $optional ): Bool {
		return $optional !== Null ? $this Instanceof Lists === $optional : $this Instanceof Lists;
	}

	/*
	 * Allow class to JSON Serialize.
	 * 
	 * @access Public
	 * 
	 * @return Mixed
	 */
	public function jsonSerialize(): Mixed {
		return $this->__toArray();
	}
	
	/*
	 * Return first array key element.
	 *
	 * @access Public
	 *
	 * @return Mixed
	 */
	public function keyFirst(): Mixed {
		return $this->keys[0] ?? Null;
	}
	
	/*
	 * Return last array key element.
	 *
	 * @access Public
	 *
	 * @return Mixed
	 */
	public function keyLast(): Mixed {
		return end( $this->keys );
	}
	
	/*
	 * Return key of value.
	 *
	 * @access Public
	 *
	 * @params Mixed $value
	 *
	 * @return Int|String
	 */
	public function keyOf( Mixed $value ): Int | String {
		return $this->keys[$this->indexOf( $value )];
	}
	
	/*
	 * Return array keys.
	 *
	 * @access Public
	 *
	 * @return Array
	 */
	public function keys(): Array {
		return $this->keys;
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
	public function map( Callable $callback ): Static {
		$stack = [];
		$keys = $this->keys();
		$vals = $this->values();
		for( $i = 0; $i < $this->count(); $i++ ) {
			if( isset( $keys[$i] ) ) {
				try {
					$stack[$keys[$i]] = call_user_func( $callback, $i, $keys[$i], $vals[$i] );
				}
				catch( Support\Stoppable $stopped ) {
					$stack[$keys[$i]] = $stopped;
				}
				if( $stack[$keys[$i]] Instanceof Support\Stoppable ) {
					$stack[$keys[$i]] = $stack[$keys[$i]]->value;
					break;
				}
			}
		}
		return new Static( $stack );
	}
	
	/*
	 * Replaces elements from passed arrays into the first array.
	 *
	 * @access Public
	 *
	 * @params Array|Yume\Fure\Util\Arr\Arrayable $array
	 * @params Bool $recursive
	 *  Allow recursive replace.
	 *
	 * @return Static
	 */
	public function replace( Array | Arrayable $array, Bool $recursive = False ): Static {
		if( $array Instanceof Arrayable ) {
			$array = $array->data;
		}
		foreach( $array As $offset => $value ) {
			if( is_array( $value ) || $value Instanceof Arrayable ) {
				
				// If recursive option is allowed, we will check if element is exists,
				// and if value of element is Array or Arrayable class.
				if( $recursive && isset( $this[$offset] ) && $this[$offset] Instanceof Arrayable ) {
					
					// Recursion of array values until exhausted.
					$value = $this[$offset]->replace( $value );
				}
				else {
					$value = $value Instanceof Arrayable ? $value : new Static( $value );
				}
			}
			$this[$offset] = $value;
		}
		return $this;
	}
	
	/*
	 * Array element values.
	 *
	 * @access Public
	 *
	 * @return Array
	 */
	public function values(): Array {
		return array_values( $this->data );
	}
	
}

?>