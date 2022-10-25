<?php

namespace Yume\Fure\Error;

/*
 * SyntaxError
 *
 * @extends Yume\Fure\Error\ParseError
 *
 * @package Yume\Fure\Error
 */
class SyntaxError extends ParseError
{
	
	/*
	 * System rule error.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const RULE_ERROR = 13720;
	
	/*
	 * Unexpected token error.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const TOKEN_ERROR = 13722;
	
	/*
	 * @inherit Yume\Fure\Error\ParseError
	 *
	 */
	protected Array $flags = [
		13720 => "There is an error in writing the code.",
		13722 => "Unexpected token `{}`."
	];
	
}

?>