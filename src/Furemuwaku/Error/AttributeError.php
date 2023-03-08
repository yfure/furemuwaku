<?php

namespace Yume\Fure\Error;

/*
 * AttributeError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\ClassError
 */
class AttributeError extends ClassError
{
	
	/*
	 * @inherit Yume\Fure\Error\ClassError
	 *
	 */
	protected Array $flags = [
		AttributeError::class => [
			self::NAME_ERROR,
			self::TYPE_ERROR
		]
	];
	
}

?>