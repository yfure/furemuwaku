<?php

namespace Yume\Fure\CLI;

use Yume\Fure\App;
use Yume\Fure\CLI\Argument;
use Yume\Fure\CLI\Command;
use Yume\Fure\Config;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\Design;
use Yume\Fure\Support\Reflect;

/*
 * CLI
 *
 * @package Yume\Fure\CLI
 *
 * @extends Yume\Fure\Support\Design\Singleton
 */
final class CLI extends Design\Singleton
{
	
	/*
	 * Instance of class Argument.
	 *
	 * @access Private Readonly
	 *
	 * @values Yume\Fure\CLI\Argument\Argument
	 */
	private Readonly Argument\Argument $argument;
	
	/*
	 * Instance of class Commands.
	 *
	 * @access Private Readonly
	 *
	 * @values Yume\Fure\CLI\Command\Commands
	 */
	private Readonly Command\Commands $commands;
	
	use \Yume\Fure\Config\ConfigTrait;
	
	/*
	 * @inherit Yume\Fure\Support\Design\Singleton::__construct
	 *
	 */
	protected function __construct()
	{
		$this->argument = new Argument\Argument;
		$this->commands = new Command\Commands( logger() );
		$this->prepare();
	}
	
	/*
	 * Coming soon!
	 *
	 * @access Private
	 *
	 * @return Void
	 */
	private function prepare(): Void
	{}
	
	/*
	 * Starting command line interface.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function start(): Void
	{
		$this->commands->run(
			$this->argument[0] ?? "help",
			$this->argument
		);
	}
	
	/*
	 * Closing command line interface.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function stop(): Void
	{
		puts( "{}: Finsih\n", $this->argument->file );
	}
	
}

?>