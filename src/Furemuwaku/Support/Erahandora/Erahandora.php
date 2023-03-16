<?php

namespace Yume\Fure\Support\Erahandora;

use Throwable;

use Yume\Fure\CLI;
use Yume\Fure\Error;
use Yume\Fure\Support\Design;
use Yume\Fure\Util\File;
use Yume\Fure\Util\File\Path;
use Yume\Fure\Util\Reflect;

/*
 * Erahandora (Error Handler)
 *
 * @package Yume\Fure\Support\Erahandora
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
		$trace['file']['file'] = preg_replace( "/(\/|\.|\-)/", "\x1b[1;38;5;111m$1\x1b[1;38;5;250m", $trace['file']['file'] );
		
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
			$file = $_SERVER['PHP_SELF'];
			$format = "\x1b[1;32m\x7b\x7d\x1b[1;38;5;214m\x3a\x20\x1b[1;38;5;190m\x7b\x6f\x62\x6a\x65\x63\x74\x2e\x63\x6c\x61\x73\x73\x7d\x1b[1;38;5;214m\x3a\x20\x1b[1;37m\x7b\x6d\x65\x73\x73\x61\x67\x65\x7d\x20\x69\x6e\x20\x66\x69\x6c\x65\x20\x1b[1;38;5;250m\x7b\x66\x69\x6c\x65\x2e\x66\x69\x6c\x65\x7d\x20\x1b[00m\x1b[1;37m\x6f\x6e\x20\x6c\x69\x6e\x65\x20\x1b[1;31m\x7b\x66\x69\x6c\x65\x2e\x6c\x69\x6e\x65\x7d\x1b[00m";
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
			
			$view = config( "error[exception.view]" );
			
			$error = $sutakku->getTrace();
			$error = new \Yume\Fure\Support\Data\Data( $error );
			
			if( File\File::exists( $path = Path\Paths::StorageView->path( f( "{}.php", $view ) ) ) )
			{
				$error->session = $_SESSION ?? [];
				$error->request = $_REQUEST ?? [];
				$error->server = $_SERVER ?? [];
				$error->cookie = $_COOKIE ?? [];
				$error->include = [
					
					// Gets the current include_path configuration option value.
					"path" => @get_include_path() ?: "Something wrong",
					
					// Grouping included files.
					"files" => File\File::group( get_included_files(), True )
				];
				$error->memory = [
					"peak" => memory_get_peak_usage( True ),
					"usage" => memory_get_usage( True )
				];
				( function() use( $error, $path )
				{
					include $path;
				})();
			}
			else {
				puts( "<pre>{}</pre>", $error );
			}
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