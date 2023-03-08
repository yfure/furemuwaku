<?php

namespace Yume\Fure\Error;

/*
 * ExtensionError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\ReflectError
 */
class ExtensionError extends ReflectError
{
	
	/*
	 * @inherit Yume\Fure\Error\ReflectError
	 *
	 */
	protected Array $flags = [
		ExtensionError::class => [
			self::_ERROR
		]
	];
	
}

?>