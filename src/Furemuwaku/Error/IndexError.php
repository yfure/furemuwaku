<?php

namespace Yume\Fure\Error;

use Throwable;

/*
 * IndexError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\LookupError
 */
class IndexError extends LookupError
{
	/*
	 * @inherit Yume\Fure\Error\LookupError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = parent::INDEX_ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( ...func_get_args() );
	}
}

?>