<?php

namespace Yume\Fure\Service;

use Yume\Fure\Error;

/*
 * ServiceError
 *
 * @package Yume\Fure\Support\Service
 *
 * @extends Yume\Fure\Error\RuntimeError;
 */
class ServiceError extends Error\RuntimeError {
	
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
	 * Error constants for service that cannot be overridden.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const OVERRIDE_ERROR = 343578;
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		ServiceError::class => [
			self::LOOKUP_ERROR => "No service named {}",
			self::NAME_ERROR => "Service name must be type Object|String, {type(+):ucfirst} given",
			self::OVERRIDE_ERROR => "Can't override service {}"
		],
		ServiceLookupError::class => [
			self::LOOKUP_ERROR => "No service named {}"
		],
		ServiceOverrideError::class => [
			self::OVERRIDE_ERROR => "Can't override service {}"
		]
	];
	
}

?>