<?php

namespace Yume\Fure\Logger;

use Yume\Fure\Service;

/*
 * LoggerServiceProvider
 * 
 * @extends Yume\Fure\Service\ServiceProvider
 * 
 * @package Yume\Fure\Logger
 */
class LoggerServiceProvider extends Service\ServiceProvider implements Service\ServiceProviderInterface {

	/*
	 * Instance of class Logger.
	 * 
	 * @access Private
	 * 
	 * @values Yume\Fure\Logger\Logger
	 */
	private Logger $logger;

	/*
	 * Construct method of class LoggerServiceProver.
	 * 
	 * @access Public Initialize
	 * 
	 * @return Void
	 */
	public function __construct() {
		$this->logger = new Logger();
	}

	/*
	 * @inherit Yume\Fure\Support\ServiceProvider
	 *
	 */
	public function register(): Void {
		$this->bind( [ "logger", Logger::class ], $this->logger, True );
	}

}

?>