<?php

namespace Yume\Fure\Util\Env;

use Throwable;

use Yume\Fure\Error;
use Yume\Fure\Util\RegExp;

/*
 * EnvError
 *
 * @package Yume\Fure\Util\Env
 *
 * @extends Yume\Fure\Error\TypeError
 */
class EnvError extends Error\TypeError
{
	
	public const ASSIGMENT_ERROR = 10238;
	public const COMMENT_ERROR = 11428;
	public const JSON_ERROR = 11725;
	public const REFERENCE_ERROR = 13467;
	public const SYNTAX_ERROR = 14829;
	
	/*
	 * @inherit Yume\Fure\Error\TypeError
	 *
	 */
	protected Array $flags = [
		self::ASSIGMENT_ERROR => "Invalid operator \"{}\" for assigment value to variable",
		self::COMMENT_ERROR => "Value can't have \"{}\" comment syntax",
		self::JSON_ERROR => "Invalid json string value in variable \"{}\"",
		self::REFERENCE_ERROR => "Undefined environment variable \"{}\"",
		self::SYNTAX_ERROR => "Invalid syntax \"{}\""
	];
	
	/*
	 * @inherit Yume\Fure\Error\TypeError
	 *
	 */
	public function __construct( Array | Int | String $message, ? String $file = Null, Int $line = 0, Int $code = 0, ? Throwable $previous = Null )
	{
		// Set environment file name.
		$this->file = $file ?? $this->file;
		
		// Set environment line number.
		$this->line = $line ?: $this->line;
		
		// Call parent constructor.
		parent::__construct( RegExp\RegExp::replace( "/^[\s\t]*/", $message, "" ), $code, $previous );
	}
	
}

?>