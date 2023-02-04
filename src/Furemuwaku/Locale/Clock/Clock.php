<?php

namespace Yume\Fure\Locale\Clock;

use DateTimeImmutable;

/*
 * Clock
 *
 * @package Yume\Fure\Locale\Clock
 */
class Clock implements ClockInterface
{
	
	/*
	 * @inherit Yume\Fure\Locale\Clock\ClockInterface
	 *
	 */
	public function now(): DateTimeImmutable
	{
		return( new DateTimeImmutable() );
	}
	
}

?>