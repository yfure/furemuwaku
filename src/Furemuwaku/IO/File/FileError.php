<?php

namespace Yume\Fure\IO\File;

use Yume\Fure\Error;

/*
 * FileError
 *
 * @extends Yume\Fure\Error\IOError
 *
 * @package Yume\Fure\IO\File
 */
class FileError extends Error\IOError {
	
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
	 * @inherit Yume\Fure\Error\YumeError::$flags
	 *
	 */
	protected Array $flags = [
		FileError::class => [
			self::COPY_ERROR => "Failed copy file from {} to {}",
			self::FILE_ERROR => "Target file {} is not file type",
			self::MOVE_ERROR => "Failed move file from {} to {}",
			self::MODE_ERROR => "Failed open file {}, invalid fopen mode for {}",
			self::NAME_ERROR => "The file name {} is invalid",
			self::NOT_FOUND_ERROR => "No such file {}",
			self::OPEN_ERROR => "Failed open file {}",
			self::PATH_ERROR => "Failed open file.{} because directory {} not found",
			self::READ_ERROR => "An error occurred while reading the contents of the file {}",
			self::WRITE_ERROR => "An error occurred while writing the contents of the file {}"
		]
	];
	
}

?>
