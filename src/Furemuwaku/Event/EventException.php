<?php

namespace Yume\Fure\Event;

use Throwable;

/*
 * EventException
 * 
 * @package Yume\Fure\Event
 */
final class EventException {

	/*
	 * Construct method of class EventException.
	 * 
	 * @access Public Initialize
	 * 
	 * @params Public Readonly Throwable $throwned
	 * 
	 * @return Void
	 */
	public function __construct( public Readonly Throwable $throwned ) {
	}

}