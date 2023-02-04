<?php

namespace Yume\Fure\Error;

/*
 * ClassError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\TypeError
 */
class ClassError extends TypeError
{
	
	public const IMPLEMENTS_ERROR = 57822;
	
	public const INSTANCE_ERROR = 67887;
	
	public const NAME_ERROR = 79222;
	
	/*
	 * @inherit Yume\Fure\Error\TypeError
	 *
	 */
	protected Array $flags = [
		ClassError::class => [
			self::IMPLEMENTS_ERROR,
			self::INSTANCE_ERROR, 
			self::NAME_ERROR
		],
		ClassImplementationError::class => [
			self::IMPLEMENTS_ERROR
		],
		ClassInstanceError::class => [
			self::INSTANCE_ERROR,
		],
		ClassNameError::class => [ 
			self::NAME_ERROR
		]
	];
	
}

?>