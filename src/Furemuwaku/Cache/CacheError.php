<?php

namespace Yume\Fure\Cache;

use Yume\Fure\Error;

/*
 * CacheError
 *
 * @package Yume\Fure\Cache
 *
 * @extends Yume\Fure\Error\TypeError
 */
class CacheError extends Error\TypeError
{
	
	/*
	 * Error constant for invalid time value.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const TIME_ERROR = 70172;
	
	/*
	 * Error constant for invalid time to live value.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const TTL_ERROR = 72822;
	
	/*
	 * @inherit Yume\Fure\Error\TypeError
	 *
	 */
	protected Array $flags = [
		CacheError::class => [
			self::TIME_ERROR => "Invalid \$time \"{}\"",
			self::TTL_ERROR => "Provided TTL \"{}\" is not supported"
		]
	];
	
}

?>