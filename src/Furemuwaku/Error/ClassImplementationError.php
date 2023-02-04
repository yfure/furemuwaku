<?php

namespace Yume\Fure\Error;

use Throwable;

/*
 * ClassImplementationError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\ClassError
 */
class ClassImplementationError extends ClassError
{
	
	/*
	 * @inherit Yume\Fure\Error\ClassError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::IMPLEMENTS_ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( $message, $code, $previous );
	}
	
}

?>