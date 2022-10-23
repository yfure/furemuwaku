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
	 * Access Denied.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const ACCESS_ERROR = 8640;
	
	/*
	 * File is not executable.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const EXEC_ERROR = 8643;
	
	/*
	 * File or directory is not readable.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const READ_ERROR = 8646;
	
	/*
	 * File or directory is not writeable.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const WRITE_ERROR = 8649;
	
	/*
	 * @inherit Yume\Fure\Error\BaseError
	 *
	 */
	protected Array $flags = [
		8640 => "Permission denied for `{}`.",
		8643 => "Can't execute file `{}`.",
		8646 => "Unable to read file or directory `{}`.",
		8649 => "Unable to write file or make directory `{}`."
	];
	
}

?>