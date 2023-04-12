<?php

namespace Yume\Fure\CLI\Argument;

use Throwable;

/*
 * ArgumentJsonValueError
 *
 * @package Yume\Fure\CLI\Argument
 *
 * @extends Yume\Fure\CLI\Argument\ArgumentError
 */
class ArgumentJsonValueError extends ArgumentError
{
	/*
	 * @inherit Yume\Fure\CLI\Argument\ArgumentError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::JSON_VALUE_ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( $message, $code, $previous );
	}
}

?>