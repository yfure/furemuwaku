<?php

namespace Yume\Fure\Util\Array;

use WeakMap;

/*
 * Reference
 *
 * It is a smart implementation of the Arrayable interface,
 * which also supports creating new instances such as Associative, Data,
 * and also List classes when new elements with Array values are added.
 *
 * With the help of WeakMap, the Reference class can associate each new
 * instance with the original instance, allowing easy tracing and access
 * to the original object hierarchy.
 *
 * This innovative class provides a unique way to manage complex data structures,
 * and opens up new possibilities for object-oriented programming in PHP.
 *
 * @package Yume\Fure\Util\Array
 */
final class Reference implements \ArrayAccess//Arrayable
{
	
	/*
	 * A WeakMap to associate the original instance of A with any new instances that are created when a new element whose value is an array is added.
	 *
	 * @access Private Readonly
	 *
	 * @values WeakMap
	 */
	private WeakMap $map;

	/*
	 * Construct method for class Reference.
	 *
	 * @access Public Instance
	 *
	 * @params Private Array $data
	 *
	 * @return Void
	 */
	public function __construct( private Array $data = [] )
	{
		// Initialize the WeakMap in the constructor.
		$this->map = new WeakMap();
		
		/*
		 * Associate the initial $data property with
		 * the current instance of Reference class in the WeakMap.
		 *
		 */
		$this->map[$this] = $this->data;
	}

	/*
	 * Check if the specified offset exists in the $data array.
	 *
	 * @access Public
	 *
	 * @params Mixed $offset
	 *  The offset to check for.
	 *
	 * @return Bool<True>
	 *  True if the offset exists in the $data array,
	 * @return Bool<False>
	 *  False otherwise.
	 */
	public function offsetExists( $offset ): Bool
	{
		return isset( $this->data[$offset] );
	}

	/*
	 * Return the value at the specified offset in the $data array.
	 *
	 * @access Public
	 *
	 * @params Mixed $offset
	 *  The offset to get the value for.
	 *
	 * @return Mixed
	 *  The value at the specified offset in the $data array.
	 */
	public function offsetGet( $offset ): Mixed
	{
		return( $this )->data[$offset];
	}

	/*
	 * ...
	 *
	 * If the value being set is an array, create a new instance
	 * of Reference class and associate it with the current instance
	 * in the WeakMap.
	 *
	 * @access Public
	 *
	 * @params Mixed $offset
	 *  The offset to set the value for.
	 * @params Mixed $value
	 *  The value to set at the specified offset.
	 *
	 * @return Void
	 */
	public function offsetSet( Mixed $offset, Mixed $value ): Void
	{
		if( is_null( $offset ) )
		{
			if( is_array( $value ) )
			{
				$value = new Reference( $value );
				$this->map[$value] = $value->getData();
			}
			$this->data[] = $value;
		}
		else {
			if( is_array( $value ) )
			{
				$this->data[$offset] = new Reference( $value );
				$this->map[$this->data[$offset]] = $this->data[$offset]->getData();
			}
			else {
				$this->data[$offset] = $value;
			}
		}
	}
	
	/*
	 * ...
	 *
	 * @access Public
	 *
	 * @params Mixed $offset
	 *
	 * @return Void
	 */
	public function offsetUnset( Mixed $offset ): Void
	{echo 0;
		if( $this->data[$offset] ?? Null Instanceof Reference )
		{
			unset( $this->map[$this->data[$offset]] );
		}
		unset( $this->data[$offset] );
	}
	
	/*
	 * Return the $data array.
	 *
	 * @access Public
	 *
	 * @return Array
	 *  The $data array for this instance of A.
	 */
	public function getData(): Array
	{
		return( $this )->data;
	}
	
	/*
	 * Return the original instance of Reference class
	 * associated with the current instance in the WeakMap.
	 *
	 * @access Public
	 *
	 * @return Static
	 *  The original instance of Reference class
	 *  associated with the current instance.
	 * @return Null
	 *  When if no original instance is found.
	 */
	public function self(): ? Reference
	{
		return( $this )->map[$this]->data;
	}
	
}

?>