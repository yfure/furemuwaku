<?php

namespace Yume\Fure\Error;

use Throwable;

/*
 * TriggerError
 *
 * @extends Yume\Fure\Error\YumeError
 *
 * @package Yume\Fure\Error
 */
class TriggerError extends YumeError
{
	
	/*
	 * Construct method of class TriggerError.
	 *
	 * @access Public Initialize
	 *
	 * @params String $message
	 * @params String $type
	 * @params String $file
	 * @params Int $code
	 * @params Throwable $previous
	 *
	 * @return Void
	 */
	public function __construct( String $message, String $level, String $file, Int $line, Int $code, ? Throwable $previous = Null )
	{
		// Set error type.
		$this->type = $level;
		
		// Call parent constructor.
		parent::__construct( $message, $code, $previous, $file, $line );
	}
	
}

?>