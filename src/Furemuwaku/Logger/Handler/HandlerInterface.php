<?php

namespace Yume\Fure\Logger\Handler;

use Yume\Fure\Logger;

/*
 * HandlerInterface
 *
 * @package Yume\Fure\Logger\Handler
 */
interface HandlerInterface
{
	
	public function allow( Logger\LoggerLevel $level ): Bool;
	
	public function handle( Logger\LoggerLevel $level, String $message ): Bool;
	
	public function setDateTimeFormat( String $format ): HandlerInterface;
	
	
}

?>