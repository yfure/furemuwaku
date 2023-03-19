<?php

/*
 * Yume Logger Helpers.
 *
 * @include logger
 */

use Yume\Fure\Logger;
use Yume\Fure\Services;

/*
 * Write new log or get Logger instance class.
 *
 * @params Int|String|Yume\Fure\Logger\LoggerLevel
 * @params String $message
 * @params Array $context
 *
 * @return Yume\Fure\Logger\LoggerInterface
 */
function logger( Int | Null | String | Logger\LoggerLevel $level = Null, ? String $message = Null, ? Array $context = Null ): ? Logger\LoggerInterface
{
	if( Services\Services::available( "logger", False ) )
	{
		Services\Services::register( "logger", new Logger\Logger(), False );
	}
	if( $level !== Null && $message !== Null && $context !== Null )
	{
		return( Services\Services::get( "logger" ) )->log( $level, $message, $context );
	}
	return( Services\Services::get( "logger" ) );
}

?>