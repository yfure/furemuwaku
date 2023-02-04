<?php

namespace Yume\Fure\Error\Handler;

use Yume\Fure\Error;

/*
 * ErrorHandler
 *
 * @package Yume\Fure\Error\Handler
 */
abstract class ErrorHandler
{
	
	/*
	 * Trigger error handler.
	 *
	 * @access Public Static
	 *
	 * @params Int $code
	 * @params String $message
	 * @params String $file
	 * @params Int $line
	 *
	 * @return Void
	 */
	public static function handler( Int $code, String $message, String $file, Int $line ): Void
	{
		throw new Error\TriggerError(
			message: $message,
			file: $file,
			line: $line,
			code: $code,
			level: match( $code )
			{
				E_ERROR => "E_ERROR",
				E_WARNING => "E_WARNING",
				E_PARSE => "E_PARSE",
				E_NOTICE => "E_NOTICE",
				E_CORE_ERROR => "E_CORE_ERROR",
				E_CORE_WARNING => "E_CORE_WARNING",
				E_COMPILE_ERROR => "E_COMPILE_ERROR",
				E_COMPILE_WARNING => "E_COMPILE_WARNING",
				E_USER_ERROR => "E_USER_ERROR",
				E_USER_WARNING => "E_USER_WARNING",
				E_USER_NOTICE => "E_USER_NOTICE",
				E_STRICT => "E_STRICT",
				E_RECOVERABLE_ERROR => "E_RECOVERABLE_ERROR",
				E_DEPRECATED => "E_DEPRECATED",
				E_USER_DEPRECATED => "E_USER_DEPRECATED",
				E_ALL => "E_ALL",
				default => "E_UNKNOWN"
			}
		);
	}
	
}

?>