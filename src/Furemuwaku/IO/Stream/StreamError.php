<?php

namespace Yume\Fure\IO\Stream;

use Yume\Fure\Error;

/*
 * StreamError
 *
 * @package Yume\Fure\IO\Stream
 *
 * @extends Yume\Fure\Error\IOError
 */
class StreamError extends Error\IOError {
	
	/*
	 * Error constant for when stream has detached.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const DETACH_ERROR = 67291;
	
	/*
	 * Error constant for problems with reading streams.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const FREAD_ERROR = 77892;
	
	/*
	 * Error constant for problems occur when managing the Ulamg Pointer Stream.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const FSEEK_ERROR = 77972;
	
	/*
	 * Error constant for problems occur when tell stream.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const FTELL_ERROR = 77991;
	
	/*
	 * Error constant for problems when writing content to streams.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const FWRITE_ERROR = 77999;
	
	/*
	 * Error constant for a length value below zero.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const LENGTH_ERROR = 80282;
	
	/*
	 * Error constant for read unreadable stream.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const READ_ERROR = 81272;
	
	/*
	 * Error constant for problems with reading stream content with stream_get_contents.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const READ_CONTENT_ERROR = 81366;
	
	/*
	 * Error constant for seek a stream that is not seek.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const SEEK_ERROR = 83782;
	
	/*
	 * Error constant for error occurred when parse stream into string.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const STRINGIFY_ERROR = 83492;
	
	/*
	 * Error constant for Streams that cannot be Tell.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const TELL_ERROR = 84667;
	
	/*
	 * Error constant for Errors when writing content with streams.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const WRITE_ERROR = 85789;
	
	/*
	 * @inherit Yume\Fure\Error\YumeError::$flags
	 *
	 */
	protected Array $flags = [
		StreamError::class => [
			self::DETACH_ERROR => "Stream {} has been detach, the stream is useless",
			self::FREAD_ERROR => "An error occurred when reading the {} stream",
			self::FSEEK_ERROR => "An error occurred in Stream {} when re-changing the pointer to {} with whence {}",
			self::FTELL_ERROR => "An error occurred in Stream {} when determine position",
			self::FWRITE_ERROR => "An error occurred in Stream {} when writing content",
			self::LENGTH_ERROR => "Cannot read stream content with length below zero, {} is given",
			self::READ_ERROR => "Can't read stream {}, because stream is unreadable",
			self::READ_CONTENT_ERROR => "An error occurred when getting the content stream {}",
			self::SEEK_ERROR => "Stream {} cannot seek, because the unseekable stream",
			self::STRINGIFY_ERROR => "Failed parse Object Stream {} into string",
			self::TELL_ERROR => "Cannot determine the position of a Stream {}",
			self::WRITE_ERROR => "Stream {} cannot be written, because Stream is unwritable"
		],
		StreamBufferError::class => [
			self::SEEK_ERROR => "Stream {} cannot seek, because the unseekable stream",
			self::TELL_ERROR => "Cannot determine the position of a Stream {}"
		]
	];
	
}

?>
