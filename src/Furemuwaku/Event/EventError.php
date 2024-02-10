<?php

namespace Yume\Fure\Event;

use Yume\Fure\Error;

/*
 * EventError
 * 
 * @extends Yume\Fure\Error\RuntimeError
 * 
 * @package Yume\Fure\Event
 */
final class EventError extends Error\RuntimeError {

	public const INVALID_EVENT = 873653;

	/*
	 * @inherit Yume\Fure\Error\YumeError::$flags
	 * 
	 */
	protected Array $flags = [
		EventError::class => [
			self::INVALID_EVENT => "Event listeners must have at least one event"
		]
	];
	
}

?>