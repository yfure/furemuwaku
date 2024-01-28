<?php

namespace Yume\Fure\CLI;

use Yume\Fure\Error;

/*
 * CLIError
 *
 * @package Yume\Fure\CLI
 *
 * @extends Yume\Fure\Error\RuntimeError
 */
class CLIError extends Error\RuntimeError {
	
	/*
	 * @inherit Yume\Fure\Error\RuntimeError::$flags
	 *
	 */
	protected Array $flags = [
		CLIError::class => []
	];
	
}

?>