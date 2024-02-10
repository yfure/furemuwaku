<?php

namespace Yume\Fure\Event;

use ReflectionNamedType;
use ReflectionUnionType;
use Yume\Fure\Util\Arr;
use Yume\Fure\Util\Reflect;

/*
 * EventListener
 * 
 * @package Yume\Fure\Event
 */
final class EventListener {

	/*
	 * Instance of class Associative.
	 * 
	 * The associative must be insensitive case.
	 * 
	 * @access Private Readonly
	 * 
	 * @values Yume\Fure\Util\Arr\Associative
	 */
	private Readonly Arr\Associative $listeners;

	/*
	 * Construct method of class EventListener.
	 * 
	 * @access Public Initialize
	 * 
	 * @return Void
	 */
	public function __construct() {
		$this->listeners = new Arr\Associative( insensitive: True );
	}

	/*
	 * Append new listener.
	 * 
	 * @access Public
	 * 
	 * @params Callable|String $listener
	 * @params String ...$events
	 * 
	 * @return Yume\Fure\Event\EventListener
	 */
	public function append( Callable|String $listener, String ...$events ): EventListener {
		$instance = Clone $this;
		$instance->listeners = new Arr\Associative( $this->listeners, insensitive: True );
		if( valueIsEmpty( $events ) ) {
			$parameters = Reflect\ReflectFunction::getParameters( $listener );
			if( valueIsEmpty( $parameters ) ) {
				throw new EventError( [], EventError::INVALID_EVENT );
			}
			foreach( $parameters As $parameter ) {
				$parameterType = $parameter->getType();
				if( $parameterType Instanceof ReflectionNamedType ) {
					$events[] = $parameterType->getName();
				}
				if( $parameterType Instanceof ReflectionUnionType ) {
					foreach( $parameterType->getTypes() As $type ) {
						if( $type Instanceof ReflectionNamedType ) {
							$events[] = $type->getName();
							break;
						}
					}
				}
				break;
			}
		}
		foreach( $events As $event ) {
			if( isset( $instance->listeners[$event] ) === False ) {
				$instance->listeners[$event] = [$listener];
				continue;
			}
			$instance->listeners[$event][] = [$listener];
		}
		return $instance;
	}

	/*
	 * Get listener by event class name.
	 * 
	 * @access Public
	 * 
	 * @params String ...$events
	 * 
	 * @return Iterable
	 */
	public function getEvents( String ...$events ): Iterable {
		foreach( $events As $event ) {
			if( isset( $this->listeners[$event] ) ) {
				yield from $this->listeners[$event];
			}
		}
	}

}

?>