<?php

namespace Yume\Fure\Support\Env;

use Yume\Fure\Support;

/*
 * EnvInterface
 *
 * @package Yume\Fure\Support\Env
 */
interface EnvInterface
{
	
	/*
	 * Get all environment variables.
	 *
	 * @access Public
	 *
	 * @return Yume\Fure\Support\Data\DataInterface;
	 */
	public function getAll( Bool $type = False ): Support\Data\DataInterface;
	
	/*
	 * Get environment variable value.
	 *
	 * @access Public
	 *
	 * @params String $env
	 *
	 * @return Mixed
	 */
	public function getEnv( String $env ): Mixed;
	
	/*
	 * Get environment file name.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getFilename(): String;
	
	/*
	 * Load and parse environmen file.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function load(): Void;
	
}

?>