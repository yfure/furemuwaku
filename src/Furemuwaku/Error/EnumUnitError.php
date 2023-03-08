<?php

namespace Yume\Fure\Error;

/*
 * EnumUnitError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\EnumError
 */
class EnumUnitError extends EnumError
{
	
	/*
	 * @inherit Yume\Fure\Error\EnumError
	 *
	 */
	protected Array $flags = [
		Error::class => [
			self::_ERROR
		]
	];
	
}

?>