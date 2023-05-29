<?php

namespace Yume\Fure\Error;

/*
 * EnumError
 *
 * @extends Yume\Fure\Error\ClassError
 *
 * @package Yume\Fure\Error
 */
class EnumError extends ClassError
{
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		EnumError::class => [
			self::NAME_ERROR => "No enum named {}"
		],
		EnumBackedError::class => [
			//self::NAME_ERROR => ""
		],
		EnumUnitError::class => [
			//self::NAME_ERROR => ""
		]
	];
	
}

?>