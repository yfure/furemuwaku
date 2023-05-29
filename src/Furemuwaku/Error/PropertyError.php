<?php

namespace Yume\Fure\Error;

/*
 * PropertyError
 *
 * @extends Yume\Fure\Error\ConstantError
 *
 * @package Yume\Fure\Error
 */
class PropertyError extends ConstantError
{
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		PropertyError::class => [
			self::ACCESS_ERROR => "Property {}::\${} is not accessible from outsite class",
			self::NAME_ERROR => "Class {} has no property named {}"
		]
	];
	
}

?>