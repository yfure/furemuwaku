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
		self::LOOKUP_ERROR => "No service named \"{}\"",
		self::OVERRIDE_ERROR => "Can't override service \"{}\""
	];
	
}

?>