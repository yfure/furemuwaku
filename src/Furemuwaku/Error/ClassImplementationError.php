<?php

namespace Yume\Fure\Error;

use Throwable;

/*
 * ClassImplementationError
 *
 * @extends Yume\Fure\Error\ClassError
 *
 * @package Yume\Fure\Error
 */
final class ClassImplementationError extends ClassError {
	
	/*
	 * @inherit Yume\Fure\Error\YumeError::__construct
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::IMPLEMENTS_ERROR, ? Throwable $previous = Null, ? String $file = Null, ? Int $line = Null ) {
		parent::__construct( $message, $code, $previous, $file, $line );
	}
	
}

?>