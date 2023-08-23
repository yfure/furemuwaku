<?php

namespace Yume\Fure\Error\Erahandora;

use Throwable;

use Yume\Fure\Error;

/*
 * Erahandora
 * 
 * @package Yume\Fure\Error\Erahandora
 */
final class Erahandora
{

	/*
	 * Setup trigger error and exception handler.
	 *
	 * @access Public Static
	 *
	 * @return Void
	 */
	public static function setup(): Void
	{
		// Import error configuration.
		$config = config( "error" );

		// Trying to set handlers.
		set_error_handler( $config->handler->trigger );
		set_exception_handler( $config->handler->exception );
	}

	/*
	 * Handle uncaught exception thrown.
	 *
	 * @access Public Static
	 *
	 * @params Throwable $thrown
	 *
	 * @return Void
	 */
	public static function exception( Throwable $thrown ): Void
	{
		echo $thrown;
	}
	
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
	public static function trigger( Int $code, String $message, String $file, Int $line ): Void
	{
		throw new Error\TriggerError(
			message: $message,
			file: $file,
			line: $line,
			code: $code,
			level: match( $code )
			{
				E_ALL => "E_ALL",
				E_COMPILE_ERROR => "E_COMPILE_ERROR",
				E_COMPILE_WARNING => "E_COMPILE_WARNING",
				E_CORE_ERROR => "E_CORE_ERROR",
				E_CORE_WARNING => "E_CORE_WARNING",
				E_DEPRECATED => "E_DEPRECATED",
				E_ERROR => "E_ERROR",
				E_NOTICE => "E_NOTICE",
				E_PARSE => "E_PARSE",
				E_RECOVERABLE_ERROR => "E_RECOVERABLE_ERROR",
				E_STRICT => "E_STRICT",
				E_USER_DEPRECATED => "E_USER_DEPRECATED",
				E_USER_ERROR => "E_USER_ERROR",
				E_USER_NOTICE => "E_USER_NOTICE",
				E_USER_WARNING => "E_USER_WARNING",
				E_WARNING => "E_WARNING",
				
				default => "E_UNKNOWN"
			}
		);
	}

}

?>