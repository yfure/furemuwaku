<?php

namespace Yume\Fure\Event;

/*
 * EventStoppableInterface
 * 
 * @source https://github.com/php-fig/event-dispatcher
 * 
 * @package Yume\Fure\Event
 */
interface EventStoppableInterface {

	/*
	 * Return whether the event is propagation stopped.
	 * 
	 * @access Public
	 * 
	 * @return Bool
     */
    public function isPropagationStopped(): Bool;

}

?>