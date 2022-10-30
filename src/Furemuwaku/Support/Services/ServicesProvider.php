<?php

namespace Yume\Fure\Support\Services;

use Yume\Fure\App;

/*
 * ServicesProvider
 *
 * @package Yume\Fure\Support\Services
 */
abstract class ServicesProvider
{
	
	/*
	 * Construct method of class ServicesProvider
	 *
	 * @access Public Instance
	 *
	 * @params Yume\Fure\App\App $app
	 *
	 * @return Void
	 */
	public function __construct( protected App\App $app )
	{
		// ...
	}
	
	/*
	 * Services provider booting.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function boot(): Void
	{
		// ...
	}
	
	/*
	 * Services provider register.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function register(): Void
	{
		// ...
	}
	
}

?>