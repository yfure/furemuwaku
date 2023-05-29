<?php

namespace Yume\Fure\Error;

/*
 * FiberError
 *
 * @extends Yume\Fure\Error\ReflectError
 *
 * @package Yume\Fure\Error
 */
class FiberError extends ReflectError
{
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		FiberError::class => [
			// ...
		]
	];
	
}

?>