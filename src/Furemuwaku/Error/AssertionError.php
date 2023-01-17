<?php

namespace Yume\Fure\Error;

/*
 * AssertionError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\BaseError
 */
class AssertionError extends BaseError
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
	 * @inherit Yume\Fure\Error\BaseError
	 *
	 */
	protected Array $flags = [
		self::VALUE_ERROR => "Invalid value for \"{}\" values must be {} \"{}\" given"
	];
	
}

?>