<?php

namespace Yume\Fure\Error;

/*
 * ConstantError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\ClassError
 */
class ConstantError extends ClassError
{
	
	/*
	 * Error constant for failed set accessible.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const ACCESS_ERROR = 25357;
	
	/*
	 * @inherit Yume\Fure\Error\ClassError
	 *
	 */
	protected Array $flags = [
		ConstantError::class => [
			self::ACCESS_ERROR,
			self::NAME_ERROR
		]
	];
	
}

?>