<?php

namespace Yume\Fure\Error;

/*
 * AttributeError
 *
 * @extends Yume\Fure\Error\ClassError
 *
 * @package Yume\Fure\Error
 */
class AttributeError extends ClassError
{
	
	/*
	 * Error constant for instantiate invalid attribute.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const TYPE_ERROR = 68447;
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		AttributeError::class => [
			self::NAME_ERROR => "No attribute named {}",
			self::TYPE_ERROR => "Can't instantiate non-attribute class {}"
		]
	];
	
}

?>