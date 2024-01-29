<?php

namespace Yume\Fure\Error;

/*
 * ParameterError
 *
 * @extends Yume\Fure\Error\ReflectError
 *
 * @package Yume\Fure\Error
 */
class ParameterError extends ReflectError
{
	
	/*
	 * Error constant for unknown parameter names.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const NAME_ERROR = 18192;
	
	/*
	 * Error constant for required parameter.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const REQUIRE_ERROR = 68891;
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		ParameterError::class => [
			self::NAME_ERROR => "Function {} has no parameter named {}",
			self::REQUIRE_ERROR => "Function {} requires parameters {} with type {}"
		]
	];
	
}

?>