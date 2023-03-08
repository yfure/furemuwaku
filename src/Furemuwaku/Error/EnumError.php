<?php

namespace Yume\Fure\Error;

/*
 * EnumError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\ClassError
 */
class EnumError extends ClassError
{
	
	/*
	 * @inherit Yume\Fure\Error\ClassError
	 *
	 */
	protected Array $flags = [
		EnumError::class => [
			self::NAME_ERROR
		],
		EnumBackedError::class => [
			self::_ERROR
		],
		EnumUnitError::class => [
			self::_ERROR
		]
	];
	
}

?>