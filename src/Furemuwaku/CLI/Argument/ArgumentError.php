<?php

namespace Yume\Fure\CLI\Argument;

use Yume\Fure\CLI;

/*
 * ArgumentError
 *
 * @package Yume\Fure\CLI\Argument
 *
 * @extends Yume\Fure\CLI\CLIError
 */
class ArgumentError extends CLI\CLIError
{
	
	public const JSON_VALUE_ERROR = 128282;
	
	public const SHORT_OPTION_ERROR = 133245;
	
	public const SET_ERROR = 145772;
	
	public const UNSET_ERROR = 146272;
	
	/*
	 * @inherit Yume\Fure\CLI\CLIError
	 *
	 */
	protected Array $flags = [
		ArgumentError::class => [
			self::JSON_VALUE_ERROR,
			self::SHORT_OPTION_ERROR,
			self::SET_ERROR,
			self::UNSET_ERROR
		],
		ArgumentJsonValueError::class => [
			self::JSON_VALUE_ERROR
		],
		ArgumentShortOptionError::class => [
			self::SHORT_OPTION_ERROR
		]
	];
	
}

?>