<?php

namespace Yume\Fure\Logger;

use Yume\Fure\Error;

/*
 * LoggerError
 *
 * @package Yume\Fure\Logger
 *
 * @extends Yume\Fure\Error\TypeError
 */
class LoggerError extends Error\TypeError
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
			self::HANDLER_ERROR,
			self::LEVEL_ERROR
		]
	];
	
}

?>