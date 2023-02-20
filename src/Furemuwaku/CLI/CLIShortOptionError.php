<?php

namespace Yume\Fure\CLI;

use Throwable;

/*
 * CLIShortOptionError
 *
 * @package Yume\Fure\CLI
 *
 * @extends Yume\Fure\CLI\CLIError
 */
class CLIShortOptionError extends CLIError
{
	/*
	 * @inherit Yume\Fure\CLI\CLIError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::SHORT_OPTION_ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( $message, $code, $previous );
	}
}

?>