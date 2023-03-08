<?php

namespace Yume\Fure\Error;

/*
 * ArgumentError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\ValueError
 */
class ArgumentError extends ValueError
{
	
	/*
	 * @inherit Yume\Fure\Error\ValueError
	 *
	 */
	protected Array $flags = [
		ArgumentError::class => [
			self::_ERROR
		]
	];
	
}

?>