<?php

namespace Yume\Fure\Logger;

use Yume\Fure\Services;

/*
 * LoggerServiceProvider
 *
 * @package Yume\Fure\Logger
 *
 * @extends Yume\Fure\Services\ServiceProvider
 */
class LoggerServiceProvider extends Services\ServiceProvider
{
	
	/*
	 * @inherit Yume\Fure\Support\ServiceProvider
	 *
	 */
	public function register(): Void
	{
		$this->bind( [ "Logger", Logger::class ], new Logger, False );
	}
	
}

?>