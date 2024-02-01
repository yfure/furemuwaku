<?php

namespace Yume\Fure\Error;

use Throwable;

/*
 * KeyError
 *
 * @extends Yume\Fure\Error\LookupError
 *
 * @package Yume\Fure\Error
 */
class KeyError extends LookupError {
	
	/*
	 * @inherit Yume\Fure\Error\YumeError::__construct
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::KEY_ERROR, ? Throwable $previous = Null, ? String $file = Null, ? Int $line = Null ) {
		parent::__construct( $message, $code, $previous, $file, $line );
	}	
}

?>
