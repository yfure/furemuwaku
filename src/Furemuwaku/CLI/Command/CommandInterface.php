<?php

namespace Yume\Fure\CLI\Command;

use Yume\Fure\CLI\Argument;

/*
 * CommandInterface
 *
 * @package Yume\Fure\CLI\Command
 */
interface CommandInterface
{
	
	/*
	 * Get command abouts/ descriptions.
	 *
	 * @access Public
	 *
	 * @values Array
	 */
	public function getAbout(): ? String;
	
	/*
	 * Get command group name.
	 *
	 * @access Public
	 *
	 * @values String
	 */
	public function getGroup(): String;
	
	/*
	 * Get command name.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getName(): String;
	
	/*
	 * Get command options.
	 *
	 * @access Public
	 *
	 * @return Array
	 */
	public function getOptions(): Array;
	
	/*
	 * Get command usage.
	 *
	 * @access Public
	 *
	 * @return Array|String
	 */
	public function getUsage(): Array | Null | String;
	
	/*
	 * Handles a CLI command.
	 *
	 * @param Yume\Fure\CLI\Argument\Argument $argument
	 *
	 * @return Void
	 */
	public function run( Argument\Argument $argument ): Void;
	
}

?>