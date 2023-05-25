<?php

namespace Yume\Fure\Locale\Clock;

use DateTimeImmutable;

use Yume\Fure\Locale\DateTime;

/*
 * Clock
 *
 * @package Yume\Fure\Locale\Clock
 */
class Clock implements ClockInterface
{
	
	/*
	 * @inherit Yume\Fure\Locale\Clock\ClockInterface::now
	 *
	 */
	public function now(): DateTimeImmutable
	{
		return( new DateTime\DateTimeImmutable );
	}
	
}

?>