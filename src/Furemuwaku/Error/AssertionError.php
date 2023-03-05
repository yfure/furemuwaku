<?php

namespace Yume\Fure\Error;

/*
 * AssertionError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\ValueError
 */
class AssertionError extends ValueError
{
	
	/*
	 * @inherit Yume\Fure\Error\ValueError
	 *
	 */
	protected Array $flags = [
		AssertionError::class => [
			self::VALUE_ERROR
		]
	];
	
}

?>