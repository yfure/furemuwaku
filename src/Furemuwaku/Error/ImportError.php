<?php

namespace Yume\Fure\Error;

/*
 * ImportError
 *
 * @extends Yume\Fure\Error\ModuleError
 *
 * @package Yume\Fure\Error
 */
class ImportError extends ModuleError
{
	
	public const SOMETHING_ERROR = 96337;
	
	/*
	 * @inherit Yume\Fure\Error\FileError
	 *
	 */
	protected Array $flags = [
		96337 => "Failed to import `{}` file, this usually happens because there is a code error in the file itself."
	];
	
}

?>