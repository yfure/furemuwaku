<?php

namespace Yume\Fure\Error;

/*
 * ClassInstanceError
 *
 * @extends Yume\Fure\Error\ClassError
 *
 * @package Yume\Fure\Error
 */
final class ClassInstanceError extends ClassError
{
	
	/*
	 * @inherit Yume\Fure\Error\YumeError::__construct
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::INSTANCE_ERROR, ? Throwable $previous = Null, ? String $file = Null, ? Int $line = Null )
	{
		parent::__construct( $message, $code, $previous, $file, $line );
	}
	
}

?>