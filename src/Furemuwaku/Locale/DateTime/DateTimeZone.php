<?php

namespace Yume\Fure\Locale\DateTime;

use DateTimeZone As BaseDateTimeZone;

use Yume\Fure\Locale;
use Yume\Fure\Util\Env;

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
	 * @inherit BaseDateTimeZone
	 *
	 */
	public function __construct( ? String $timezone = Null )
	{
		// Check if environment variable is exists.
		if( Env\Env::isset( "LOCALE_DATE_TIMEZONE" ) )
		{
			// Get timezone from environment variable set.
			$timezone = Env\Env::get( "LOCALE_DATE_TIMEZONE" );
		}
		else {
			$timezone = Locale\Locale::getDefaultTimezone();
		}
		
		// Call parent constructor.
		parent::__construct( $timezone );
	}
}

?>