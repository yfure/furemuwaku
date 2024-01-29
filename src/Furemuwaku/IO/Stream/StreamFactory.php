<?php

namespace Yume\Fure\IO\Stream;

use Closure;
use Iterator;
use Stringable;

use Yume\Fure\IO\File;
use Yume\Fure\Util\Reflect;

/*
 * StreamFactory
 *
 * @package Yume\Fure\IO\Stream
 */
final class StreamFactory {
	
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
	public static function create( String $content = "", Array $options = [] ): StreamInterface {
		$fname = "php://temp";
		$stream = File\File::open( $fname, "r+" );
		if( $content !== "" ) {
			fwrite( $stream, $content );
			fseek( $stream, 0 );
		}
		return( new Stream( $stream, $options ) );
	}
	
	/*
	 * Copy stream into another stream.
	 *
	 * @access Public Static
	 *
	 * @params Yume\Fure\IO\Stream\StreamInterace $source
	 * @params Yume\Fure\IO\Stream\StreamInterace $destination
	 * @params Int $length
	 *  Maximum number of bytes.
	 *
	 * @return Yume\Fure\IO\Stream\StreamInterface
	 *  Stream source destionation.
	 */
	public static function createCopy( StreamInterface $source, StreamInterface $destination, Int $length = -1 ): StreamInterface {
		$size = 8192;
		if( $length <= -1 ) {
            while( $source->eof() === False ) {
                if( $destination->write( $source->read( $size ) ) === False ) {
                    break;
                }
            }
        }
		else {
            $remain = $length;
            while( $remain > 0 && $source->eof() === False ) {
                $buffer = $source->read( min( $size, $remain ) );
                $length = strlen( $buffer );
				if( $length <= 0 ) {
					break;
				}
                $remain -= $length;
                $destination->write( $buffer );
            }
        }
		return( $destination );
	}
	
	/*
	 * Copy Stream Data as String.
	 *
	 * @access Public Static
	 *
	 * @params Yume\Fure\IO\Stream\StreamInterface $stream
	 * @params Int $maxLength
	 *
	 * @return String
	 */
	public static function createCopyString( StreamInterface $stream, Int $maxLength = -1 ): String {
		$buffer = "";
		if( $maxLength <= -1 ) {
			while( $stream->eof() === False ) {
				if( "" === $buf = $stream->read( 1048576 ) ) {
					break;
				}
				$buffer .= $buf;
			}
		}
		else {
			
			$length = 0;
			
			// While for end-of-file on a stream pointer.
			// And length less than maximum length given.
			while( !$stream->eof() && $length < $maxLength ) {
				
				// If buffer of stream is empty value, break it.
				if( "" === $buf = $stream->read( $maxLength - $length ) ) {
					break;
				}
				$buffer .= $buf;
				$length = strlen( $buffer );
			}
		}
		return( $buffer );
	}
	
	/*
	 * Creates a stream of output buffers.
	 *
	 * @access Public Static
	 *
	 * @params Closure $callback
	 * @params Array<Mixed> $args
	 *
	 * @return Yume\Fure\IO\Stream\StreamInterface
	 */
	public static function createFromOB( Closure $callback, Array $args = [] ): StreamInterface {
		
		// Starting output buffering.
		ob_start();
		
		// Execute callback function.
		Reflect\ReflectFunction::invoke( $callback, $args );
		
		// Clean and return output buffering.
		return( self::create( ob_get_clean() ?? "" ) );
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
	 * @return Yume\Fure\IO\Stream\StreamInterface
	 *
	 * @throws Yume\Fure\Support\File\FileError
	 *  If the file cannot be opened.
	 * @throws Yume\Fure\Error\AssertionError
	 *  If the mode is invalid.
	 */
	public static function createFromFile( String $fname, String $mode = "r", Array $options = [] ): StreamInterface {
		if( File\File::exists( $fname ) ) {
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
	 * @return Yume\Fure\IO\Stream\StreamInterface
	 */
	public static function createFromResource( Mixed $resource, Array $options = [] ): StreamInterface {
		switch( type( $resource, ref: $type, disable: True ) ) {
			case "Object":
				
				// If the object is an implementation of StreamInterface
				// then it will only be returned, nothing is done.
				if( $resource Instanceof StreamInterface ) return( $resource );
				
				// ....
				if( $resource Instanceof Stringable ) return( self::create( $resource, $options ) );
				if( $resource Instanceof Closure ) return( new StreamPump( $resource, $options ) );
				if( $resource instanceof Iterator ) {
					return( new StreamPump( 
						options: $options,
						source: function() use( $resource ) {
							if( $resource->valid() ) {
								return([ $resource->current(), $resource->next() ][0]);
							}
							return( False );
						}
					 ) );
				}
				break;
				
			case "NULL":
			case "String":

				// Check if stream is callable.
				if( is_callable( $resource ) ) {
					return( new StreamPump( $resource, $options ) );
				}
				return( self::create( $resource ?? "", $options ) );
			
			case "Resource":
				
				/*
				 * Note php://input is a PHP stream used for reading raw data from the request body.
				 *
				 * Some potential issues when using it include not reading the stream properly,
				 * reading the stream multiple times, memory issues with large data, incorrect
				 * content type, and security concerns such as SQL injection or XSS attacks.
				 *
				 */
				if( stream_get_meta_data( $resource )['uri'] ?? "" === "php://input" ) {
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
		throw new \InvalidArgumentException( 'Invalid resource type: ' . gettype( $resource ) );
	}
	
}

?>