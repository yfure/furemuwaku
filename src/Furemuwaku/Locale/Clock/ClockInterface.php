<?php

namespace Yume\Fure\Locale\Clock;

use Yume\Fure\Locale\DateTime;

/*
 * ClockInterface
 *
 * @package Yume\Fure\Locale\Clock
 */
interface ClockInterface {
	
	/*
	 * Returns the current time as a DateTimeImmutable Object.
	 *
	 * @access Public
	 *
	 * @return DateTimeImmutable
	 */
	public function now(): DateTime\DateTimeImmutable;
	
}

?>