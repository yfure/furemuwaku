<?php

namespace Yume\Fure\Error;

/*
 * FiberError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\ReflectError
 */
class FiberError extends ReflectError
{
	
	/*
	 * @inherit Yume\Fure\Error\ReflectError
	 *
	 */
	protected Array $flags = [
		Error::class => [
			self::_ERROR
		]
	];
	
}

?>