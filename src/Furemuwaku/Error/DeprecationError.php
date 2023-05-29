<?php

namespace Yume\Fure\Error;

/*
 * DeprecationError
 *
 * @extends Yume\Fure\Error\YumeError
 *
 * @package Yume\Fure\Error
 */
class DeprecationError extends YumeError
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
	 * @inherit Yume\Fure\YumeError
	 *
	 */
	protected Array $flags = [
		DeprecationError::class => [
			self::FUNCTION_ERROR => "Function {} has been deprecated, use {} instead",
			self::METHOD_ERROR => "Method {} has been deprecated, use {} instead"
		]
	];
	
}

?>