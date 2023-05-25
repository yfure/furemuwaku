<?php

namespace Yume\Fure\Locale\DateTime;

use DateTimeImmutable as BaseDateTimeImmutable;

/*
 * DateTimeImmutable
 *
 * @extends BaseDateTimeImmutable (DateTimeImmutable)
 *
 * @package Yume\Fure\Locale\DateTime
 */
class DateTimeImmutable extends BaseDateTimeImmuable
{
	use \Yume\Fure\Locale\DateTimeDecorator;
}

?>