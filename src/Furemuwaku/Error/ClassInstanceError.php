<?php

namespace Yume\Fure\Error;

use Throwable;

/*
 * ClassInstanceError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\ClassError
 */
class ClassInstanceError extends ClassError
{
	
	/*
	 * @inherit Yume\Fure\Error\ClassError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::INSTANCE_ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( $message, $code, $previous );
	}
	
}

?>