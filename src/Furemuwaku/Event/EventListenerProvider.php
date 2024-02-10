<?php

namespace Yume\Fure\Event;

use Yume\Fure\Util\Reflect;

/*
 * EventListenerProvider
 * 
 * Event Listener Provider Implementation.
 * 
 * @package Yume\Fure\Event
 */
final class EventListenerProvider implements EventListenerProviderInterface {

	/*
	 * Construct method of class EventListenerProvider.
	 * 
	 * @access Public Initialize
	 * 
	 * @params Private Readonly Yume\Fure\Event\EventListener $listeners
	 * 
	 * @return Void
	 */
	public function __construct( private Readonly EventListener $listeners ) {
	}

	/*
	 * @inherit Yume\Fure\Event\EventListenerProvider->getListenersForEvent
	 * 
	 */
	public function getListenersForEvent( Object $event ): Iterable {
		yield from $this->listeners->getEvents( $event::class );
		yield from $this->listeners->getEvents( ...[
			...Reflect\ReflectClass::getParentClasses( $event, $reflect ),
			...Reflect\ReflectClass::getInterfaceNames( $event, $reflect )
		]);
	}
	
}

?>