<?php

namespace Yume\Fure\Error;

/*
 * ClassError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\ReflectError
 */
class ClassError extends ReflectError
{
	
	/*
	 * Error constant for invalid class.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const IMPLEMENTS_ERROR = 57822;
	
	/*
	 * Error constant for uninstantiable class.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const INSTANCE_ERROR = 67887;
	
	/*
	 * @inherit Yume\Fure\Error\ReferenceError
	 *
	 */
	public const NAME_ERROR = ReferenceError::NAME_ERROR;
	
	/*
	 * @inherit Yume\Fure\Error\ReflectError
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