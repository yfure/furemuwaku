<?php

namespace Yume\Fure\HTTP\Stream;

use Exception;
use Throwable;

use Yume\Fure\Error;
use Yume\Fure\Support\File;

/*
 * Stream
 *
 * @package Yume\Fure\HTTP\Stream
 */
class Stream implements StreamInterface
{
	
	/*
	 * Stream.
	 *
	 * @access Private
	 *
	 * @values Resource
	 */
	private $stream;
	
	/*
	 * Stream size.
	 *
	 * @access Private
	 *
	 * @values Int
	 */
	private ? Int $size;
	
	/*
	 * Stream seekable.
	 *
	 * @access Private
	 *
	 * @values Bool
	 */
	private Bool $seekable;
	
	/*
	 * Stream readable.
	 *
	 * @access Private
	 *
	 * @values Bool
	 */
	private Bool $readable;
	
	/*
	 * Stream writeble.
	 *
	 * @access Private
	 *
	 * @values Bool
	 */
	private Bool $writable;
	
	/*
	 * Stream URI
	 *
	 * @access Private
	 *
	 * @values String
	 */
	private ? String $uri;
	
	/*
	 * Stream Custom Metadata
	 *
	 * @access Private
	 *
	 * @values Array<Mixed>
	 */
	private Array $customMetadata;
	
	/*
	 * Construct method of class Stream.
	 *
	 * @access Public Instance
	 *
	 * @params Resource $stream
	 * @params Array $options
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\Error\AssertionError
	 */
	public function __construct( $stream, Array $options = [] )
	{
		// Check if stream is Resource type.
		if( is_resource( $stream ) )
		{
			// Set size by size option given.
			$this->size = $options['size'] ?? Null;
			
			// Set custom metadata.
			$this->customMetadata = $options['metadata'] ?? [];
			
			// Get stream metadata.
			$meta = stream_get_meta_data( $stream );
			
			// Set stream.
			$this->stream = $stream;
			
			// Set seekable stream (If True value).
			$this->seekable = $meta['seekable'];
			
			$this->readable = File\File::isReadableMode( $meta['mode'] );
			$this->writable = File\File::isWritableMode( $meta['mode'] );
			
			// Set stream URI.
			$this->uri = $this->getMetadata( "uri" );
		}
		else {
			throw new Error\AssertionError( [ "Stream", "Resource", type( $stream ) ], Error\AssertionError::VALUE_ERROR );
		}
	}
	
	/*
	 * Closes the stream when the destructed.
	 * 
	 * @access Public
	 * 
	 * @return Void
	 */
	public function __destruct()
	{
		$this->close();
	}
	
