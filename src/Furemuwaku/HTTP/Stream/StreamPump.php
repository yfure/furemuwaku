<?php

namespace Yume\Fure\HTTP\Stream;

use Closure;
use Exception;
use Throwable;

use Yume\Fure\Support\File;

/*
 * StreamPump
 *
 * Provides a read only stream that pumps data from a PHP callable.
 *
 * When invoking the provided callable, the PumpStream will pass the amount of
 * data requested to read to the callable. The callable can choose to ignore
 * this value and return fewer or more bytes than requested. Any extra data
 * returned by the provided callable is buffered internally until drained using
 * the read function of the PumpStream. The provided callable MUST return
 * false when there is no more data to read.
 *
 * @source https://github.com/guzzle/psr7
 *
 * @package Yume\Fure\HTTP\Stream
 */
final class StreamPump implements StreamInterface
{
	
	/*
	 * Instance of class StreamBuffer.
	 *
	 * @access Private
	 *
	 * @values Yume\Fure\HTTP\Stream\StreamBuffer
	 */
	private ? BufferStream $buffer;
	
	/*
	 * The stream metadata.
	 *
	 * @access Private
	 *
	 * @values Array
	 */
	private Array $metadata;
	
	/*
	 * The stream size.
	 *
	 * @access Private
	 *
	 * @values 
	 */
	private ? Int $size;
	
	/*
	 * Stream source.
	 *
	 * @access Private
	 *
	 * @values Closure
	 */
	private ? Closure $source;
	
	/*
	 * Tell position.
	 *
	 * @access Private
	 *
	 * @values Int
	 */
	private Int $tellPos = 0;
	
	/*
	 * Construct method of class StreamPump.
	 *
	 * @access Public Instance
	 *
	 * @params Closure $source
	 * @params Array $options
	 *
	 * @return Void
	 */
	public function __construct( Closure $source, Array $options = [])
	{
		$this->source = $source;
		$this->size = $options['size'] ?? null;
		$this->metadata = $options['metadata'] ?? [];
		$this->buffer = new BufferStream;
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::__toString
	 *
	 */
	public function __toString(): String
	{
		try
		{
			return( StreamFactory::createCopyString( $this ) );
		}
		catch( Throwable $e )
		{
			throw new StreamEror( $this::class, StreamError::STRINGIFY_ERROR, $e );
		}
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::close
	 *
	 */
	public function close(): Void
	{
		$this->detach();
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::detach
	 *
	 */
	public function detach()
	{
		return([ $this->source = Null, $this->tellPos = 0 ][0]);
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::eof
	 *
	 */
	public function eof(): Bool
	{
		return( $this )->source === Null;
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::getContents
	 *
	 */
	public function getContents(): String
	{
		$result = "";
		
		while( !$this->eof() )
		{
			$result .= $this->read( 1000000 );
		}
		return( $result );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::getMetadata
	 *
	 */
	public function getMetadata( Mixed $key = null ): Mixed
	{
		return( $key ? $this->metadata[$key] ?? Null : $this->metadata );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::getSize
	 *
	 */
	public function getSize(): ? Int
	{
		return( $this )->size;
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::readable
	 *
	 */
	public function isReadable(): Bool
	{
		return( True );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::isSeekable
	 *
	 */
	public function isSeekable(): Bool
	{
		return( False );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::isWritable
	 *
	 */
	public function isWritable(): Bool
	{
		return( False );
	}
	
	private function pump( Int $length ): Void
	{
		// If the source stream has not been unset.
		if( $this->source Instanceof Closure )
		{
			do
			{
				// Get callback return value.
				$data = call_user_func( $this->source, $length );
				
				// If the callback returns the dismiss value.
				if( $data === False || $data === Null || $data === STOP_ITERATION )
				{
					$this->source = Null; return;
				}
				$this->buffer->write( $data );
				$length -= strlen( $data );
			}
			while( $length > 0 );
		}
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::read
	 *
	 */
	public function read( Int $length ): String
	{
		/*
		 * Add the previous tell position value to the
		 * length value of the data that has been read.
		 *
		 */
		$this->tellPos += $readLength = strlen( $data = $this->buffer->read( $length ) );
		
		// If there is still a long remaining.
		if( $remaining = $length - $readLength )
		{
			$this->pump( $remaining );
			$this->tellPos += strlen( $data .= $this->buffer->read( $remaining ) ) - $readLength;
		}
		return( $data );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::rewind
	 *
	 */
	public function rewind(): Void
	{
		$this->seek( 0 );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::seek
	 *
	 */
	public function seek( Int $offset, Int $whence = SEEK_SET ): Void
	{
		throw new StreamError( $this::class, StreamError::SEEK_ERROR );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::tell
	 *
	 */
	public function tell(): Int
	{
		return( $this )->tell;
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::write
	 *
	 */
	public function write( String $string ): Int
	{
		throw new StreamError( $this::class, StreamError::WRITE_ERROR );
	}
	
}

?>