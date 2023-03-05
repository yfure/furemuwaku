<?php

namespace Yume\Fure\Support\Services;

use Yume\Fure\Error;

/*
 * ServicesError
 *
 * @package Yume\Fure\Support\Services
 *
 * @extends Yume\Fure\Error\TypeError;
 */
class ServicesError extends Error\TypeError
{
	
	/*
	 * Error constant for unavailable service.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const LOOKUP_ERROR = 343567;
	
	/*
	 * Error constant for invalid service name.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const NAME_ERROR = 34729;
	
	/*
	 * Error constants for services that cannot be overridden.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const OVERRIDE_ERROR = 343578;
	
	/*
	 * @inherit Yume\Fure\Error\TypeError
	 *
	 */
	protected Array $flags = [
		ServicesError::class => [
			self::LOOKUP_ERROR,
			self::NAME_ERROR,
			self::OVERRIDE_ERROR,
		]
	];
	
}

?>