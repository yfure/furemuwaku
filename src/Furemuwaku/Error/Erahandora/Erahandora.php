<?php

namespace Yume\Fure\Error\Erahandora;

use Throwable;

use Yume\Fure\CLI;
use Yume\Fure\Error;
use Yume\Fure\Support\Design;
use Yume\Fure\Support\Reflect;

/*
 * Erahandora (Error Handler)
 *
 * @package Yume\Fure\Error\Erahandora
 *
 * @extends Yume\Fure\Support\Design\Singleton
 */
class Erahandora extends Design\Singleton
{
	
	/*
	 * @inherit Yume\Fure\Support\Design\Singleton
	 *
	 */
	protected function __construct( Bool $setup = False )
	{
		if( $setup )
		{
			$this->setup();
		}
	}
	
	/*
	 * Setup error and exception handler.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function setup(): Void
	{
		set_error_handler( config( "error" )->handler->trigger );
		set_exception_handler( config( "error" )->handler->exception );
	}
	
	private static function colorize( Array $trace ): Array
	{
		// ...
		$trace['object']['class'] = preg_replace( "/(\\\)/", "\x1b[1;38;5;111m$1\x1b[1;38;5;190m", $trace['object']['class'] );
		$trace['file']['file'] = preg_replace( "/(\/|\.|\-)/", "\x1b[0m\x1b[1;38;5;111m$1\x1b[2;37m", $trace['file']['file'] );
		
		return( $trace );
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
		// Default stack is exception thrown.
		$stack = $thrown;
		
		// If exception thrown has previous.
		if( $stack->getPrevious() !== Null )
		{
			// Exception class previous.
			$stack = [ $stack::class => $stack ];
			
			// Main exception class thrown.
			$prevs = $thrown;
			
			do
			{
				$stack[$prevs::class] = $prevs;
			}
			while( $prevs = $prevs->getPrevious() );
		}
		
		// Create new ErahandoraSuttaku instance.
		$sutakku = new ErahandoraSutakku( $stack );
		$sutakku->build();
		
		// Get builded trace.
		$trace = $sutakku->getTrace();
		$previouses = $trace['object']['previous'];
		
		// Check if application running on CLI mode.
		if( YUME_CONTEXT_CLI )
		{
			$file = CLI\CLI::self()->argument->file;
			$format = "\x1b[1;32m{}\x1b[1;38;5;214m: \x1b[1;38;5;190m{object.class}\x1b[1;38;5;214m: \x1b[1;37m{message} in file \x1b[2;37m{file.file} \x1b[00m\x1b[1;37mon line \x1b[1;31m{file.line}\x1b[00m";
			$outputs = [];
			
			// If exception thrown has previous.
			if( $previouses !== [] )
			{
				/*
				 * Reserve array order.
				 *
				 * This is done so that the developer knows which
				 * part of the program threw the exception first.
				 *
				 */
				$previouses = array_reverse( $previouses );
				
				foreach( $previouses As $class => $previous )
				{
					$outputs[] = f( $format, $file, ...self::colorize( $previous ) );
				}
			}
			puts( "\n{}\n\n", implode( "\n\n", [ ...$outputs, f( $format, $file, ...self::colorize( $trace ) ) ] ) );
		}
		else {
			puts( "{}", $sutakku->getTrace() );
			//echo view( config( "error" )->exception->view->html, $sutakku->getTrace() );
		}
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