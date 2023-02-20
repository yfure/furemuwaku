<?php

namespace Yume\Fure\CLI;

use Throwable;

/*
 * CLIJsonValueError
 *
 * @package Yume\Fure\CLI
 *
 * @extends Yume\Fure\CLI\CLIError
 */
class CLIJsonValueError extends CLIError
{
	/*
	 * @inherit Yume\Fure\CLI\CLIError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::JSON_VALUE_ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( $message, $code, $previous );
	}
}

?>