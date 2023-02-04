<?php

namespace Yume\Fure\Error\Handler;

use Yume\Fure\Util\Env;

/*
 * Handler
 *
 * @package Yume\Fure\Error\Handler
 */
abstract class Handler
{
	
	/*
	 * Return error handler function|method name.
	 *
	 * @access Public Static
	 *
	 * @return String
	 */
	public static function getErrorHandler(): String
	{
		// Check if application has error handler.
		if( Env\Env::isset( "ERROR_HANDLER" ) )
		{
			return( Env\Env::get( "ERROR_HANDLER" ) );
		}
		return( config( "error.error" ) );
	}
	
	/*
	 * Return exception handler function|method name.
	 *
	 * @access Public Static
	 *
	 * @return String
	 */
	public static function getExceptionHandler(): String
	{
		// Check if application has no exception handler.
		if( Env\Env::isset( "EXCEPTION_HANDLER" ) )
		{
			return( Env\Env::get( "EXCEPTION_HANDLER" ) );
		}
		return( config( "error.exception" ) );
	}
	
	/*
	 * Set application handlers.
	 *
	 * @access Public Static
	 *
	 * @return Void
	 */
	public static function setup(): Void
	{
		// Sets a user-defined error & exception handler function.
		set_error_handler( self::getErrorHandler() );
		set_exception_handler( self::getExceptionHandler() );
	}
	
}

?>