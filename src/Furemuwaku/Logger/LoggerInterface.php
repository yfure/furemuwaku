<?php

namespace Yume\Fure\Logger;

use Stringable;

/*
 * LoggerInterface
 *
 * @package Yume\Fure\Logger
 */
interface LoggerInterface
{
	
	/*
	 * Action must be taken immediately.
	 *
	 * @access Public
	 *
	 * @params String|Stringable $message
	 * @params Array<Mixed> $context
	 *
	 * @return Void
	 */
	public function alert( String|Stringable $message, Array $context = [] ): Void;
	
	/*
	 * Critical conditions.
	 *
	 * @access Public
	 *
	 * @params String|Stringable $message
	 * @params Array<Mixed> $context
	 *
	 * @return Void
	 */
	public function critical( String|Stringable $message, Array $context = [] ): Void;
	
	/*
	 * Detailed debug information.
	 *
	 * @access Public
	 *
	 * @params String|Stringable $message
	 * @params Array<Mixed> $context
	 *
	 * @return Void
	 */
	public function debug( String|Stringable $message, Array $context = [] ): Void;
	
	/*
	 * System is unusable.
	 *
	 * @access Public
	 *
	 * @params String|Stringable $message
	 * @params Array<Mixed> $context
	 *
	 * @return Void
	 */
	public function emergency( String|Stringable $message, Array $context = [] ): Void;
	
	/*
	 * Runtime errors that do not require immediate action but
	 * should typically be logged and monitored.
	 *
	 * @access Public
	 *
	 * @params String|Stringable $message
	 * @params Array<Mixed> $context
	 *
	 * @return Void
	 */
	public function error( String|Stringable $message, Array $context = [] ): Void;
	
	/*
	 * Interesting events.
	 *
	 * @access Public
	 *
	 * @params String|Stringable $message
	 * @params Array<Mixed> $context
	 *
	 * @return Void
	 */
	public function info( String|Stringable $message, Array $context = [] ): Void;
	
	/*
	 * Normal but significant events.
	 *
	 * @access Public
	 *
	 * @params String|Stringable $message
	 * @params Array<Mixed> $context
	 *
	 * @return Void
	 */
	public function notice( String|Stringable $message, Array $context = [] ): Void;
	
	/*
	 * Exceptional occurrences that are not errors.
	 *
	 * @access Public
	 *
	 * @params String|Stringable $message
	 * @params Array<Mixed> $context
	 *
	 * @return Void
	 */
	public function warning( String|Stringable $message, Array $context = [] ): Void;
	
	/*
	 * Logs with an arbitrary level.
	 *
	 * @access Public
	 *
	 * @params Int|String|Yume\Fure\Logger\LoggerLevel $level
	 * @params String|Stringable $message
	 * @params Array<Mixed> $context
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\Logger\LoggerError
	 */
	public function log( Int | String | LoggerLevel $level, String|Stringable $message, Array $context = [] ): Void;
	
}

?>