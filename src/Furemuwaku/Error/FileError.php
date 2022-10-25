<?php

namespace Yume\Fure\Error;

/*
 * FileError
 *
 * @extends Yume\Fure\Error\PermissionError
 *
 * @package Yume\Fure\Error
 */
class FileError extends PermissionError
{
	
	/*
	 * No such file.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const FILE_ERROR = 8511;
	
	/*
	 * File open invalid mode.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const MODE_ERROR = 8513;
	
	/*
	 * File name is invalid.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const NAME_ERROR = 8514;
	
	public const OPEN_ERROR = 8515;
	
	/*
	 * No such path or directory.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const PATH_ERROR = 8516;
	
	/*
	 * Not file.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const TYPE_ERROR = 8518;
	
	/*
	 * @inherit Yume\Fure\Error\PermissionError
	 *
	 */
	protected Array $flags = [
		8643 => "Can't execute file `{}`.",
		8646 => "Unable to read file `{}`.",
		8649 => "Unable to write file `{}`.",
		8511 => "No such file `{}`",
		8513 => "Invalid file mode for `{}`: `{}`",
		8514 => "Invalid file name for `{}`.",
		8515 => "Failed open file `{}`.",
		8516 => "No such path or directory `{}`",
		8518 => "Unable to open `{}` because it is not a file."
	];
	
}

?>