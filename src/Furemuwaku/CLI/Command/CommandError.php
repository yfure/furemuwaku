<?php

namespace Yume\Fure\CLI\Command;

use Yume\Fure\CLI;

/*
 * CommandError
 * 
 * @extends Yume\Fure\CLI\CLIError
 * 
 * @package Yume\Fure\CLI\CLI
 */
class CommandError extends CLI\CLIError
{

	/*
	 * Error constant when command not found.
	 * 
	 * @access Public Static
	 * 
	 * @values Int
	 */
	public const NOT_FOUND_ERROR = 472672;

	/*
	 * Error constant when required option is not available.
	 * 
	 * @access Public Static
	 * 
	 * @values Int
	 */
	public const OPTION_REQUIRE_ERROR = 525368;

	/*
	 * Error constant when value of option is invalid.
	 * 
	 * @access Public Static
	 * 
	 * @values Int
	 */
	public const OPTION_VALUE_ERROR = 538387;

	/*
	 * Error constant when the command does
	 * not initialize command name.
	 * 
	 * @access Public Static
	 * 
	 * @values Int
	 */
	public const UNITIALIZE_NAME_ERROR = 665456;

	/*
	 * @inherit Yume\Fure\Error\YumeError::$flags
	 * 
	 */
	protected Array $flags = [
		CommandError::class => [
			self::NOT_FOUND_ERROR => "Command {} not found",
			self::OPTION_REQUIRE_ERROR => "Command {} require option {}",
			self::OPTION_VALUE_ERROR => "Option {} of command {} must be type {}, {} given",
			self::UNITIALIZE_NAME_ERROR => "Unitialize coomand name for class {}"
		],
		CommandNotFoundError::class => [
			self::NOT_FOUND_ERROR => "Command {} not found"
		],
		CommandOptionRequireError::class => [
			self::OPTION_REQUIRE_ERROR => "Command {} require option {}"
		],
		CommandOptionValueError::class => [
			self::OPTION_VALUE_ERROR => "Option {} of command {} must be type {}, {} given"
		],
		CommandUnitializeNameError::class => [
			"Unitialize coomand name for class {}"
		]
	];

}

?>