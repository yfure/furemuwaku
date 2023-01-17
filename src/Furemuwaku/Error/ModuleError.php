<?php

namespace Yume\Fure\Error;

use Yume\Fure\Support\File;

/*
 * ModuleError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\TypeError
 */
class ModuleError extends TypeError
{
	
	/*
	 * Error constant for .
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const IMPORT_ERROR = 78549;
	
	/*
	 * Error constant for
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const NOT_FOUND_ERROR = File\FileError::NOT_FOUND_ERROR;
	
	/*
	 * @inherit Yume\Fure\Error\TypeError
	 *
	 */
	protected Array $flags = [
		self::IMPORT_ERROR => "Something wrong when import file \"{}\"",
		self::NOT_FOUND_ERROR => "No module named \"{}\""
	];
	
}

?>