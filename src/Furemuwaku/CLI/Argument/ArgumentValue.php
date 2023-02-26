<?php

namespace Yume\Fure\CLI\Argument;

use ArrayAccess;

use Yume\Fure\Error;
use Yume\Fure\Util;

/*
 * ArgumentValue
 *
 * @package Yume\Fure\CLI\Argument
 */
class ArgumentValue implements ArrayAccess
{
	
	/*
	 * Argument name.
	 *
	 * @access Protected Readonly
	 *
	 * @values Int|String
	 */
	protected Readonly Int | String $name;
	
	/*
	 * Argument value.
	 *
	 * @access Protected Readonly
	 *
	 * @values 
	 */
	protected Readonly Mixed $value;
	
	/*
	 * Argument value type.
	 *
	 * @access Protected Readonly
	 *
	 * @values Yume\Fure\Util\Types
	 */
	protected Readonly Util\Types $type;
	
	/*
	 * Argument option (Long/Short).
	 *
	 * @access Protected Readonly
	 *
	 * @values Bool
	 */
	protected Readonly Bool $long;
	
	/*
	 * Construct method of class ArgumentValue.
	 *
	 * @access Public Instance
	 *
	 * @params Int|String $name
	 * @params Mixed $value
	 * @params String $type
	 * @params Bool $long
	 *
	 * @return Void
	 */
	public function __construct( Int | String $name, Mixed $value, Util\Types $type, Bool $long )
	{
		$this->name = $name;
		$this->value = $value;
		$this->type = $type;
		$this->long = $long;
	}
	
	/*
	 * Get value from ArgumentValue property.
	 *
	 * @access Public
	 *
	 * @params String $name
	 *
	 * @return Mixed
	 *
	 * @throws Yume\Fure\Error\PropertyError
	 */
	public function __get( String $name ): Mixed
	{
		if( $this->offsetExists( $name ) )
		{
			return( $this )->{ $name };
		}
		throw new Error\PropertyError( $name, Error\PropertyError::NAME_ERROR );
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
		return( property_exists( $this, $offset ) );
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
		return( $this )->__get( $offset );
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
	 *
	 * @throws Yume\Fure\CLI\Argument\ArgumentError
	 */
	public function offsetSet( Mixed $offset, Mixed $value ): Void
	{
		throw new ArgumentError( $offset, ArgumentError::SET_ERROR );
	}
	
	/*
	 * Unsets an offset.
	 *
	 * @access Public
	 *
	 * @params Mixed $offset
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\CLI\Argument\ArgumentError
	 */
	public function offsetUnset( Mixed $offset ): Void
	{
		throw new ArgumentError( $offset, ArgumentError::UNSET_ERROR );
	}
	
}

?>