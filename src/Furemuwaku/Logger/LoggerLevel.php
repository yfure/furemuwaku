<?php

namespace Yume\Fure\Logger;

/*
 * Level
 *
 * @package Yume\Fure\Logger
 */
enum LoggerLevel: Int {
	
	use \Yume\Fure\Util\Backed;
	
	/*
	 * Action must be taken immediately, like
	 * when an entire website is down, the
	 * database unavailable, etc.
	 *
	 * @values Int
	 */
	case Alert = 172728;
	
	/*
	 * Critical conditions, like an application
	 * component not available, or an unexpected
	 * exception.
	 *
	 * @values Int
	 */
	case Critical = 273826;
	
	/*
	 * Detailed debug information.
	 *
	 * @values Int
	 */
	case Debug = 362728;
	
	/*
	 * The system is unusable.
	 *
	 * @values Int
	 */
	case Emergency = 468228;
	
	/*
	 * Runtime errors that do not require immediate
	 * action but should typically be logged and monitored.
	 *
	 * @values Int
	 */
	case Error = 582925;
	
	/*
	 * Interesting events in your application,
	 * like a user logging in, logging SQL queries, etc.
	 *
	 * @values Int
	 */
	case Info = 619352;
	
	/*
	 * Normal, but significant events in your application.
	 *
	 * @values Int
	 */
	case Notice = 799622;
	
	/*
	 * Exceptional occurrences that are not errors,
	 * like the use of deprecated APIs, poor use of
	 * an API, or other undesirable things that are
	 * not necessarily wrong.
	 *
	 * @values Int
	 */
	case Warning = 865271;
	
}

?>