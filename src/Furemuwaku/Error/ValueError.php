<?php

namespace Yume\Fure\Error;

/*
 * ValueError
 *
 * @extends Yume\Fure\Error\AssertionError
 *
 * @package Yume\Fure\Error
 */
class ValueError extends AssertionError
{
	
	/*
	 * Error constant for invalid length passed.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const LENGTH_ERROR = 78183;
	
	/*
	 * Error constant for invalid unicode passed.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const UNICODE_ERROR = 79262;
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		ValueError::class => [
			self::LENGTH_ERROR => "Length of {} must be {}, {} given",
			self::UNICODE_ERROR => "Unkown and Invalid unicode for {}"
		]
	];
	
}

?>