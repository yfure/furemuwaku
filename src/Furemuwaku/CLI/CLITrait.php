<?php

namespace Yume\Fure\CLI;

use Yume\Fure\CLI\Command;

/*
 * CLITrait
 *
 * @package Yume\Fure\CLI
 */
trait CLITrait
{
	
	/*
	 * Colorize string.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 * @params String $base
	 *
	 * @return String
	 */
	public static function color( String $string, String $base = "\x1b[0m" ): String
	{
		// ...
	}
	
}

?>