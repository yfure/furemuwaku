<?php

namespace Yume\Fure\Error;

use Throwable;

/*
 * TriggerError
 *
 * @extends Yume\Fure\Error\BaseError
 *
 * @package Yume\Fure\Error
 */
class TriggerError extends BaseError
{
	
	/*
	 * @inherit Yume\Fure\Error\BaseError
	 *
	 */
	public function __construct( String $message, String $level, String $file, Int $line, Int $code, ? Throwable $previous = Null )
	{
		// Set error information.
		$this->line = $line;
		$this->code = $code;
		$this->file = $file;
		$this->type = $level;
		$this->message = $message;
		
		// Call parent constructor.
		parent::__construct( $message, $code, $previous );
	}
	
}

?>