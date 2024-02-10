<?php

namespace Yume\Fure\Error\Erahandora;

use Throwable;

use Yume\Fure\Error;

/*
 * Erahandora
 * 
 * @package Yume\Fure\Error\Erahandora
 */
final class Erahandora {

	/*
	 * Setup trigger error and exception handler.
	 *
	 * @access Public Static
	 *
	 * @return Void
	 */
	public static function setup(): Void {
		$config = config( "error" );
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
	public static function exception( Throwable $thrown ): Void {
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
	public static function trigger( Int $code, String $message, String $file, Int $line ): Void {
		throw new Error\TriggerError(
			message: $message,
			file: $file,
			line: $line,
			code: $code,
			level: match( $code ) {
				E_ALL => "All",
				E_COMPILE_ERROR => "CompileError",
				E_COMPILE_WARNING => "CompileWarning",
				E_CORE_ERROR => "CoreError",
				E_CORE_WARNING => "CoreWarning",
				E_DEPRECATED => "Deprecated",
				E_ERROR => "Error",
				E_NOTICE => "Notice",
				E_PARSE => "Parse",
				E_RECOVERABLE_ERROR => "RecoverableError",
				E_STRICT => "Strict",
				E_USER_DEPRECATED => "UserDeprecated",
				E_USER_ERROR => "UserError",
				E_USER_NOTICE => "UserNotice",
				E_USER_WARNING => "UserWarning",
				E_WARNING => "Warning",
				
				default => "E_UNKNOWN"
			}
		);
	}

}

?>
