<?php

namespace Yume\Fure\Locale\DateTime;

use DateTime As BaseDateTime;
use DateTimeZone As BaseDateTimeZone;

/*
 * DateTime
 *
 * @package Yume\Fure\Locale\DateTime
 *
 * @extends BaseDateTime (DateTime)
 */
class DateTime extends BaseDateTime
{
	/*
	 * @inherit BaseDateTime
	 *
	 */
	public function __construct( ? String $datetime = Null, ? BaseDateTimeZone $timezone = Null )
	{
		// Check if timezone is Null value.
		if( $timezone === Null )
			$timezone = new DateTimeZone();
		
		// Call parent constructor.
		parent::__construct( $datetime ?? "now", $timezone );
	}
}

?>