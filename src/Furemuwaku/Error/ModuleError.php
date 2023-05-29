<?php

namespace Yume\Fure\Error;

/*
 * ModuleError
 *
 * @extends Yume\Fure\Error\IOError
 *
 * @package Yume\Fure\Error
 */
class ModuleError extends IOError
{
	
	/*
	 * Error constant .
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
	public const NOT_FOUND_ERROR = 78550;
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		ModuleError::class => [
			self::IMPORT_ERROR => "Something wrong when import module {}",
			self::NOT_FOUND_ERROR => "No module named {}"
		]
	];
	
}

?>