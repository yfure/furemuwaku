<?php

namespace Yume\Fure\Error;

use Throwable;

/*
 * LengthError
 *
 * @extends Yume\Fure\Error\ValueError
 *
 * @package Yume\Fure\Error
 */
class LengthError extends ValueError {
	
	/*
	 * Error constant for length values that are too small.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const GREATER_ERROR = 89282;
	
	/*
	 * Error constant for long values that are too large.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const LESSTER_ERROR = 98655;
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		LengthError::class => [
			self::GREATER_ERROR => "The length of the value {} must be greater than {}, {} is given",
			self::LENGTH_ERROR => "Length of {} must be {}, {} given",
			self::LESSTER_ERROR => "The length of {} value must be less than {}, {} is given"
		]
	];
	
	/*
	 * @inherit Yume\Fure\Error\YumeError::__construct
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::LENGTH_ERROR, ? Throwable $previous = Null, ? String $file = Null, ? Int $line = Null ) {
		parent::__construct( $message, $code, $previous, $file, $line );
	}
	
}

?>