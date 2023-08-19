<?php

namespace Yume\Fure\CLI\Command\Helper;

use Yume\Fure\CLI\Argument;
use Yume\Fure\CLI\Command;
use Yume\Fure\Util;

/*
 * Help
 * 
 * @extends Yume\Fure\CLI\Command\Command
 * 
 * @package Yume\Fure\CLI\Command\Helper
 */
final class Help extends Command\Command implements Command\CommandInterface
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
		"all" => [
			"type" => Util\Type::Bool,
			"alias" => "a",
			"explain" => "Display command with another info"
		],
		"command" => [
			"type" => Util\Type::Bool,
			"alias" => "c",
			"explain" => "Display command only"
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