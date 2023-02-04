<?php

namespace Yume\Fure\Error;

use Throwable;

/*
 * ClassNameError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\ClassError
 */
class ClassNameError extends ClassError
{
	
	/*
	 * @inherit Yume\Fure\Error\ClassError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::NAME_ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( $message, $code, $previous );
	}
	
}

?>