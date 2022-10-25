<?php

namespace Yume\Fure\Error;

use Throwable;

/*
 * EnvError
 *
 * @extends Yume\Fure\Error\SyntaxError
 *
 * @package Yume\Fure\Error
 */
class EnvError extends SyntaxError
{
	
	/*
	 * If environment file not found.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const FILE_ERROR = 13462;
	
	/*
	 * @inherit Yume\Fure\Error\SyntaxError
	 *
	 */
	protected Array $flags = [
		13720 => "There is an error in writing the code.",
		13722 => "Unexpected token `{}`.",
		13462 => "File environment `{}` not found."
	];
	
}

?>