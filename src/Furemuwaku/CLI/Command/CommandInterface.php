<?php

namespace Yume\Fure\CLI\Command;

use Generator;

use Yume\Fure\CLI\Argument;

/*
 * CommandInterface
 *
 * @package Yume\Fure\CLI\Command
 */
interface CommandInterface
{
	
	/*
	 * Execute command.
	 *
	 * @param Yume\Fure\CLI\Argument\Argument $argument
	 *
	 * @return Void
	 */
	public function exec( Argument\Argument $argument ): Void;

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
	 * Return command option alias names.
	 * 
	 * @access Public
	 * 
	 * @return Generator
	 */
	public function getOptionAliases(): Generator;

	/*
	 * Return required command options.
	 * 
	 * @access Public
	 * 
	 * @return Generator
	 */
	public function getOptionRequires(): Generator;

	/*
	 * Return if command has option.
	 * 
	 * @access Public
	 * 
	 * @params String $option
	 * @params Bool $optional
	 * 
	 * @return Bool
	 */
	public function hasOption( String $option, ? Bool $optional = Null ): Bool;

	/*
	 * Return if command has options.
	 * 
	 * @access Public
	 * 
	 * @params Bool $optional
	 * 
	 * @return Bool
	 */
	public function hasOptions( ? Bool $optional = Null ): Bool;

	/*
	 * Return if command has require option.
	 * 
	 * @access Public
	 * 
	 * @params Bool $optional
	 * 
	 * @return Bool
	 */
	public function hasOptionRequires( ? Bool $optional = Null ): Bool;

	/*
	 * Return if option is required.
	 * 
	 * @access Public
	 * 
	 * @params String $option
	 * @params Bool $optional
	 * 
	 * @return Bool
	 */
	public function isOptionRequired( String $option, ? Bool $optional = Null ): Bool;
	
}

?>