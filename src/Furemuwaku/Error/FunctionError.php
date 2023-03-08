<?php

namespace Yume\Fure\Error;

/*
 * ReflectError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\Error
 */
class FunctionError extends ReflectError
{
	
	/*
	 * @inherit Yume\Fure\Error\ReferenceError
	 *
	 */
	public const NAME_ERROR = ReferenceError::NAME_ERROR;
	
	/*
	 * @inherit Yume\Fure\Error\ReflectError
	 *
	 */
	protected Array $flags = [
		FunctionError::class => [
			self::NAME_ERROR
		]
	];
	
}

?>