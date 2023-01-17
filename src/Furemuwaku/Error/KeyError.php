<?php

namespace Yume\Fure\Error;

use Throwable;

/*
 * KeyError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\LookupError
 */
class KeyError extends LookupError
{
	/*
	 * @inherit Yume\Fure\Error\LookupError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = parent::KEY_ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( ...func_get_args() );
	}
}

?>