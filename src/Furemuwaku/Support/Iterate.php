<?php

namespace Yume\Fure\Support;

use Countable;
use SeekableIterator;

use Yume\Fure\Error;

/*
 * Iterate
 * 
 * @package Yume\Fure\Support
 */
class Iterate implements Countable, SeekableIterator {
	
	/*
	 * Iterator position.
	 * 
	 * @access Protected
	 * 
	 * @values Int
	 */
	protected Int $index = 0;

	/*
	 * Keys from array elements.
	 * 
	 * @access Protected
	 * 
	 * @values Array
	 */
	protected Array $keys = [];

	/*
	 * Construct method of class Iterate.
	 * 
	 * @access Public INstance
	 * 
	 * @params Protected Array $data
	 * 
	 * @return Void
	 */
	public function __construct( protected Array $data = [] ) {
		$this->keys = array_keys( $data );
	}
	
	/*
	 * Count elements of an object.
	 *
	 * @access Public
	 *
	 * @return Int
	 */
	public function count(): Int {
		return( count( $this->data ) );
	}

	/*
	 * Return the current element.
	 * 
	 * @access Public
	 * 
	 * @return Mixed
	 */
	public function current(): Mixed {
		return( $this )->data[$this->keys[$this->index]];
	}
	
	/*
	 * Return the key of the current element.
	 * 
	 * @access Public
	 * 
	 * @return Mixed
	 */
	public function key(): Mixed {
		return( $this )->keys[$this->index];
	}

	/*
	 * Move forward to next element.
	 * 
	 * @access Public
	 * 
	 * @return Void
	 */
	public function next(): Void {
		++$this->index;
	}

	/*
	 * Rewind the Iterator to the first element.
	 * 
	 * @access Public
	 * 
	 * @return Void
	 */
	public function rewind(): Void {
		$this->seek( 0 );
	}

	/*
	 * Seeks to a position.
	 * 
	 * @access Public
	 * 
	 * @return Void
	 */
	public function seek( Int $i ): Void {
		if( isset( $this->keys[$i] ) === False ) {
			throw new Error\IndexError( $i );
		}
		$this->index = $i;
	}

	/*
	 * Checks if current position is valid.
	 * 
	 * @access Public
	 * 
	 * @return Bool
	 */
	public function valid(): Bool {
		return(
			isset( $this->keys[$this->index] ) &&
			isset( $this->data[$this->keys[$this->index]] )
		);
	}

}

?>