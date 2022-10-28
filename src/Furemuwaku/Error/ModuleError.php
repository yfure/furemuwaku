<?php

namespace Yume\Fure\Error;

/*
 * ModuleError
 *
 * @extends Yume\Fure\Error\FileError
 *
 * @package Yume\Fure\Error
 */
class ModuleError extends FileError
{
	
	/*
	 * @inherit Yume\Fure\Error\FileError
	 *
	 */
	protected Array $flags = [
		FileError::NAME_ERROR => "No module named {}."
	];
	
}

?>