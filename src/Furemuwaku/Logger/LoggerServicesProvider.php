<?php

namespace Yume\Fure\Logger;

use Yume\Fure\Support\Services;

/*
 * LoggerServicesProvider
 *
 * @package Yume\Fure\Logger
 *
 * @extends Yume\Fure\Support\ServicesProvider
 */
class LoggerServicesProvider extends Services\ServicesProvider
{
	
	/*
	 * @inherit Yume\Fure\Support\ServicesProvider
	 *
	 */
	public function register(): Void
	{
		$this->bind( [ "Logger", Logger::class ], new Logger, False );
	}
	
}

?>