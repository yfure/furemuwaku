<?php

namespace Yume\Fure\Logger;

/*
 * Level
 *
 * @package Yume\Fure\Logger
 */
enum LoggerLevel: String
{
	
	/*
	 * Action must be taken immediately, like
	 * when an entire website is down, the
	 * database unavailable, etc.
	 *
	 * @values String
	 */
	case ALERT = "ALERT";
	
	/*
	 * Critical conditions, like an application
	 * component not available, or an unexpected
	 * exception.
	 *
	 * @values String
	 */
	case CRITICAL = "CRITICAL";
	
	/*
	 * Detailed debug information.
	 *
	 * @values String
	 */
	case DEBUG = "DEBUG";
	
	/*
	 * The system is unusable.
	 *
	 * @values String
	 */
	case EMERGENCY = "EMERGENCY";
	
	/*
	 * Runtime errors that do not require immediate
	 * action but should typically be logged and monitored.
	 *
	 * @values String
	 */
	case ERROR = "ERROR";
	
	/*
	 * Interesting events in your application,
	 * like a user logging in, logging SQL queries, etc.
	 *
	 * @values String
	 */
	case INFO = "INFO";
	
	/*
	 * Normal, but significant events in your application.
	 *
	 * @values String
	 */
	case NOTICE = "NOTICE";
	
	/*
	 * Exceptional occurrences that are not errors,
	 * like the use of deprecated APIs, poor use of
	 * an API, or other undesirable things that are
	 * not necessarily wrong.
	 *
	 * @values String
	 */
	case WARNING = "WARNING";
	
}

?>