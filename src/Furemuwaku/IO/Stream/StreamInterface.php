<?php

namespace Yume\Fure\IO\Stream;

use Stringable;

/*
 * StreamInterface
 *
 * @fsrouce IOs://github.com/php-fig/IO-message
 *
 * @package Yume\Fure\IO\Stream
 */
interface StreamInterface extends Stringable {
	
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
	 * After the stream has been detached, the stream is
	 * in an unusable state.
	 *
	 * @access Public
	 *
	 * @return Resource
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
	 * Returns the remaining contents in a string
	 *
	 * @access Public
	 *
	 * @return String
	 *
	 * @throws Yume\Fure\IO\Stream\StreamError
	 *  If unable to read or an error occurs while reading.
	 *
	 */
	public function getContents(): String;
	
	/*
	 * Get stream metadata as an associative array or retrieve a specific key.
	 *
	 * @access Public
	 *
	 * @params String $key
	 *  Specific metadata to retrieve.
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
	 *  Returns the size in bytes if known, or null if unknown.
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
	 *  Read up to $length bytes from the object and return them.
	 *  Fewer than $length bytes may be returned if underlying stream
	 *  call returns fewer bytes.
	 *
	 * @return String
	 *  Returns the data read from the stream, or an
	 *  empty string if no bytes are available.
	 *
	 * @throws Yume\Fure\IO\Stream\StreamError
	 */
	public function read( Int $length ): String;
	
	/*
	 * Seek to the beginning of the stream.
	 *
	 * @access Public
	 *
	 * @throws Yume\Fure\IO\Stream\StreamError
	 */
	public function rewind(): Void;
	
	/*
	 * Seek to a position in the stream.
	 *
	 * @access Public
	 *
	 * @params int $offset
	 * @params Int $whence
	 *
	 * @throws Yume\Fure\IO\Stream\StreamError
	 */
	public function seek( Int $offset, Int $whence = SEEK_SET ): Void;
	
	/*
	 * Returns the current position of the file read/write pointer.
	 *
	 * @access Public
	 *
	 * @return Int
	 *  Position of the file pointer
	 * 
	 * @throws Yume\Fure\IO\Stream\StreamError
	 */
	public function tell(): Int;
	
	/*
	 * Write data to the stream.
	 *
	 * @access Public
	 *
	 * @params String $string
	 *  The string that is to be written.
	 *
	 * @return Int
	 *  Returns the number of bytes written to the stream.
	 *
	 * @throws Yume\Fure\IO\Stream\StreamError
	 */
	public function write( String $string ): Int;
	
}

?>