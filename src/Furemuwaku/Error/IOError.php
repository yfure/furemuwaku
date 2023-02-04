<?php

namespace Yume\Fure\Error;

/*
 * IOError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\BaseError
 */
class IOError extends BaseError
{
	
	/*
	 * Error constants for permission errors.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const PERMISSION_ERROR = 61947;
	
	/*
	 * @inherit Yume\Fure\Error\BaseError
	 *
	 */
	protected Array $flags = [
		IOError::class => [
			self::PERMISSION_ERROR
		]
	];
	
}

?>