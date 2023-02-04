<?php

namespace Yume\Fure\Error;

use Throwable;

/*
 * PermissionError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\IOError
 */
class PermissionError extends IOError
{
	
	/*
	 * Error constant for errors while reading the file.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const READ_ERROR = 84901;
	
	/*
	 * Error constant for error while writing the contents of the file.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const WRITE_ERROR = 84911;
	
	/*
	 * @inherit Yume\Fure\Error\IOError
	 *
	 */
	protected Array $flags = [
		PermissionError::class => [
			self::PERMISSION_ERROR,
			self::READ_ERROR,
			self::WRITE_ERROR
		]
	];
	
	/*
	 * @inherit Yume\Fure\Error\IOError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::PERMISSION_ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( $message, $code, $previous );
	}
	
}

?>