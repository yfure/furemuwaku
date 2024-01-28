<?php

namespace Yume\Fure\IO\Path;

use Yume\Fure\Error;

/*
 * PathError
 *
 * @extends Yume\Fure\Error\IOError
 *
 * @package Yume\Fure\IO\Path
 */
class PathError extends Error\IOError {
	
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
	 * Error constant for errors while reading the file.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const READ_ERROR = 84901;
	
	/*
	 * Error constant for error while writing the contents of the file.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const WRITE_ERROR = 84911;
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		PathError::class => [
			self::COPY_ERROR => "Failed copy directory to {} from {}",
			self::MOVE_ERROR => "Failed move directory to {} from {}",
			self::NOT_FOUND_ERROR => "No such directory {}",
			self::READ_ERROR => "Cannot read anything in directory {}",
			self::WRITE_ERROR => "Could not write to file or directory in directory {}"
		]
	];
}

?>