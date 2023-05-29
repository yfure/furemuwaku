<?php

namespace Yume\Fure\Support;

/*
 * Factory
 *
 * @package Yume\Fure\Support
 */
abstract class Factory
{
	
	/*
	 * Return object instance from factory.
	 *
	 * @access Public
	 *
	 * @return Object
	 */
	abstract public function factory(): Object;
	
}

?>