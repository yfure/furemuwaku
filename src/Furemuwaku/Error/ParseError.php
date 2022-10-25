<?php

namespace Yume\Fure\Error;

/*
 * ParseError
 *
 * @extends Yume\Fure\Error\BaseError
 *
 * @package Yume\Fure\Error
 */
class ParseError extends BaseError
{
	
	/*
	 * @inherit Yume\Fure\Error\BaseError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = 0, ? Throwable $previous = Null )
	{
		// Check if message is array.
		if( is_array( $message ) )
		{
			if( isset( $message['line'] ) )
			{
				$this->line = $message['line'];
			}
			if( isset( $message['file'] ) )
			{
				$this->file = $message['file'];
			}
			if( isset( $message['message'] ) )
			{
				$message = $message['message'];
			}
		}
		
		// Call parent constructor.
		parent::__construct( $message, $code, $previous );
	}
	
}

?>