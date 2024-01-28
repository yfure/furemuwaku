<?php

namespace Yume\Fure\CLI\Command;

use Throwable;

/*
 * CommandNotFoundError
 * 
 * @extends Yume\Fure\CLI\Command\CommandError
 * 
 * @pacakge Yume\Fure\CLI\Command
 */
final class CommandNotFoundError extends CommandError {
	
	/*
	 * @inherit Yume\Fure\Error\YumeError::__construct
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::NOT_FOUND_ERROR, ? Throwable $previous = Null, ? String $file = Null, ? Int $line = Null ) {
		parent::__construct( $message, $code, $previous, $file, $line );
	}
}

?>