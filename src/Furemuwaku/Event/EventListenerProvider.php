<?php

namespace Yume\Fure\Event;

use Closure;
use Iterator;

/*
 * EventListenerProvider
 *
 * The ListenerProvider class implements the ListenerProviderInterface from the PHP FIG event dispatcher package.
 * It manages a collection of event listeners and provides them to the EventDispatcher on demand.
 *
 * @package Yume\Fure\Event
 */
class EventListenerProvider implements ListenerProviderInterface
{
	
	/*
	 * An associative array of event listeners, keyed by event name.
	 *
	 * Each value is an array of Closure<listeners> that should be
	 * called when the corresponding event is dispatched.
	 *
	 * @access Private
	 *
	 * @values Array
	 */
	private Array $listeners = [];
	
	/*
	 * Adds a listener to the specified event.
	 *
	 * @access Public
	 *
	 * @params string $eventName
	 *  The name of the event to attach the listener to.
	 * @params Closure $listener
	 *  The listener to attach to the event.
	 *
	 * @return void
	 */
	public function addListener( string $eventName, Closure $listener ): void
	{
		if( isset( $this->listeners[$eventName] )=== False )
		{
			$this->listeners[$eventName] = [];
		}
		$this->listeners[$eventName][] = $listener;
	}

	/*
	 * Retrieves an iterator for the listeners that should be
	 * called for the specified event.
	 *
	 * @access Public
	 *
	 * @params object $event
	 *  The event to retrieve listeners for.
	 *
	 * @return Iterator
	 *  An iterable collection of Closures( listeners ).
	 */
	public function getListenersForEvent( object $event ): Iterator
	{
		$eventName = $event instanceof Event ? $event->getName(): get_class( $event );

		$listeners = $this->listeners[$eventName] ?? [];
		$inheritedListeners = [];

		// Check for inherited listeners.
		while( $pos = strrpos( $eventName, "." ) )
		{
			$eventName = substr( $eventName, 0, $pos );
			
			if( isset( $this->listeners[$eventName] ) )
			{
				$inheritedListeners = array_merge( $inheritedListeners, $this->listeners[$eventName] );
			}
		}

		// Merge the inherited and direct listeners.
		return( array_merge( $inheritedListeners, $listeners ) );
	}

	/*
	 * Removes a listener from the specified event.
	 *
	 * @access Public
	 *
	 * @params string   $eventName The name of the event to remove the listener from.
	 * @params Closure $listener  The listener to remove from the event.
	 *
	 * @return void
	 */
	public function removeListener( string $eventName, Closure $listener ): void
	{
		if( isset( $this->listeners[$eventName] ) )
		{
			foreach( $this->listeners[$eventName] as $key => $value )
			{
				if( $value === $listener )
				{
					unset( $this->listeners[$eventName][$key] );
				}
			}
		}
	}
	
	/*
	 * Retrieves the number of listeners for the specified event.
	 *
	 * @access Public
	 *
	 * @params string $eventName The name of the event to retrieve the listener count for.
	 *
	 * @return int The number of listeners for the specified event.
	 */
	public function getListenerCount( string $eventName ): int
	{
		return( count( $this->listeners[$eventName] ?? [] ) );
	}
	
}

?>