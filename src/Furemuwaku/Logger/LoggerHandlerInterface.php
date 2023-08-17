<?php

namespace Yume\Fure\Logger;

/*
 * LoggerHandlerInterface
 * 
 * @package Yume\Fure\Logger
 */
interface LoggerHandlerInterface
{

	public function allow( LoggerLevel $level ): Bool;
	
	public function handle( LoggerLevel $level, String $message ): Bool;
	
	public function setDateTimeFormat( String $format ): LoggerHandlerInterface;

}

?>