<?php

namespace Yume\Fure\Error;

/*
 * LookupError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\ReferenceError
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
	 * @inherit Yume\Fure\Error\ReferenceError
	 *
	 */
	protected Array $flags = [
		LookupError::class => [
			self::INDEX_ERROR,
			self::KEY_ERROR
		]
	];
	
}

?>