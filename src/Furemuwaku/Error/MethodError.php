<?php

namespace Yume\Fure\Error;

/*
 * MethodError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\ConstantError
 */
class MethodError extends ConstantError
{
	
	/*
	 * Error constant for when failed invoke method.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const INVOKE_ERROR = 75457;
	
	/*
	 * @inherit Yume\Fure\Error\ConstantError
	 *
	 */
	protected Array $flags = [
		MethodError::class => [
			self::ACCESS_ERROR,
			self::NAME_ERROR,
			self::INVOKE_ERROR
		]
	];
	
}

?>