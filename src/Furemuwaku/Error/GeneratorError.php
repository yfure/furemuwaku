<?php

namespace Yume\Fure\Error;

/*
 * GeneratorError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\ReflectError
 */
class GeneratorError extends ReflectError
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