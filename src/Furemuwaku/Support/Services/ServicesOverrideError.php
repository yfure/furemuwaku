<?php

namespace Yume\Fure\Support\Services;

use Throwable;

/*
 * ServicesOverrideError
 *
 * @package Yume\Fure\Support\Services
 *
 * @extends Yume\Fure\Support\Services\ServicesError
 */
class ServicesOverrideError extends ServicesError
{
	
	/*
	 * @inherit Yume\Fure\Support\Services\ServicesError
	 *
	 */
	protected Array $flags = [
		ServicesOverrideError::class => [
			self::OVERRIDE_ERROR
		]
	];
	
	/*
	 * @inherit Yume\Fure\Support\Services\ServicesError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = parent::OVERRIDE_ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( $message, $code, $previous );
	}
	
}

?>