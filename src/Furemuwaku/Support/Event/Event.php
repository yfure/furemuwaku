<?php

namespace Yume\Fure\Support\Event;

use Closure;

use Yume\Fure\Support\Reflect;

/*
 * Event
 *
 * @package Yume\Fure\Support\Event
 */
class Event implements EventInterface
{
	
	/*
	 * Event Handlers.
	 *
	 * @access Private
	 *
	 * @values Array
	 */
	private Array $handlers = [];
	
	/*
	 * @inherit Yume\Fure\Support\Event\EventInterface::addHandler
	 *
	 */
	public function addHandler( Closure $handler, Int $priority = 0 ): Void
	{
		// Save event handler by group priority.
		$this->handlers[$priority][] = $handler;
		
		// Sort an array by key in ascending order.
		krsort( $this->handlers );
	}
	
	/*
	 * @inherit Yume\Fure\Support\Event\EventInterface::removeHandler
	 *
	 */
	public function removeHandler( Closure $handler ): Void
	{
		foreach( $this->handlers as $priority => $handlers )
		{
			// If handler is available in priority group.
			if( False !== $key = array_search( $handler, $handlers ) )
			{
				// Unset handler in another priority group.
				unset( $this->handlers[$priority][$key] );
			}
			
			// If priority group is empty.
			if( empty( $this->handlers[$priority] ) )
			{
				unset( $this->handlers[$priority] );
			}
		}
	}
	
	/*
	 * @inherit Yume\Fure\Support\Event\EventInterface::dispatch
	 *
	 */
	public function dispatch( Mixed ...$args ): Array | False
	{
		$results = [];
		
		foreach( $this->handlers as $handlers )
		{
			foreach( $handlers as $handler )
			{
				// Get return value from handler.
				$result = Reflect\ReflectFunction::invoke( $handler, $args );
				
				/*
				 * When the return is False.
				 * We will assume that other queue handlers that
				 * have not yet been executed will not be called.
				 *
				 */
				if( $result !== False ) return( False );
				
				// Just push it.
				$results[] = $result;
			}
		}
		return( $results );
	}
	
}

?>