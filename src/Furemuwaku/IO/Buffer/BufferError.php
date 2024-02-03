<?php

namespace Yume\Fure\IO\Buffer;

use Yume\Fure\Error;

/*
 * BufferError
 *
 * @extends Yume\Fure\Error\IOError
 *
 * @package Yume\Fure\IO\Buffer
 */
class BufferError extends Error\IOError {
	
	/*
	 * Error constant when failed to append buffer.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const APPEND_ERROR = 618289;
	
	/*
	 * Error constant when failed to clean output buffering.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const CLEAN_ERROR = 726262;
	
	/*
	 * Error constant when failed to sent output buffering.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const FLUSH_ERROR = 866282;
	
	/*
	 * Error constant when the output buffering doesn't have specified level.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const LEVEL_ERROR = 976272;
	
	/*
	 * Error constant when the output buffering doesn't started.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const STATUS_ERROR = 988292;
	
	/*
	 * Error constant when failed to terminate output buffering.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const TERMINATE_ERROR = 998223;
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		BufferError::class => [
			self::APPEND_ERROR => "Failed to apped output buffering",
			self::CLEAN_ERROR => "Failed to clean output buffering",
			self::FLUSH_ERROR => "Failed to flush output buffering",
			self::LEVEL_ERROR => "Output buffering has no level {}",
			self::STATUS_ERROR => "Output buffering doesn't started",
			self::TERMINATE_ERROR => "Failed to terminate output buffering"
		]
	];
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $track = [
		__NAMESPACE__ => [
			"classes" => [
				Buffer::class
			],
			"function" => [
				"ob_clean",
				"ob_end_clean",
				"ob_end_flush",
				"ob_flush",
				"ob_get_contents",
				"ob_get_flush",
				"ob_get_length",
				"ob_get_level",
				"ob_get_status",
				"ob_gzhandler"
			]
		]
	];
	
}

?>