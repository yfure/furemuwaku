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
	
	private Readonly Argument\Argument $argument;
	private Readonly Command\Commands $commands;
	
	use \Yume\Fure\Config\ConfigTrait;
	
	protected function __construct()
	{
		$this->argument = new Argument\Argument;
		$this->commands = new Command\Commands( logger() );
		$this->prepare();
	}
	
	private function prepare(): Void
	{
		// ...
	}
	
	public function start(): Void
	{
		$this->commands->run(
			$this->argument[0] ?? "help",
			$this->argument
		);
	}
	
	public function stop(): Void
	{}
	
}

?>