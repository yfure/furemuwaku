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
class DateTimeImmutable extends BaseDateTimeImmutable {
	use \Yume\Fure\Locale\DateTime\DateTimeDecorator;
}

?>