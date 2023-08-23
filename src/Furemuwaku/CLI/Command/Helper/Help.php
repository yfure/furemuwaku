<?php

namespace Yume\Fure\CLI\Command\Helper;

use Yume\Fure\CLI\Argument;
use Yume\Fure\CLI\Command;
use Yume\Fure\CLI\Command\CommandInterface;
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
	{
		// Command group container.
		$groups = [];

		// Grouping commands.
		$this->commands->getAll()->map(
			
			/*
			 * Mapper handler.
			 * 
			 * @params Int $i
			 * @params String $name
			 * @params Yume\Fure\CLI\Command\CommandInterface $command
			 * 
			 * @return Void
			 */
			function( Int $i, String $name, CommandInterface $command ) use( &$groups )
			{
				$groups[$command->getGroup()] ??= [];
				$groups[$command->getGroup()][$name] = $this->handler( $command );
			}
		);

		putln( colorize( "\e[0m\e[1;37mThe Yume Framework v{config(\\{\\})}", "app.version" ) );
		putln( colorize( "\e[0m\e[1;37mThe Sasayaki Command Line Interface" ) );

		foreach( $groups As $group => $commands )
		{
			putln( $group );
			foreach( $commands As $name => $command )
			{}
		}
	}

	/*
	 * Grouping command by command group name.
	 * 
	 * @access Private
	 * 
	 * @params Yume\Fure\CLI\Command\CommandInterface $command
	 * 
	 * @return Array<Mixed>
	 */
	private function handler( CommandInterface $command ): Array
	{
		return([]);
	}

}

?>