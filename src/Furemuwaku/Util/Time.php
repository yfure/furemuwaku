<?php

namespace Yume\Fure\Util;

use Yume\Fure\Support\Data;
use Yume\Fure\Support\Design;

/*
 * Time
 *
 * @package Yume\Fure\Util
 *
 * @extends Yume\Fure\Support\Design\Singleton
 */
final class Time extends Design\Singleton
{
	
	/*
	 * Instance of class DataInterface.
	 *
	 * This is on for stored compilation time.
	 *
	 * @access Public Readonly
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	public Readonly Data\DataInterface $times;
	
	/*
	 * @inherit Yume\Fure\Support\Design\Singleton
	 *
	 */
	protected function __construct()
	{
		$this->times = new Data\Data([
			"app" => [],
			"booting" => [],
			"bootstrap" => [],
			"env" => [],
			"render" => [],
			"routing" => []
		]);
	}
	
	public static function calculate( String $name, Callable $)
	{
	}
	
}

?>