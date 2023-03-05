<?php

namespace Yume\Fure\App;

use Yume\App\Tests;

use Yume\Fure\Error;
use Yume\Fure\Locale;
use Yume\Fure\Services;
use Yume\Fure\Support\Erahandora;
use Yume\Fure\Support\Design;
use Yume\Fure\Util\Env;
use Yume\Fure\Util\File\Path;
use Yume\Fure\Util\Package;
use Yume\Fure\Util\Reflect;
use Yume\Fure\Util\Timer;

/*
 * App
 *
 * @package Yume\Fure\App
 *
 * @extends Yume\Fure\Support\Design\Singleton
 */
final class App extends Design\Singleton
{
	
	static private Bool $booted = False;
	static private Bool $booting = False;
	static private Array $configs = [];
	static private Bool $running = False;
	static private Array $package = [];
	static private App $self;
	static private Array $services = [];
	
	use \Yume\Fure\App\AppDecoratorTrait;
	
	/*
	 * @inherit Yume\Fure\Support\Design\Singleton::__construct
	 *
	 */
	protected function __construct()
	{
		Timer\Timer::execute( "booting", fn() => $this->booting() );
	}
	
	/*
	 * Booting application.
	 *
	 * @access Private
	 *
	 * @return Void
	 */
	private function booting(): Void
	{
		static::$self = $this;
		static::$booting = True;
		static::$running = False;
		
		Env\Env::self()->parse();
		Locale\Locale::self()->setup();
		Package\Package::self()->load();
		
		// Get application environment.
		$env = strtolower( env( "ENVIRONMENT", "development" ) );
		
		// Check if valid application environment.
		if( $env === "development" || $env === "production" )
		{
			// Check if application has context.
			if( defined( "YUME_CONTEXT" ) )
			{
				// Set application context.
				$this->context = match( True )
				{
					YUME_CONTEXT_CLI => "cli",
					YUME_CONTEXT_CLI_SERVER => "cli-server",
					YUME_CONTEXT_WEB => "web",
					
					default => throw new Error\LogicError( sprintf( "Unknown application context for \"%s\"", YUME_CONTEXT ) )
				};
			}
			else {
				throw new Error\LogicError( "The application has no context" );
			}
			
			// Define application evironment.
			define( "YUME_ENVIRONMENT", $env === "development" ? YUME_DEVELOPMENT : YUME_PRODUCTION );
			
			// Import bootable settings file by environment.
			Package\Package::import( sprintf( "%s/%s", Path\Paths::SYSTEM_BOOTING->value, $env ) );
		}
		else {
			throw new Error\LogicError( sprintf( "The application environment must be development|production, \"%s\" given", $env ) );
		}
		
		Erahandora\Erahandora::self()->setup();
		Services\Services::self()->booting();
		
		static::$booted = True;
		static::$booting = False;
	}
	
	/*
	 * Run application.
	 *
	 * @access Public
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\Error\RuntimeError
	 */
	public function run(): Void
	{
		if( static::$running === False )
		{
			static::$running = True;
			
			$test = new Tests\Test;
			$test->main();
		}
		else {
			throw new Error\RuntimeError( "Unable to run the currently running application" );
		}
		static::$running = False;
	}
	
}

?>