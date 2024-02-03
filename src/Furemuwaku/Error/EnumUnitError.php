<?php

namespace Yume\Fure\Error;

/*
 * EnumUnitError
 *
 * @extends Yume\Fure\Error\EnumError
 *
 * @package Yume\Fure\Error
 */
final class EnumUnitError extends EnumError {
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		EnumUnitError::class => [
		]
	];
	
}

?>