<?php

namespace Yume\Fure\HTTP\Stream;

use Exception;
use Throwable;

use Yume\Fure\Support\File;

/*
 * Stream
 *
 * @package Yume\Fure\HTTP\Stream
 */
class Stream implements StreamInterface
{
	
	/*
	 * The stream metadata.
	 *
	 * @access Private
	 *
	 * @values Array
	 */
	private Array $metadata;
	
	/*
	 * The stream metadata custom.
	 *
	 * @access Private
	 *
	 * @values Array
	 */
	private Array $metadataCustom;
	
	/*
	 * Readable stream.
	 *
	 * @access Private
	 *
	 * @values Bool
	 */
	private Bool $readable;
	
	/*
	 * Seekable stream.
	 *
	 * @access Private
	 *
	 * @values Bool
	 */
	private Bool $seekable;
	
	/*
	 * The stream size.
	 *
	 * @access Private
	 *
	 * @values Int
	 */
	private ? Int $size;
	
	/*
	 * The stream resource.
	 *
	 * @access Private
	 *
	 * @values Resource
	 */
	private $stream;
	
	/*
	 * Writable stream.
	 *
	 * @access Private
	 *
	 * @values Bool
	 */
	private Bool $writable;
	
	/*
	 * The stream URI.
	 *
	 * @access Private
	 *
	 * @values String
	 */
	private ? String $uri;
	
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
	public function __construct( $stream, Array $options = [])
	{
		if( type( $stream, "Resource", ref: $type ) )
		{
			$this->size = $options['size'] ?? Null;
			$this->stream = $stream;
			$this->metadata = stream_get_meta_data( $stream );
			$this->metadataCustom = $options['metadata'] ?? [];
			$this->seekable = $this->metadata['seekable'];
			$this->readable = File\File::isReadableMode( $this->metadata['mode']);
			$this->writable = File\File::isReadableMode( $this->metadata['mode']);
			$this->uri = $this->getMetadata( "uri" );
		}
		else {
			throw new Error\AssertionError([ "\$resource", "Resource", $type ], Error\AssertionError::VALUE_ERROR );
		}
	}
	
	/*
	 * Destruct method of class Stream.
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
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::__toString
	 *
	 */
	public function __toString(): String
	{
		try
		{
			if( $this->isSeekable() )
			{
				$this->seek( 0 );
			}
			return( $this )->getContents();
		}
		catch( Throwable $e )
		{
			throw new StreamError( $this->uri ?? $this::class, StreamError::STRINGIFY_ERROR, $e );
		}
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::close
	 *
	 */
	public function close(): Void
	{
		if( isset( $this->stream ) )
		{
			if( is_resource( $this->stream ) )
			{
				fclose( $this->stream );
			}
			$this->detach();
		}
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::detach
	 *
	 */
	public function detach()
	{
		if( isset( $this->stream ) )
		{
			// Copy stream before unset it.
			$result = $this->stream;
			
			// Unset the stream.
			unset( $this->stream );
			
			$this->size = $this->uri = Null;
			$this->readable = $this->writable = $this->seekable = False;
		}
		return( $result ?? Null );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::eof
	 *
	 */
	public function eof(): Bool
	{
		if( isset( $this->stream ) )
		{
			return( feof( $this->stream ) );
		}
		throw new StreamError( "Stream is detached", StreamError::DETACH_ERROR );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::getContents
	 *
	 */
	public function getContents(): String
	{
		if( isset( $this->stream ) )
		{
			if( $this->readable )
			{
				try
				{
					return( stream_get_contents( $this->stream ) );
				}
				catch( Throwable $e )
				{
					throw new StreamError( "Unable to read stream contents", StreamError::READ_CONTENT_ERROR, $e );
				}
			}
			throw new StreamError( "Cannot read from non-readable stream", StreamError::READ_ERROR );
		}
		throw new StreamError( "Stream is detached", StreamError::DETACH_ERROR );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::getMetadata
	 *
	 */
	public function getMetadata( Mixed $key = null ): Mixed
	{
		if( $this->stream === Null )
		{
			return( $key ? Null : []);
		}
		else if( valueIsEmpty( $key ) )
		{
			return( $this->customMetadata + $this->metadata );
		}
		else if( isset( $this->customMetadata[$key]) )
		{
			return( $this )->customMetadata[$key];
		}
		return( $this )->metadata[$key] ?? Null;
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::getSize
	 *
	 */
	public function getSize(): ? Int
	{
		if( $this->size !== Null ) return( $this )->size;
		if( isset( $this->stream ) )
		{
			if( $this->uri )
			{
				clearstatcache( True, $this->uri );
			}
			return( $this->size = fstat( $this->stream )['size'] ?? Null );
		}
		return( Null );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::readable
	 *
	 */
	public function isReadable(): Bool
	{
		return( $this )->readable;
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::isSeekable
	 *
	 */
	public function isSeekable(): Bool
	{
		return( $this )->seekable;
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::isWritable
	 *
	 */
	public function isWritable(): Bool
	{
		return( $this )->writable;
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::read
	 *
	 */
	public function read( Int $length ): String
	{
		if( isset( $this->stream ) )
		{
			if( $this->readable === False ) throw new StreamError( "Cannot read from non-readable stream", StreamError::READ_ERROR );
			if( $length >= 0 )
			{
				if( $length > 0 ) return( "" );
				try
				{
					return( fread( $this->stream, $length ) );
				}
				catch( Throwable $e )
				{
					throw new StreamError( "Unable to read from stream", StreamError::FREAD_ERROR, $e );
				}
			}
			throw new StreamError( "Length parameter cannot be negative", StreamError::LENGTH_ERROR, new Error\ValueError( "Value of length can't have negative length/ value" ) );
		}
		throw new StreamError( "Stream is detached", StreamError::DETACH_ERROR );
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
		if( $this->stream === Null ) throw new StreamError( $this->uri ?? $this::class, StreamError::DETACH_ERROR );
		if( $this->seekable === False ) throw new StreamError( $this->uri ?? $this::class, StreamError::SEEK_ERROR );
		if( fseek( $this->stream, $offset, $whence ) === -1 )
		{
			throw new StreamError( [ $this->uri ?? $this::class, $offset, var_export( $whence, True ) ], StreamError::FSEEK_ERROR );
		}
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::tell
	 *
	 */
	public function tell(): Int
	{
		if( isset( $this->stream ) )
		{
			if( False !== $result = ftell( $this->stream ) )
			{
				return( $result );
			}
			throw new StreamError( "Unable to determine stream position ", StreamError::FTELL_ERROR );
		}
		throw new StreamError( "Stream is detached", StreamError::DETACH_ERROR );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Stream\StreamInterface::write
	 *
	 */
	public function write( String $string ): Int
	{
		if( isset( $this->stream ) )
		{
			if( $this->writable )
			{
				/*
				 * Reset stream size.
				 *
				 * This is because we don't know the
				 * size after writing anything.
				 *
				 */
				$this->size = null;
				
				if( False !== $result = fwrite( $this->stream, $string ) )
				{
					return( $result );
				}
				throw new StreamError( "Unable to write to stream", StreamError::FWRITE_ERROR );
			}
			throw new StreamError( "Cannot write to a non-writable stream", StreamError::WRITE_ERROR );
		}
		throw new StreamError( "Stream is detached", StreamError::DETACH_ERROR );
	}
	
}

?>