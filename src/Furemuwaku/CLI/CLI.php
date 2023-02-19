<?php

namespace Yume\Fure\CLI;

use Yume\Fure\App;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\Design;

/*
 * CLI
 *
 * @package Yume\Fure\CLI
 *
 * @extends Yume\Fure\Support\Design\Singleton
 */
final class CLI extends Design\Singleton
{
	
	private Readonly Argument $argument;
	private Readonly Commands $commands;
	
	protected function __construct()
	{
		$this->argument = new Argument;
		$this->commands = new Commands;
	}
	
	
	public function start(): Void
	{
		// ...
	}
	
	public function stop(): Void
	{}
	
}

?>