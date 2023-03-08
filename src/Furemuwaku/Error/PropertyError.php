<?php

namespace Yume\Fure\Error;

/*
 * PropertyError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\ConstantError
 */
class PropertyError extends ConstantError
{
	
	/*
	 * @inherit Yume\Fure\Error\ConstantError
	 *
	 */
	protected Array $flags = [
		PropertyError::class => [
			self::ACCESS_ERROR,
			self::NAME_ERROR
		]
	];
	
}

?>