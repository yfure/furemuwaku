<?php

namespace Yume\Fure\CLI\Command;

use Throwable;

/*
 * CommandUnitializeNameError
 * 
 * @extends Yume\Fure\CLI\Command\CommandError
 * 
 * @package Yume\Fure\CLI\Command
 */
final class CommandUnitializeNameError extends CommandError {

	/*
	 * @inherit Yume\Fure\Error\YumeError::__construct
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::UNITIALIZE_NAME_ERROR, ? Throwable $previous = Null, ? String $file = Null, ? Int $line = Null ) {
		parent::__construct( $message, $code, $previous, $file, $line );
	}
}

?>