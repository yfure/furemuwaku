<?php

namespace Yume\Fure\Support\File;

use Yume\Fure\Error;

/*
 * FileError
 *
 * @package Yume\Fure\Support\File
 *
 * @extends Yume\Fure\Error\PermissionError
 */
class FileError extends Error\PermissionError
{
	
	/*
	 * Error constant for errors when copying files.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const COPY_ERROR = 82628;
	
	/*
	 * Error constant for open error.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const FILE_ERROR = 82387;
	
	/*
	 * Error constant for invalid fopen mode.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const MODE_ERROR = 82472;
	
	/*
	 * Error constant for errors when moving files.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const MOVE_ERROR = 82429;
	
	/*
	 * Error constant for invalid filename.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const NAME_ERROR = 83917;
	
	/*
	 * Error constant for file not found.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const NOT_FOUND_ERROR = 83927;
	
	/*
	 * Error constant for failed open file.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const OPEN_ERROR = 83977;
	
	/*
	 * Error constant for file path not found.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const PATH_ERROR = 84182;
	
	/*
	 * @inherit Yume\Fure\Error\PermissionError
	 *
	 */
	protected Array $flags = [
		FileError::class => [
			self::COPY_ERROR,
			self::FILE_ERROR,
			self::MOVE_ERROR,
			self::MODE_ERROR,
			self::NAME_ERROR,
			self::NOT_FOUND_ERROR,
			self::OPEN_ERROR,
			self::PATH_ERROR,
			self::READ_ERROR,
			self::WRITE_ERROR
		]
	];
	
}

?>