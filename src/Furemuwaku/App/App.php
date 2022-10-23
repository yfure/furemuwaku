<?php

namespace Yume\Fure\App;

use Yume\Fure\Support;
use Yume\Fure\Util;

/*
 * App
 *
 * @extends Yume\Fure\Support\Design\Singleton
 *
 * @package Yume\Fure\App
 */
final class App extends Support\Design\Creational\Singleton
{
	
	/*
	 * @inherit Yume\Fure\App\Design\Singleton
	 *
	 */
	protected function __construct()
	{
		echo Support\Package\Package::import( parent::class );
	}
	
}

?>