<?php

namespace Yume\Fure\HTTP\Stream;

/*
 * StreamBuffer
 *
 * Provides a buffer stream that can be written to to fill a buffer,
 * and read from to remove bytes from the buffer.
 *
 * This stream returns a "hwm" metadata value that tells upstream consumers
 * what the configured high water mark of the stream is, or the maximum
 * preferred size of the buffer.
 *
 * @source https://github.com/guzzle/psr7
 *
 * @package Yume\Fure\HTTP\Stream
 */
final class StreamBuffer implements StreamInterface
{
	
	/*
	 * High Water Mark.
	 *
	 * Representing the preferred maximum buffer size.
	 * If the size of the buffer exceeds the high water mark,
	 * then calls to write will continue to succeed but will
	 * return 0 to inform writers to slow down until the
	 * buffer has been drained by reading from it.
	 *
	 * @access Private
	 *
	 * @values Int
	 */
	private Int $hwm;
	
	/*
	 * Buffer contents.
	 *
	 * @access Private
	 *
	 * @values String
	 */
	private String $buffer = "";
	
	/*
	 * Construct method of class StreamBuffer.
	 *
	 * @access Public Instance
	 *
	 * @params Int $hwm
	 *
	 * @return Void
	 */
	public function __construct( Int $hwm = 16384 )
	{
		$this->hwm = $hwm;
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::__toString
	 *
	 */
	public function __toString(): String
	{
		return( $this )->getContents();
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::close
	 *
	 */
	public function close(): Void
	{
		$this->buffer = "";
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::detach
	 *
	 */
	public function detach()
	{
		return( $this )->close();
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::eof
	 *
	 */
	public function eof(): Bool
	{
		return( $this )->getSize()=== 0;
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::getContents
	 *
	 */
	public function getContents(): String
	{
		return([ $this->buffer, $this->buffer = "" ][0]);
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::getMetadata
	 *
	 */
	public function getMetadata( Mixed $key = null ): Mixed
	{
		return( $key === "hmw" ? $key :( $key ? Null : []) );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::getSize
	 *
	 */
	public function getSize(): ? Int
	{
		return( strlen( $this->buffer ) );
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
		return( True );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::read
	 *
	 */
	public function read( Int $length ): String
	{
		if( $length >= $this->getSize() )
		{
			// No need to slice the buffer because we don't have enough data.
		    $result = $this->buffer;
		    $this->buffer = "";
		} else {
		    // Slice up the result to provide a subset of the buffer.
		    $result = substr( $this->buffer, 0, $length );
		    $this->buffer = substr( $this->buffer, $length );
		}
		return( $result );
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
		throw new StreamBufferError( $this::class, StreamBufferError::SEEK_ERROR );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::tell
	 *
	 */
	public function tell(): Int
	{
		throw new StreamBufferError( $this::class, StreamBufferError::TELL_ERROR );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::write
	 *
	 */
	public function write( String $string ): Int
	{
		return([ $this->buffer .= $string, $this->getSize()>= $this->hwm ? 0 : strlen( $string )][1]);
	}
	
}

?>