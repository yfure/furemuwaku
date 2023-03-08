<?php

namespace Yume\Fure\Error;

/*
 * ParameterError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\ReflectError
 */
class ParameterError extends ReflectError
{
	
	/*
	 * @inherit Yume\Fure\Error\ClassError
	 *
	 */
	public const NAME_ERROR = ClassError::NAME_ERROR;
	
	/*
	 * Error constant for invalid parameter value.
	 *
	 * @access Public Static
	 *
	 * @value Int
	 */
	public const REQUIRE_ERROR = ValueError::VALUE_ERROR;
	
	/*
	 * @inherit Yume\Fure\Error\ClassError
	 *
	 */
	protected Array $flags = [
		ParameterError::class => [
			self::NAME_ERROR,
			self::REQUIRE_ERROR
		]
	];
	
}

?>