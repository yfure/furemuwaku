<?php

namespace Yume\Fure\CLI\Command\Helper;

use Yume\Fure\CLI\Argument;
use Yume\Fure\CLI\Command;
use Yume\Fure\Util;

/*
 * Helper
 * 
 * @extends Yume\Fure\CLI\Command\Command
 * 
 * @package Yume\Fure\CLI\Command\Helper
 */
final class Helper extends Command\Command implements Command\CommandInterface
{

	/*
	 * @inherit Yume\Fure\CLI\Command\Command::$name
	 * 
	 */
	protected String $name = "help";

	/*
	 * @inherit Yume\Fure\CLI\Command\Command::$options
	 * 
	 */
	protected Array $options = [
		"help" => [
			"type" => Util\Type::Bool,
			"alias" => "h",
			"require" => False,
			"example" => [
				"--help",
				"-h"
			]
		]
	];

	/*
	 * @inherit Yume\Fure\CLI\Command\CommandInterface::exec
	 * 
	 */
	public function exec( Argument\Argument $argument ): Void
	{}

}

?>