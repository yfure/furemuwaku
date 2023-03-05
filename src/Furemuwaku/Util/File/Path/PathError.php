<?php

namespace Yume\Fure\Util\File\Path;

use Yume\Fure\Error;

/*
 * PathError
 *
 * @package Yume\Fure\Util\File\Path
 *
 * @extends Yume\Fure\Error\PermissionError
 */
class PathError extends Error\PermissionError
{
	
	/*
	 * Error constant for errors when copying directory.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const COPY_ERROR = 42825;
	
	/*
	 * Error constant for errors when moving directory.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const MOVE_ERROR = 42952;
	
	/*
	 * Error constant for directory not found.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const NOT_FOUND_ERROR = 43618;
	
	/*
	 * @inherit Yume\Fure\Error\PermissionError
	 *
	 */
	protected Array $flags = [
		PathError::class => [
			self::COPY_ERROR,
			self::MOVE_ERROR,
			self::NOT_FOUND_ERROR,
			self::READ_ERROR,
			self::WRITE_ERROR
		]
	];
	
}

?>