<?php

namespace Yume\Fure\Event;

/*
 * EventDispatcherInterface
 * 
 * @source https://github.com/php-fig/event-dispatcher
 * 
 * @package Yume\Fure\Event
 */
interface EventDispatcherInterface {

	/*
	 * Provide all relevant listeners with an event to process.
	 * 
	 * @access Public
	 * 
	 * @params Object $event
	 * 
	 * @return Object
	 */
	public function dispatch( Object $event ): Object;

}

?>