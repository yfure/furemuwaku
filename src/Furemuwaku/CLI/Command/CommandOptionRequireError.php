<?php

namespace Yume\Fure\CLI\Command;

use Throwable;

/*
 * CommandOptionRequireError
 * 
 * @extends Yume\Fure\CLI\Command\CommandError
 * 
 * @pacakge Yume\Fure\CLI\Command
 */
final class CommandOptionRequireError extends CommandError
{
	/*
	 * @inherit Yume\Fure\Error\YumeError::__construct
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::OPTION_REQUIRE_ERROR, ? Throwable $previous = Null, ? String $file = Null, ? Int $line = Null )
	{
		parent::__construct( $message, $code, $previous, $file, $line );
	}
}

?>