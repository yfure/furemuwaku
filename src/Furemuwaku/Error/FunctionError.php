<?php

namespace Yume\Fure\Error;

/*
 * FunctionError
 *
 * @extends Yume\Fure\Error\ReflectError
 *
 * @package Yume\Fure\Error
 */
class FunctionError extends ReflectError {
	
	/*
	 * Error constant for undefined function name.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const NAME_ERROR = 68445;
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		FunctionError::class => [
			self::NAME_ERROR => "No function named {}"
		]
	];
	
}

?>