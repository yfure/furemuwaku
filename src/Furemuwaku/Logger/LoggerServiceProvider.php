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
final class LoggerServiceProvider extends Service\ServiceProvider implements Service\ServiceProviderInterface {

	/*
	 * Instance of class LoggerInterface.
	 * 
	 * @access Private Readonly
	 * 
	 * @values Yume\Fure\Logger\LoggerInterface
	 */
	private Readonly LoggerInterface $logger;

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
		$this->bind( [ "logger", Logger::class ], $this->logger, override: False );
	}

}

?>