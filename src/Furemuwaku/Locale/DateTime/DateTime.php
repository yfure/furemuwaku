<?php

namespace Yume\Fure\Locale\DateTime;

use DateTime As BaseDateTime;

/*
 * DateTime
 *
 * @package Yume\Fure\Locale\DateTime
 *
 * @extends BaseDateTime (DateTime)
 */
class DateTime extends BaseDateTime
{
	use \Yume\Fure\Locale\DateTime\DateTimeDecorator;
}

?>