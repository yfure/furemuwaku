<?php

namespace Yume\Fure\Error;

/*
 * ExtensionError
 *
 * @extends Yume\Fure\Error\ReflectError
 *
 * @package Yume\Fure\Error
 */
class ExtensionError extends ReflectError {
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		ExtensionError::class => [
		]
	];
	
}

?>