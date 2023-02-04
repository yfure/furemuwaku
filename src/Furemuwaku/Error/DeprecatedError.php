<?php

namespace Yume\Fure\Error;

/*
 * DeprecatedError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\BaseError
 */
class DeprecatedError extends BaseError
{
	
	/*
	 * Error constant for deprecated function.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const FUNCTION_ERROR = 63567;
	
	/*
	 * Error constant for deprecated method.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const METHOD_ERROR = 75272;
	
	/*
	 * @inherit Yume\Fure\BaseError
	 *
	 */
	protected Array $flags = [
		DeprecatedError::class => [
			self::FUNCTION_ERROR,
			self::METHOD_ERROR
		]
	];
	
}

?>