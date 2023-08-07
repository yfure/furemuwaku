<?php

namespace Yume\Fure\IO\Buffer;

use Closure;
use Countable;

use Yume\Fure\IO\Stream;
use Yume\Fure\Support;
use Yume\Fure\Util\Array;

/*
 * Buffer
 *
 * @extends Yume\Fure\Support\Singleton
 *
 * @package Yume\Fure\IO\Buffer
 */
class Buffer extends Support\Singleton implements Countable
{
	
	public const CLEAN = 356799;
	public const FLUSH = 688575;
	
	/*
	 * Output buffer container.
	 *
	 * @access Private
	 *
	 * @values Array<>
	 */
	private Array\Lists $buffer;
	
	/*
	 * Output handler callback.
	 *
	 * @access Private
	 *
	 * @values Closure
	 */
	private ? Closure $callback = Null;
	
	/*
	 * Current output buffer level.
	 *
	 * @access Private
	 *
	 * @values Int
	 */
	private ? Int $level = Null;
	
	/*
	 * @inherit Yume\Fure\Support\Singleton
	 *
	 */
	protected function __construct( ? Closure $callback = Null )
	{
		// Get all output buffer statuses.
		$this->buffer = new Array\Lists( ob_get_status( True ) );
		$this->buffer->map(
			
			/*
			 * Mapping all buffers.
			 *
			 * @params Int $i
			 * @params Int $index
			 * @params Yume\Fure\Util\Array\Associative $buffer
			 *
			 * @return Void
			 */
			function( Int $i, Int $index, Array\Associative $buffer ): Void
			{
				// Set current buffer level status.
				$this->level = $index;
				
				// Get buffer contents.
				$buffer->contents = ob_get_contents();
				
				// Get buffer handler.
				$buffer->handler = ob_list_handlers()[$index];
			}
		);
	}
	
	public function append( String $buffer ): Buffer
	{
		return([ $this, $this->handler( $buffer, 0 ) ][0]);
	}
	
	public function count(): Int
	{
		return( $this )->buffer->count();
	}
	
	public function end( Int $flags ): Void
	{
		
	}
	
	protected function handler( String $buffer, Int $flags ): False | String
	{
		// If output buffering is active.
		if( $this->hasLevel() )
		{
			// Handle output buffering.
			$buffer = is_callable( $callback = $this->buffer[$this->level]->handler ?? $this->callback ) ? call_user_func( $callback, $buffer, $flags ) : $buffer;
			
			// Update buffer status.
			$this->buffer[$this->level]->replace( $this->status() );
			$this->buffer[$this->level]->contents .= $buffer;
			
			return( $buffer );
		}
		throw new BufferError( $buffer, BufferError::STATUS_ERROR );
	}
	
	public function hasLevel(): Bool
	{
		return( is_int( $this->level ) );
	}
	
	public function length(): Int
	{
		return( strlen( $this->buffer[$this->level] ) );
	}
	
	public function setCallback( ? Closure $callback = Null, ? Int $level = Null ): Buffer
	{
		return([ $this, $this->callback = $callback ][0]);
	}
	
	public function setLevel( Int $level ): Buffer
	{
		// Check if level is valid.
		if( isset( $this->buffer[$level] ) )
		{
			$this->level = $level;
		}
		else {
			throw new BufferError( $level, BufferError::LEVEL_ERROR );
		}
		return( $this );
	}
	
	public function start( ? Closure $callback = Null, Int $chunk = 0, Int $flags = PHP_OUTPUT_HANDLER_STDFLAGS ): Buffer
	{
		// Starting output buffering.
		ob_start( fn( String $buffer, Int $flags ) => $this->handler( $buffer, $flags ), $chunk, $flags );
		
		// Set current callback.
		$this->callback = $callback ?? $this->callback;
		
		// Push current buffer.
		$this->buffer[] = [
			...ob_get_status(),
			...[
				"contents" => "",
				"handler" => $this->callback
			]
		];
		
		// Update buffer level.
		$this->level = count( $this->buffer ) -1;
		
		return( $this );
	}
	
	public function status( ? Int $level = Null ): Array | Array\Arrayable
	{
		if( is_int( $level ) )
		{
			if( isset( $this->buffer[$level] ) )
			{
				return( $this )->buffer[$level];
			}
			throw new BufferError( $level, BufferError::LEVEL_ERROR );
		}
		return( ob_get_status() );
	}
	
}

?>