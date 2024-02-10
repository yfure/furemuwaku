<?php

namespace Yume\Fure\Event;

use Yume\Fure\Util\Reflect;

/*
 * EventDispatcher
 * 
 * Event Dispatcher Implementation.
 * 
 * @package Yume\Fure\Event
 */
final class EventDispatcher implements EventDispatcherInterface {

	/*
	 * Construct method of class EventDispatcher.
	 * 
	 * @access Public Initialize
	 * 
	 * @params Private Readonlu Yume\Fure\Event\EventListenerProviderInterface $provider
	 * 
	 * @return Void
	 */
	public function __construct( private Readonly EventListenerProviderInterface $provider ) {
	}

	/*
	 * @inherit Yume\Fure\Event\EventDispatcherInterface->dispatch
	 * 
	 */
	public function dispatch( Object $event ): Object {
		foreach( $this->provider->getListenersForEvent( $event ) As $listener ) {
			if( $event Instanceof EventStoppableInterface && $event->isPropagationStopped() ) {
				break;
			}
			Reflect\ReflectFunction::invoke( $listener, [$event] );
		}
		return $event;
	}
}

?>