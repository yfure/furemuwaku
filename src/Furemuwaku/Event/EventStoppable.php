<?php

namespace Yume\Fure\Support\Event;

/*
 * EventStoppable
 *
 * The EventStoppable class represents an event that can be
 * dispatched by the EventDispatcher. It contains a name and
 * a payload, which can be any data.
 *
 * @package Yume\Fure\Event
 */
class EventStoppable implements StoppableEventInterface
{
	
	/*
	 * The name of the event.
	 *
	 * @values string
	 */
	private string $name;

	/*
	 * The payload associated with the event.
	 *
	 * @values array
	 */
	private array $payload;

	/*
	 * Indicates whether or not event propagation should be stopped.
	 *
	 * @values bool
	 */
	private bool $propagationStopped = false;

	/*
	 * Event constructor.
	 *
	 * @param string $name The name of the event.
	 * @param array $payload The payload associated with the event.
	 */
	public function __construct(string $name, array $payload = [])
	{
		$this->name = $name;
		$this->payload = $payload;
	}

	/*
	 * Returns the name of the event.
	 *
	 * @return string The name of the event.
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/*
	 * Returns the payload associated with the event.
	 *
	 * @return array The payload associated with the event.
	 */
	public function getPayload(): array
	{
		return $this->payload;
	}

	/*
	 * Sets the payload associated with the event.
	 *
	 * @param array $payload The payload associated with the event.
	 */
	public function setPayload(array $payload): void
	{
		$this->payload = $payload;
	}

	/*
	 * Adds to the payload associated with the event.
	 *
	 * @param array $payload The payload to add to the event.
	 */
	public function addPayload(array $payload): void
	{
		$this->payload = array_merge($this->payload, $payload);
	}

	/*
	 * Indicates whether or not event propagation should be stopped.
	 *
	 * @return bool Whether or not event propagation should be stopped.
	 */
	public function isPropagationStopped(): bool
	{
		return $this->propagationStopped;
	}

	/*
	 * Stops the propagation of the event.
	 */
	public function stopPropagation(): void
	{
		$this->propagationStopped = true;
	}
	
}

?>