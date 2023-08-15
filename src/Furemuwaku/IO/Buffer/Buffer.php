<?php

namespace Yume\Fure\IO\Buffer;

use Closure;

use Yume\Fure\Error;
use Yume\Fure\Support;
use Yume\Fure\Util;
use Yume\Fure\Util\Arr;

/*
 * Buffer
 *
 * @extends Yume\Fure\Support\Singleton
 *
 * @package Yume\Fure\IO\Buffer
 */
class Buffer extends Support\Singleton
{
	
	/*
	 * End or get with clean mode.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	final public const CLEAN = 356799;
	
	/*
	 * End or get with flush mode.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	final public const FLUSH = 688575;
	
	/*
	 * Output buffer statuses.
	 *
	 * @access Protected
	 *
	 * @values Yume\Fure\Util\Arr\List<Yume\Fure\Util\Arr\Associative>
	 */
	protected Arr\Lists $buffer;
	
	/*
	 * Default output buffering handler.
	 *
	 * @access Protected
	 *
	 * @values Closure
	 */
	protected ? Closure $handler = Null;
	
	/*
	 * Current output buffering level.
	 *
	 * @access Protected
	 *
	 * @values Int
	 */
	protected Int $level = 0;
	
	/*
	 * @inherit Yume\Fure\Support\Singleton
	 *
	 */
	protected function __construct( ? Closure $handler = Null )
	{
		$this->handler = $handler;
		$this->buffer = new Arr\Lists([]);
		
		// Getting all output handlers.
		$handlers = ob_list_handlers();
		
		// Getting all output buffering statuse.
		$status = ob_get_status( True );
		
		// Mapping all output buffering statuse.
		Util\Arrays::map( $status,
			
			/*
			 * Buffer map handler.
			 *
			 * @params Int $i
			 * @params Int $index
			 * @params Yume\Fure\Util\Arr\Associative $buffer
			 *
			 * @return Void
			 */
			function( Int $i, Int $index, $buffer ) use( $handlers )
			{
				// Set current buffer level.
				$this->level = $index;
				$this->level++;
				
				// Set buffer contents.
				$this->buffer[$this->level]->buffer = ob_get_clean();
				
				// Set buffer handler.
				$this->buffer[$this->level]->handler = $handlers[$index];
			}
		);
	}
	
	/*
	 * Append output buffering contents.
	 *
	 * @access Public
	 *
	 * @params String $contents
	 *
	 * @return Yume\Fure\IO\Buffer\Buffer
	 *
	 * @throws Yume\Fure\IO\Buffer\BufferError
	 *  When the buffer doesn't not started.
	 */
	public function append( String $contents ): Buffer
	{
		// Check if output buffering doesn't have level.
		if( $this->hasLevel( False ) )
		{
			throw new BufferError( $this->level, BufferError::APPEND_ERROR );
		}
		else {
			echo $contents;
		}
		return( $this );
	}
	
	/*
	 * Clean current output buffering.
	 *
	 * @access Public
	 *
	 * @return Yume\Fure\IO\Buffer\Buffer
	 *
	 * @throws Yume\Fure\IO\Buffer\BufferError
	 *  When the buffer doesn't not started.
	 */
	public function clean(): Buffer
	{
		// Check if output buffering has level.
		if( $this->hasLevel() )
		{
			ob_clean();
		}
		else {
			throw new BufferError( 0, BufferError::CLEAN_ERROR );
		}
		return( $this );
	}
	
	/*
	 * Terminate current output buffering level.
	 *
	 * @access Public
	 *
	 * @params Int $flags
	 *
	 * @return Yume\Fure\IO\Buffer\Buffer
	 *
	 * @throws Yume\Fure\IO\Buffer\BufferError
	 *  When the buffer doesn't not started.
	 */
	public function end( Int $flags = self::CLEAN ): Buffer
	{
		// Check if output buffering has level.
		if( $this->hasLevel() )
		{
			switch( $flags )
			{
				case self::CLEAN: $this->clean(); break;
				case self::FLUSH: $this->flush(); break;
				default:
					throw new Error\AssertionError( [ "flags", [ "CLEAN", "FLUSH" ], $flags ], Error\AssertionError::VALUE_ERROR );
			}
			unset( $this->buffer[$this->level--] );
		}
		else {
			throw new BufferError( 0, BufferError::TERMINATE_ERROR );
		}
		return( $this );
	}
	
