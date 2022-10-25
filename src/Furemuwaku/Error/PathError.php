<?php

namespace Yume\Fure\Error;

/*
 * PathError
 *
 * @extends Yume\Fure\Error\PermissionError
 *
 * @package Yume\Fure\Error
 */
class PathError extends PermissionError
{
	
	/*
	 * If no such path or directory.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const PATH_ERROR = 8630;
	
	/*
	 * @inherit Yume\Fure\Error\PermissionError
	 *
	 */
	protected Array $flags = [
		8646 => "Unable to scan directory `{}`.",
		8649 => "Unable to write direcrory `{}`.",
		8630 => "No such path or directory `{}`."
	];
	
}

?>