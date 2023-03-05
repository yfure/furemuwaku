<?php

namespace Yume\Fure\HTTP\Stream;

use Closure;
use Iterator;
use Stringable;

use Yume\Fure\Util\File;

/*
 * StreamFactory
 *
 * @package Yume\Fure\HTTP\Stream
 */
final class StreamFactory
{
	
	/*
	 * Create a new stream from a string.
	 *
	 * The stream SHOULD be created with a temporary resource.
	 *
	 * @access Public Static
	 *
	 * @params String $content
	 *  String content with which to populate the stream.
	 * @params Array $options
	 *  Stream options.
	 *
	 * @return StreamInterface
	 */
	public static function create( String $content = "", Array $options = [] ): StreamInterface
	{
		$fname = "php://temp";
		$stream = File\File::open( $fname, "r+" );
		
		// If content doesn't empty String.
		if( $content !== "" )
		{
			fwrite( $stream, $content );
			fseek( $stream, 0 );
		}
		return( new Stream( $stream, $options ) );
	}
	
	public static function createCopy(): StreamInterface
	{}
	
	/*
	 * Copy Stream Data as String.
	 *
	 * @access Public Static
	 *
	 * @params Yume\Fure\HTTP\Stream\StreamInterface $stream
	 * @params Int $maxLength
	 *
	 * @return String
	 */
	public static function createCopyString( StreamInterface $stream, Int $maxLength = -1 ): String
	{
		// Buffer stack.
		$buffer = "";
		
		// If maximum length less than negative one or equal it.
		if( $maxLength <= -1 )
		{
			// While for end-of-file on a stream pointer.
			while( !$stream->eof() )
			{
				// If buffer of stream is empty value, break it.
				if( "" === $buf = $stream->read( 1048576 ) )
				{
					break;
				}
				$buffer .= $buf;
			}
		}
		else {
			
			$length = 0;
			
			// While for end-of-file on a stream pointer.
			// And length less than maximum length given.
			while( !$stream->eof() && $length < $maxLength )
			{
				// If buffer of stream is empty value, break it.
				if( "" === $buf = $stream->read( $maxLength - $length ) )
				{
					break;
				}
				$buffer .= $buf;
				$length = strlen( $buffer );
			}
		}
		return( $buffer );
	}
	
	/*
	 * Create a stream from an existing file.
	 *
	 * @access Public Static
	 *
	 * @params String $fname
	 *  Filename or stream URI to use as basis of stream.
	 * @params String $mode
	 *  Mode with which to open the underlying filename/stream.
	 * @params Array $options
	 *  Stream options.
	 *
	 * @return Yume\Fure\HTTP\Stream\StreamInterface
	 *
	 * @throws Yume\Fure\Support\File\FileError
	 *  If the file cannot be opened.
	 * @throws Yume\Fure\Error\AssertionError
	 *  If the mode is invalid.
	 */
	public static function createFromFile( String $fname, String $mode = "r", Array $options = [] ): StreamInterface
	{
		if( File\File::exists( $fname ) )
		{
			return( new Stream( File\File::open( $fname, $mode ), $options ) );
		}
		throw new File\FileNotFoundError( $fname );
	}
	
	/*
	 * Create a new stream from an existing resource.
	 *
	 * The stream MUST be readable and may be writable.
	 *
	 * @access Public Static
	 *
	 * @params Resource $resource
	 *  PHP resource to use as basis of stream.
	 * @params Array $options
	 *  Stream options.
	 *
	 * @return Yume\Fure\HTTP\Stream\StreamInterface
	 */
	public static function createFromResource( $resource, Array $options = [] ): StreamInterface
	{
		switch( type( $resource, ref: $type, disable: True ) )
		{
			case "Object":
				
				// If the object is an implementation of StreamInterface
				// then it will only be returned, nothing is done.
				if( $resource Instanceof StreamInterface )return( $resource );
				
				// 
				if( $resource Instanceof Stringable )return( self::create( $resource, $options ) );
				if( $resource Instanceof Closure )return( new StreamPump( $resource, $options ) );
				if( $resource instanceof Iterator )
				{
					return( new StreamPump( 
						options: $options,
						callback: function()use( $resource )
						{
							if( $resource->valid() )
							{
								return( [ $resource->current(), $resource->next()][0] );
							}
							return( False );
						}
					 ) );
				}
				break;
				
			case "NULL":
			case "String": return( self::create( $resource ?? "", $options ) );
			
			case "Resource":
				
				/*
				 * Note php://input is a PHP stream used for reading raw data from the request body.
				 *
				 * Some potential issues when using it include not reading the stream properly,
				 * reading the stream multiple times, memory issues with large data, incorrect
				 * content type, and security concerns such as SQL injection or XSS attacks.
				 *
				 */
				if( stream_get_meta_data( $resource )['uri'] ?? "" === "php://input" )
				{
					$stream = File\File::open( "php://temp", "w+" );
					
					/*
					 * Copies data from one stream to another.
					 *
					 * Copy the contents of the php://input stream to another
					 * stream can be a safe and efficient way to handle data
					 * when working with streams in PHP.
					 *
					 * This function can help ensure the safe handling of data,
					 * is memory-efficient, and provides flexibility when working
					 * with different types of streams.
					 */
					stream_copy_to_stream( $resource, $stream );
					
					// Resets a file pointer.
					fseek( $stream, 0 );
				}
				return( new Stream( $stream ?? $resource, $options ) );
			
		}
		
		if( is_callable( $resource ) ){
			return new PumpStream( $resource, $options );
		}
		
		throw new \InvalidArgumentException( 'Invalid resource type: ' . gettype( $resource ) );
	}
	
}

?>