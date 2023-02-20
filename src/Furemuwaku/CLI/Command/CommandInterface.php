<?php

namespace Yume\Fure\CLI\Command;

/*
 * CommandInterface
 *
 * @package Yume\Fure\CLI\Command
 */
interface CommandInterface
{
	
	/*
	 * Handles a CLI command.
	 *
	 * @param Array $args
	 *
	 * @return Void
	 */
	public function run( Array $args ): Void;
	
}

?>