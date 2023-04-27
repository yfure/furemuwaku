<?php

namespace Yume\Fure\Util\Array;

use ArrayAccess;
use Countable;
use SeekableIterator;
use Stringable;

use Yume\Fure\Util;
use Yume\Fure\Util\Json;

/*
 * Arrayable
 *
 * @extends Yume\Fure\Util\Iterate
 *
 * @package Yume\Fure\Util\Arrayable
 */
abstract class Arrayable extends Util\Iterate implements ArrayAccess, Countable, SeekableIterator, Stringable
{
	
	/*
	 * Get hidden data.
	 *
	 * @access Public
	 *
	 * @params String $name
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
	 * @params String $name
	 *
	 * @return Bool
	 */
	public function __isset( String $name ): Bool
	{
		return( $this )->offsetExists( $name );
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
	public function __set( String $name, Mixed $value ): Void
	{
		$this->offsetSet( $name, $value );
	}
	
	/*
	 * Parse class to array.
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
			if( $val Instanceof Arrayable )
			{
				// Get deep data.
				$val = $val->__toArray();
			}
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
	 * @params String $name
	 *
	 * @return Void
	 */
	public function __unset( String $name ): Void
	{
		$this->offsetUnset( $name );
	}
	
}

?>