<?php

namespace Yume\Fure\Error;

use Throwable;

/*
 * EnumBackedError
 *
 * @extends Yume\Fure\Error\EnumError
 *
 * @package Yume\Fure\Error
 */
final class EnumBackedError extends EnumError
{
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		EnumBackedError::class => [
		]
	];
	
}

?>