<?php

namespace Yume\Fure\View;

use Throwable;

use Yume\Fure\Error;

/*
 * ViewError
 *
 * @package Yume\Fure\View
 *
 * @extends Yume\Fure\Error\TypeError
 */
class ViewError extends Error\TypeError
{
	
	public const NOT_FOUND_ERROR = 46288;
	public const PARSE_ERROR = 57858;
	
	/*
	 * @inherit Yume\Fure\Error\TypeError
	 *
	 */
	protected Array $flags = [
		ViewError::class => [
			self::NOT_FOUND_ERROR,
			self::PARSE_ERROR
		]
	];
	
	/*
	 * @inherit Yume\Fure\Error\TypeError
	 *
	 */
	public function __construct( Array | Int | String $message, ? String $file = Null, ? Int $line = Null, Int $code = 0, ? Throwable $previous = Null )
	{
		// Set error file name.
		$this->file = $file ?? $this->file;
		
		// Set error line number.
		$this->line = $line ?: $line ?? $this->line;
		
		// Call parent constructor.
		parent::__construct( $message, $code, $previous );
	}
}

?>