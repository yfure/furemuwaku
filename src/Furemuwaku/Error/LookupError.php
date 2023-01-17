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
		self::INDEX_ERROR => "Index \"{}\" out of range",
		self::KEY_ERROR => "Undefined key for \"{}\""
	];
	
}

?>