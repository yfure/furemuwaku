<?php

namespace Yume\Fure\Logger;

use Yume\Fure\Error;

/*
 * LoggerError
 *
 * @extends Yume\Fure\Error\IOError
 *
 * @package Yume\Fure\Logger
 */
class LoggerError extends Error\IOError
{
	
	/*
	 * Error const when no handler for Logger.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const HANDLER_ERROR = 28292;
	
	/*
	 * Error const for invalid log level.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const LEVEL_ERROR = 71428;
	
	/*
	 * @inherit Yume\Fure\Error\TypeError
	 *
	 */
	protected Array $flags = [
		LoggerError::class => [
			self::HANDLER_ERROR => "Logger does not have any handler",
			self::LEVEL_ERROR => "{+:ucfirst} is an invalid log level"
		]
	];
	
}

?>