<?php

namespace Yume\Fure\HTTP\Stream;

use Stringable;

/*
 * StreamInterface
 *
 * @package Yume\Fure\HTTP\Stream
 */
interface StreamInterface extends Stringable
{
	
	/*
	 * Closes the stream and any underlying resources.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function close(): Void;
	
	/*
	 * Separates any underlying resources from the stream.
	 *
	 * @access Public
	 *
	 * @return Reasource
	 */
	public function detach();
	
	/*
	 * Returns true if the stream is at the end of the stream.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function eof(): Bool;
	
	/*
	 * Returns the remaining contents in a string.
	 *
	 * @access Public
	 *
	 * @return String
	 *
	 * @throws Yume\Fure\HTTP\Stream\StreamError
	 */
	public function getContents(): String;
	
	/*
	 * Get stream metadata as an associative array or retrieve a specific key.
	 *
	 * @access Public
	 *
	 * @params Mixed $key
	 *
	 * @return Mixed
	 */
	public function getMetadata( Mixed $key = null ): Mixed;
	
	/*
	 * Get the size of the stream if known.
	 *
	 * @access Public
	 *
	 * @return Int
	 */
	public function getSize(): ? Int;
	
	/*
	 * Returns whether or not the stream is readable.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function isReadable(): Bool;
	
	/*
	 * Returns whether or not the stream is seekable.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function isSeekable(): Bool;
	
	/*
	 * Returns whether or not the stream is writable.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function isWritable(): Bool;
	
	/*
	 * Read data from the stream.
	 *
	 * @access Public
	 *
	 * @params Int $length
	 *
	 * @return String
	 *
	 * @throws Yume\Fure\HTTP\Stream\StreamError
	 */
	public function read( Int $length ): String;
	
	/*
	 * Seek to the beginning of the stream.
	 *
	 * @access Public
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\HTTP\Stream\StreamError
	 */
	public function rewind(): Void;
	
	/*
	 * Seek to a position in the stream.
	 *
	 * @access Public
	 *
	 * @params Int $offset
	 * @params Int $whence
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\HTTP\Stream\StreamError
	 */
	public function seek( Int $offset, Int $whence = SEEK_SET ): Void;
	
	/*
	 * Returns the current position of the file read/write pointer.
	 *
	 * @access Public
	 *
	 * @return Int
	 *
	 * @throws Yume\Fure\HTTP\Stream\StreamError
	 */
	public function tell(): Int;
	
	/*
	 * Write data to the stream.
	 *
	 * @access Public
	 *
	 * @params String $string
	 *
	 * @return Int
	 *
	 * @throws Yume\Fure\HTTP\Stream\StreamError
	 */
	public function write( String $string ): Int;
	
}

?>