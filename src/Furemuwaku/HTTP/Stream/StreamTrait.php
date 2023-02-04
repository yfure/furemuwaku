<?php

namespace Yume\Fure\HTTP\Stream;

/*
 * StreamTrait
 *
 * @package Yume\Fure\HTTP\Stream
 */
trait StreamTrait
{
	
	/*
	 * Construct method of class Stream.
	 *
	 * @access Public Instance
	 *
	 * @params Yume\Fure\HTTP\Stream\StreamInterface $stream
	 *
	 * @return Void
	 */
	public function __construct( StreamInterface $stream )
	{
		$this->stream = $stream;
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function close(): Void
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function detach()
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function eof(): Bool
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function getContents(): String
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function getMetadata( Mixed $key = null ): Mixed
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function getSize(): ? Int
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function isReadable(): Bool
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function isSeekable(): Bool
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function isWritable(): Bool
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function read( Int $length ): String
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function rewind(): Void
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function seek( Int $offset, Int $whence = SEEK_SET ): Void
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function tell(): Int
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function write( String $string ): Int
	{
		// ...
	}
	
	/*
	 * Implement in subclasses to dynamically create streams when requested.
	 *
	 * @throws \BadMethodCallException
	 */
	protected function createStream(): StreamInterface
	{
		throw new \BadMethodCallException('Not implemented');
	}
	
}

?>