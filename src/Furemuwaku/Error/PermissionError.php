<?php

namespace Yume\Fure\Error;

/*
 * PermissionError
 *
 * @extends Yume\Fure\Error\IOError
 *
 * @package Yume\Fure\Error
 */
class PermissionError extends IOError
{
	
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
		PermissionError::class => [
			self::READ_ERROR => "Access denied, unable to read {}",
			self::WRITE_ERROR => "Access denied, unable to write {}"
		]
	];
	
}

?>