<?php

namespace Yume\Fure\Locale\DateTime;

use DateTimeZone As BaseDateTimeZone;

use Yume\Fure\Locale;

/*
 * DateTimeZone
 *
 * @package Yume\Fure\Locale\DateTime
 *
 * @extends BaseDateTimeZone (DateTimeZone)
 */
class DateTimeZone extends BaseDateTimeZone
{
	
	/*
	 * Construct method of class DateTimeZone.
	 *
	 * @access Public Initialize
	 *
	 * @params String $timezone
	 *
	 * @return Void
	 */
	public function __construct( ? String $timezone = Null )
	{
		parent::__construct( $timezone ?? Locale\Locale::getTimeZoneName() );
	}
	
}

?>