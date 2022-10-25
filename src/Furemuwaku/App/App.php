<?php

namespace Yume\Fure\App;

use Error;
use Exception;
use Throwable;

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
		try
		{
			// Load application environment variables.
			Support\Env\Env::self()->load();
		}
		catch( Throwable $e )
		{
			exit( path( remove: True, path: f( "<pre>{}: {} in file {} on line {}\n{}", ...[
				$e::class,
				$e->getMessage(),
				$e->getFile(),
				$e->getLine(),
				$e->getTrace()
			])));
		}
	}
	
	public function run(): Void
	{
		
	}
	
}

?>