	/*
	 * Send output buffering.
	 *
	 * @access Public
	 *
	 * @params String $contents
	 *  When there are last contents to be sent before output sent.
	 *
	 * @return Yume\Fure\IO\Buffer\Buffer	
	 *
	 * @throws Yume\Fure\IO\Buffer\BufferError
	 *  When the buffer doesn't not started.
	 */
	public function flush( ? String $contents = Null ): Buffer
	{
		// Check if output buffering has level.
		if( $this->hasLevel() )
		{
			// If contents is available.
			if( $contents )
			{
				$this->append( $contents );
			}
			ob_flush();
		}
		else {
			throw new BufferError( 0, BufferError::FLUSH_ERROR );
		}
		return( $this );
	}
	
	/*
	 * Return output buffering contents.
	 *
	 * @access Public
	 *
	 * @params Int $level
	 *  For specified output buffering level.
	 *
	 * @return String
	 *
	 * @throws Yume\Fure\IO\Buffer\BufferError
	 *  When the buffer doesn't not started.
	 *  Or level doesn't exists.
	 */
	public function get( Int $level = 0 ): String
	{
		// Check if output buffering has level.
		if( $this->hasLevel() )
		{
			if( $level >= 1 )
			{
				if( isset( $this->buffer[$level] ) )
				{
					return( $this )->buffer[$level]->buffer;
				}
				throw new BufferError( $level, BufferError::LEVEL_ERROR );
			}
			return( $this )->buffer[$this->level]->buffer;
		}
		throw new BufferError( $this->buffer, BufferError::STATUS_ERROR );
	}
	
	/*
	 * Output buffering handler.
	 *
	 * @access Protected
	 *
	 * @params String $buffer
	 * @params Int $flags
	 *
	 * @return False|String
	 */
	final protected function handler( String $buffer, Int $flags ): False | String
	{
		// Check if output buffering has level.
		if( $this->hasLevel() )
		{
			// Get output buffering callback handler.
			$handler = $this->buffer[$this->level]->handler ?? $this->handler;
			
			// Handle output buffering.
			$buffer = is_callable( $handler ) ? call_user_func( $handler, $buffer, $flags ) : $buffer;
			
			// Append output buffering contents.
			$this->buffer[$this->level]->buffer .= $buffer ?: "";
			
			return( $buffer );
		}
		return( False );
	}
	
	/*
	 * Return if output buffering has level.
	 *
	 * @access Public
	 *
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	final public function hasLevel( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $this->hasLevel() === $optional : ( $this->level >= 1 && $this->level === ob_get_level() && $this->buffer->count() >= 1 && $this->buffer->count() === $this->level ) );
	}
	
	/*
	 * Return current output buffering level.
	 *
	 * @access Public
	 *
	 * @return Int
	 */
	public function level(): Int
	{
		return( $this )->level;
	}
	
	/*
	 * Starting new output buffering.
	 *
	 * @access Public
	 *
	 * @params Closure $handler
	 * @params Int $chunk
	 * @params Int $flags
	 *
	 * @return Yume\Fure\IO\Buffer\Buffer
	 */
	public function start( ? Closure $handler = Null, Int $chunk = 0, Int $flags = PHP_OUTPUT_HANDLER_STDFLAGS ): Buffer
	{
		// Starting output buffering.
		ob_start( fn( String $buffer, Int $flags ) => $this->handler( $buffer, $flags ), $chunk, $flags );
		
		// Normalize output buffering handler.
		$handler Instanceof Closure ? $handler : $this->handler;
		
		// Update output buffering level.
		$this->level = ob_get_level();
		
		// Get buffer status.
		$this->buffer[$this->level] = [
			...ob_get_status(),
			...[
				"buffer" => "",
				"handler" => $handler
			]
		];
		return( $this );
	}
	
	/*
	 * Return output buffering status.
	 *
	 * @access Public
	 *
	 * @params Int level
	 *  For specific output buffering.
	 *
	 * @return Yume\Fure\Util\Arr\Arrayable
	 *
	 * @throws Yume\Fure\IO\Buffer\BufferError
	 *  When the level is not found.
	 */
	public function status( Int $level = 0 ): Arr\Arrayable
	{
		if( $level >= 1 )
		{
			// Check if buffer status is available.
			if( $level <= $this->buffer->count() )
			{
				return( $this )->buffer[$level]->copy();
			}
			throw new BufferError( $level, BufferError::LEVEL_ERROR );
		}
		return( new Arr\Lists( $this->buffer ) );
	}
	
}

?>