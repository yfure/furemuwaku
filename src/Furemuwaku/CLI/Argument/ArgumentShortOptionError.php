<?php

namespace Yume\Fure\CLI\Argument;

use Throwable;

/*
 * ArgumentShortOptionError
 *
 * @package Yume\Fure\CLI\Argument
 *
 * @extends Yume\Fure\CLI\Argument\ArgumentError
 */
class ArgumentShortOptionError extends ArgumentError {
	
	/*
	 * @inherit Yume\Fure\Error\YumeError::__construct
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::SHORT_OPTION_ERROR, ? Throwable $previous = Null, ? String $file = Null, ? Int $line = Null ) {
		parent::__construct( $message, $code, $previous, $file, $line );
	}
}

?>