<?php

namespace Yume\Fure\Event;

use Closure;

/*
 * EventDispatcher
 *
 * The EventDispatcher class dispatches events to registered listeners.
 * It implements the EventDispatcherInterface from the PHP FIG event dispatcher package.
 * It acts as a mediator between the event objects and their respective listeners.
 *
 * @package Yume\Fure\Event
 */
class EventDispatcher implements EventDispatcherInterface
{
    private $listenerProvider;

    public function __construct(ListenerProviderInterface $listenerProvider)
    {
        $this->listenerProvider = $listenerProvider;
    }

    public function dispatch(object $event): object
    {
        foreach ($this->listenerProvider->getListenersForEvent($event) as $listener) {
            $listener($event);
            if ($event->isPropagationStopped()) {
                break;
            }
        }
        return $event;
    }

    public function dispatchMany(iterable $events): void
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    public function dispatchEventName(string $eventName, array $data = []): object
    {
        $event = new Event($eventName, $data);
        return $this->dispatch($event);
    }

    public function clearListeners(): void
    {
        $this->listenerProvider = new ListenerProvider();
    }
    
}

<?php

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * An implementation of the PSR-14 EventDispatcherInterface that delegates event dispatching to
 * a ListenerProviderInterface instance.
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * The ListenerProviderInterface instance used to retrieve listeners for events.
     *
     * @values ListenerProviderInterface
     */
    private ListenerProviderInterface $listenerProvider;

    /**
     * Constructs an EventDispatcher instance with the given ListenerProviderInterface.
     *
     * @param ListenerProviderInterface $listenerProvider The ListenerProviderInterface instance to use
     *                                                     for retrieving listeners.
     */
    public function __construct(ListenerProviderInterface $listenerProvider)
    {
        $this->listenerProvider = $listenerProvider;
    }

    /**
     * Dispatches an event to all registered listeners.
     *
     * @param object $event The event object to dispatch.
     *
     * @return object The dispatched event object.
     */
    public function dispatch(object $event): object
    {
        foreach ($this->listenerProvider->getListenersForEvent($event) as $listener) {
            $listener($event);
        }

        return $event;
    }

    /**
     * Adds a listener for the specified event.
     *
     * @param string $eventName The name of the event to add the listener for.
     * @param Closure $listener The listener to add.
     */
    public function addListener(string $eventName, Closure $listener): void
    {
        $this->listenerProvider->addListener($eventName, $listener);
    }

    /**
     * Adds a subscriber object to the EventDispatcher.
     *
     * @param object $subscriber The subscriber object to add.
     */
    public function addSubscriber(object $subscriber): void
    {
        $this->listenerProvider->addSubscriber($subscriber);
    }

    /**
     * Removes a listener from the specified event.
     *
     * @param string $eventName The name of the event to remove the listener from.
     * @param Closure $listener The listener to remove.
     */
    public function removeListener(string $eventName, Closure $listener): void
    {
        $this->listenerProvider->removeListener($eventName, $listener);
    }

    /**
     * Removes a subscriber object from the EventDispatcher.
     *
     * @param object $subscriber The subscriber object to remove.
     */
    public function removeSubscriber(object $subscriber): void
    {
        $this->listenerProvider->removeSubscriber($subscriber);
    }
}


?>