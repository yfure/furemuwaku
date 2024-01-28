<?php

namespace Yume\Fure\Error;

/*
 * GeneratorError
 *
 * @extends Yume\Fure\Error\ReflectError
 *
 * @package Yume\Fure\Error
 */
class GeneratorError extends ReflectError {
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		GeneratorError::class => [
		]
	];
	
}

?>