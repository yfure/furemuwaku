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
class StreamError extends Error\IOError
{
	
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
	 * @inherit Yume\Fure\Error\IOError
	 *
	 */
	protected Array $flags = [
		StreamError::class => [
			self::DETACH_ERROR,
			self::FREAD_ERROR,
			self::FSEEK_ERROR,
			self::FTELL_ERROR,
			self::FWRITE_ERROR,
			self::LENGTH_ERROR,
			self::READ_ERROR,
			self::READ_CONTENT_ERROR,
			self::SEEK_ERROR,
			self::STRINGIFY_ERROR,
			self::WRITE_ERROR
		],
		StreamBufferError::class => [
			self::SEEK_ERROR,
			self::TELL_ERROR
		]
	];
	
}

?>