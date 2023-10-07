<?php

namespace Yume\Fure\Error;

use Throwable;

/*
 * UnicodeError
 *
 * @extends Yume\Fure\Error\ValueError
 *
 * @package Yume\Fure\Error
 */
class UnicodeError extends ValueError
{
	/*
	 * @inherit Yume\Fure\Error\YumeError::__construct
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::UNICODE_ERROR, ? Throwable $previous = Null, ? String $file = Null, ? Int $line = Null )
	{
		parent::__construct( $message, $code, $previous, $file, $line );
	}
}

?>
