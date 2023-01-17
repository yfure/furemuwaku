<?php

namespace Yume\Fure\Support\Services;

use Throwable;

/*
 * ServicesLookupError
 *
 * @package Yume\Fure\Support\Services
 *
 * @extends Yume\Fure\Support\Services\ServicesError
 */
class ServicesLookupError extends ServicesError
{
	
	/*
	 * @inherit Yume\Fure\Support\Services\ServicesError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = parent::LOOKUP_ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( ...func_get_args() );
	}
	
}

?>