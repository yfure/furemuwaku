<?php

namespace Yume\Fure\Locale\DateTime;

use DateTimeZone As BaseDateTimeZone;

/*
 * DateTimeDecorator
 *
 * @package Yume\Fure\Locale\DateTime
 */
trait DateTimeDecorator
{
	
	/*
	 * Construct method of class DateTime.
	 *
	 * @access Public Initialize
	 *
	 * @params String $datetime
	 * @params BaseDateTimeZone $timezone
	 *
	 * @return Void
	 */
	final public function __construct( ? String $datetime = Null, ? BaseDateTimeZone $timezone = Null )
	{
		parent::__construct( $datetime ?? "now", $timezone ?? new DateTimeZone );
	}
	
}

?>