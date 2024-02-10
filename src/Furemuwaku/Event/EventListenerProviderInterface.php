<?php

namespace Yume\Fure\Event;

/*
 * EventListenerProviderInterface
 * 
 * @source https://github.com/php-fig/event-dispatcher
 * 
 * @package Yume\Fure\Event
 */
interface EventListenerProviderInterface {

	/*
	 * Return listeners for event.
	 * 
	 * @access Public
	 * 
	 * @params Object $event
	 * 
	 * @return Iterable
	 */
	public function getListenersForEvent( Object $event ): Iterable;

}

?>