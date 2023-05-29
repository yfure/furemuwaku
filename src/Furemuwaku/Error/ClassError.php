<?php

namespace Yume\Fure\Error;

/*
 * ClassError
 *
 * @extends Yume\Fure\Error\ReflectError
 *
 * @package Yume\Fure\Error
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
	 * Error constant for undeclared class name.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const NAME_ERROR = 68445;
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		ClassError::class => [
			self::IMPLEMENTS_ERROR => "Class {} must implement interface {}",
			self::INSTANCE_ERROR => "Unable to create new instance for class {}, it's not instantiable class", 
			self::NAME_ERROR => "No class named {}"
		],
		ClassImplementationError::class => [
			self::IMPLEMENTS_ERROR => "Class {} must implement interface {}"
		],
		ClassInstanceError::class => [
			self::INSTANCE_ERROR => "Unable to create new instance for class {}, it's not instantiable class"
		],
		ClassNameError::class => [ 
			self::NAME_ERROR => "No class named {}"
		]
	];
	
}

?>