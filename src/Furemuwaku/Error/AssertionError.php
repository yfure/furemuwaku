<?php

namespace Yume\Fure\Error;

/*
 * AssertionError
 *
 * @extends Yume\Fure\Error\YumeError
 *
 * @package Yume\Fure\Error
 */
class AssertionError extends YumeError
{
	
	/*
	 * Error constant for invalid value given.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const VALUE_ERROR = 96488;
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		AssertionError::class => [
			self::VALUE_ERROR => "Invalid value for {}, value must be {}, {+:ucfirst} given"
		]
	];
	
}

?>