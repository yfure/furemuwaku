<?php

namespace Yume\Fure\Support\Event;

use Closure;

/*
 * EventInterface
 *
 * @package Yume\Fure\Support\Event
 */
interface EventInterface
{
	
	/*
	 * Adds an event handler to the event.
	 *
	 * @access Public
	 *
	 * @params Callable $handler
	 *  The event handler to add.
	 *
	 * @params Int $priority
	 *  The priority of the handler.
	 *  Higher values are executed first.
	 *
	 * @return Void
	 */
	public function addHandler( Closure $handler, Int $priority = 0 ): Void;
	
	/*
	 * Removes an event handler from the event.
	 *
	 * @access Public
	 *
	 * @params Closure $handler
	 *  The event handler to remove.
	 *
	 * @return Void
	 */
	public function removeHandler( Closure $handler ): Void;
	
	/*
	 * Dispatches the event.
	 *
	 * Calling all event handlers in order of priority.
	 *
	 * @access Public
	 *
	 * @params Mixed ...$args
	 *  Arguments to pass to the event handlers.
	 *
	 * @return Array
	 *  Returns an Array of results from the event handlers,
	 * @return False
	 *  Return False if event propagation was halted.
	 */
	public function dispatch( Mixed ...$args ): Array | False;
	
}

?>