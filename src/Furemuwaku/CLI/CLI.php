<?php

namespace Yume\Fure\CLI;

use Yume\Fure\CLI\Argument;
use Yume\Fure\CLI\Command;
use Yume\Fure\Main;
use Yume\Fure\Support;

/*
 * CLI
 *
 * @extends Yume\Fure\Support\Singleton
 *
 * @package Yume\Fure\CLI
 */
class CLI extends Support\Singleton {
	
	/*
	 * Instance of class Main.
	 *
	 * @access Protected Readonly
	 *
	 * @values Yume\Fure\Main\Main
	 */
	protected Readonly Main\Main $app;
	
	/*
	 * Instance of class Argument.
	 *
	 * @access Public Readonly
	 *
	 * @values Yume\Fure\CLI\Argument\Argument
	 */
	public Readonly Argument\Argument $argument;
	
	/*
	 * Instance of class Commands.
	 *
	 * @access Public Readonly
	 *
	 * @values Yume\Fure\CLI\Command\Commands
	 */
	public Readonly Command\Commands $commands;
	
	/*
	 * Default command when there is not command passed.
	 *
	 * @access Private
	 *
	 * @values String
	 */
	private String $command = "help";
	
	/*
	 * @inherit Yume\Fure\Support\Singleton::__construct
	 *
	 */
	protected function __construct( ? Main\Main $app = Null ) {
		$this->app = $app ?? Main\Main::self();
		$this->argument = new Argument\Argument;
		$this->commands = new Command\Commands( logger() );
	}
	
	/*
	 * Starting command line interface.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function start(): Void {
		$this->commands->exec(
			$this->argument->command ?? $this->command,
			$this->argument
		);
	}
	
}

?>
