<?php

namespace Yume\Fure\Error;

/*
 * MethodError
 *
 * @extends Yume\Fure\Error\ConstantError
 *
 * @package Yume\Fure\Error
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
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		MethodError::class => [
			self::ACCESS_ERROR => "Method {}::{} is not accessible from outsite class",
			self::IMPLEMENTS_ERROR => "The method {}::{} is unimplemented",
			self::INVOKE_ERROR => "Can't invoke method {}::{}, it's not accessible from outsite class",
			self::NAME_ERROR => "Class {} has no method named {}"
		]
	];
	
}

?>