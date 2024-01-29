<?php

namespace Yume\Fure\Error;

/*
 * LookupError
 *
 * @extends Yume\Fure\Error\ReferenceError
 *
 * @package Yume\Fure\Error
 */
class LookupError extends ReferenceError
{
	
	/*
	 * Error constant for index not in range.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const INDEX_ERROR = 857168;
	
	/*
	 * Error constant for undefined key.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const KEY_ERROR = 857287;
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		LookupError::class => [
			self::INDEX_ERROR => "Index {} out of range",
			self::KEY_ERROR => "Undefined key for {}"
		],
		IndexError::class => [
			self::INDEX_ERROR => "Index {} out of range"
		],
		KeyError::class => [
			self::KEY_ERROR => "Undefined key for {}"
		]
	];
	
}

?>
