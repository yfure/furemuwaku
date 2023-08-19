<?php

namespace Yume\Fure\CLI\Command;

use Throwable;

/*
 * CommandOptionValueError
 * 
 * @extends Yume\Fure\CLI\Command\CommandError
 * 
 * @pacakge Yume\Fure\CLI\Command
 */
final class CommandOptionValueError extends CommandError
{
	/*
	 * @inherit Yume\Fure\Error\YumeError::__construct
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::OPTION_VALUE_ERROR, ? Throwable $previous = Null, ? String $file = Null, ? Int $line = Null )
	{
		parent::__construct( $message, $code, $previous, $file, $line );
	}
}

?>