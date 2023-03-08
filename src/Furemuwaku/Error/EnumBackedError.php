<?php

namespace Yume\Fure\Error;

/*
 * EnumBackedError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\EnumUnitError
 */
class EnumBackedError extends EnumUnitError
{
	
	/*
	 * @inherit Yume\Fure\Error\UnumUnitError
	 *
	 */
	protected Array $flags = [
		Error::class => [
			self::_ERROR
		]
	];
	
}

?>