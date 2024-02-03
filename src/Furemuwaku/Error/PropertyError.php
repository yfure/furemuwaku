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
	
	public const UNITIALIZE_ERROR = 878232;
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		PropertyError::class => [
			self::ACCESS_ERROR => "Property {}::\${} is not accessible from outsite class",
			self::NAME_ERROR => "Class {} has no property named {}",
			self::UNITIALIZE_ERROR => "Property {}::\${} is unitialized"
		]
	];
	
}

?>