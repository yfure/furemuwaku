<?php

namespace Yume\Fure\Error;

/*
 * ConstantError
 *
 * @extends Yume\Fure\Error\ClassError
 *
 * @package Yume\Fure\Error
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
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		ConstantError::class => [
			self::ACCESS_ERROR => "Constant {}::{} is not accessible from outsite class",
			self::NAME_ERROR => "Class {} has no constant named {}"
		]
	];
	
}

?>
