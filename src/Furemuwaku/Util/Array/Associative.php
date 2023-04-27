<?php

namespace Yume\Fure\Util\Array;

use Traversable;

use Yume\Fure\Error;

/*
 * Associative
 *
 * @extends Yume\Fure\Util\Array\Arrayable
 *
 * @package Yume\Fure\Util\Array
 */
class Associative extends Arrayable
{
	
	/*
	 * Construct method of class Associative.
	 *
	 * @access Public Instance
	 *
	 * @params Array|Yume\Fure\Util\Array\Arrayable|Traversable $data
	 * @params Bool $insensitive
	 *
	 * @return Void
	 */
	public function __construct( Array | Arrayable | Traversable $data = [], public Readonly Bool $insensitive = False )
	{
		// Copy data from passed Arrayable instance.
		if( $data Instanceof Arrayable ) $data = $data->data;
		
		// Copy data from passed Traversable.
		if( $data Instanceof Traversable ) $data = toArray( $data, True );
		
		parent::__construct(
			$data
		);
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
	public function isInsensitive( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $this->insensitive ?: $optional : $this->insensitive );
	}
	
	/*
	 * Normalize array key.
	 *
	 * @access Private
	 *
	 * @params Mixed $key
	 *
	 * @return String
	 */
	private function normalize( Mixed $key ): String
	{
		if( $this->insensitive )
		{
			return( strtolower( ( String ) $key ) );
		}
		return( ( String ) $key );
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
		
		// Check if position is null.
		if( $offset === Null )
		{
			$this->data[] = $value;
			$this->keys = array_keys( $this->data );
		}
		else {
			$this->data[$this->keys[$offset]] = $value;
		}
	}
	
}

?>