	/*
	 * Parse class into string.
	 * 
	 * @access Public
	 * 
	 * @return String
	 *
	 * @throws Yume\Fure\HTTP\Stream\StreamError
	 */
	public function __toString(): String
	{
		try
		{
			// Check if stream is seekable.
			if( $this->isSeekable() )
				$this->seek(0);
			
			// Return from get contents.
			return( $this )->getContents();
		}
		catch( Throwable $e )
		{
			throw new StreamError( $this::class, StreamError::STRINGIFY_ERROR, $e );
		}
		return( "" );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function close(): Void
	{
		// Check if stream is sets.
		if( isset( $this->stream ) )
		{
			// Check if stream is Resource type.
			if( is_resource( $this->stream ) )
			{
				fclose( $this->stream );
			}
			$this->detach();
		}
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function detach()
	{
		// Check if stream is sets.
		if( isset( $this->stream ) )
		{
			// Get stream resource.
			$result = $this->stream;
			
			// Unset current stream.
			unset( $this->stream );
			
			// Set stream size as Null.
			$this->size = $this->uri = Null;
			
			// Disabled read, write, and seek.
			$this->readable = $this->writable = $this->seekable = False;
			
			// Return resource.
			return $result;
		}
		return( Null );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function eof(): Bool
	{
		// Check if stream is sets.
		if( isset( $this->stream ) )
		{
			return( feof( $this->stream ) );
		}
		throw new \RuntimeException('Stream is detached');
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function getContents(): String
	{
		// Check if stream is not sets.
		if( isset( $this->stream ) === False ) throw new \RuntimeException('Stream is detached');
		
		// Check if stream is readable.
		if( $this->readable )
		{
			return( Utils::tryGetContents($this->stream) );
		}
		throw new \RuntimeException('Cannot read from non-readable stream' );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function getMetadata( Mixed $key = null ): Mixed
	{
		return( match( True )
		{
			// If stream is not sets.
			isset( $this->stream ) === False => $key ? Null : [],
			
			// If key is not Null or False type.
			$key !== Null || $key === False => f( "{}{}", $this->customMetadata, stream_get_meta_data( $this->stream ) ),
			
			// Key is exists on custom metadata.
			isset( $this->customMetadata[$key] ) => $this->customMetadata[$key],
			
			// Default.
			default => stream_get_meta_data( $this->stream )[$key] ?? Null
		});
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function getSize(): ? Int
	{
		// Checl if size is not Null type.
		if( $this->size === Null )
		{
			// Check if stream is sets.
			if( isset( $this->stream ) )
			{
				// Clear the stat cache if the stream has a URI.
				if( $this->uri ) clearstatcache( True, $this->uri );
				
				// Get stream information.
				$stats = fstat( $this->stream );
				
				// If if stats is not False and key size is exists.
				if( $stats !== False && isset( $stats['size'] ) )
				{
					return( $this->size = $stats['size'] );
				}
			}
			return( Null );
		}
		return( $this )->size;
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function isReadable(): Bool
	{
		return( $this )->readable;
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function isSeekable(): Bool
	{
		return( $this )->seekable;
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function isWritable(): Bool
	{
		return( $this )->writable;
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function read( Int $length ): String
	{
		// Check if stream is sets.
		if( isset( $this->stream ) )
		{
			// Check if stream is not readable.
			if( $this->isReadable() === False ) throw new StreamError( $this::class, StreamError::READ_ERROR );
			
			// Check if length is negative.
			if( $length < 0 ) throw new Error\ValueError( "Length parameter cannot be negative" );
			
			// Check if length is not zero value.
			if( $length !== 0 )
			{
				try
				{
					// Try to read stream with file read function.
					$string = fread( $this->stream, $length );
				}
				catch( \Exception $e )
				{
					throw new StreamError( "Unable to read from stream", 0, $e );
				}
				
				// Check if read is successfull.
				if( $string !== False )
				{
					return( $string );
				}
				throw new StreamError( "Unable to read from stream" );
			}
			return( "" );
		}
		throw new StreamError( $this::class, StreamError::STREAM_DETACHED_ERROR );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function rewind(): Void
	{
		$this->seek( 0 );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function seek( Int $offset, Int $whence = SEEK_SET ): Void
	{
		// Check if stream is sets.
		if( isset( $this->stream ) )
		{
			// Check if stream is seekable.
			if( $this->seekable )
			{
				// Check if seek is not negative one.
				if( fseek( $this->stream, $offset, $whence ) === -1 )
				{
					throw new \RuntimeException('Unable to seek to stream position '
						. $offset . ' with whence ' . var_export($whence, true));
				}
				return;
			}
			throw new \RuntimeException('Stream is not seekable');
		}
		throw new \RuntimeException('Stream is detached');
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function tell(): Int
	{
		// Check if stream is sets.
		if( isset( $this->stream ) )
		{
			// Get return from ftell function.
			$result = ftell( $this->stream );
			
			// If result is not False.
			if( $result !== False )
			{
				return $result;
			}
			throw new \RuntimeException('Unable to determine stream position');
		}
		throw new \RuntimeException('Stream is detached');
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 */
	public function write( String $string ): Int
	{
		// Check if stream is sets.
		if (!isset($this->stream))
		{
			// Check if stream is writable.
			if( $this->writable )
			{
				// We can't know the size after writing anything
				$this->size = null;
				
				// Get result from fwrite function.
				$result = fwrite( $this->stream, $string );
				
				// If result is not False.
				if( $result !== False )
				{
					return $result;
				}
				throw new \RuntimeException('Unable to write to stream');
			}
			throw new \RuntimeException('Cannot write to a non-writable stream');
		}
		throw new \RuntimeException('Stream is detached');
	}
	
}

?>