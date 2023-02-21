<?php

namespace Yume\Fure\App;

use Yume\App\Tests;

use Yume\Fure\Error;
use Yume\Fure\Error\Erahandora;
use Yume\Fure\Locale;
use Yume\Fure\Support\Design;
use Yume\Fure\Support\Package;
use Yume\Fure\Support\Path;
use Yume\Fure\Support\Reflect;
use Yume\Fure\Support\Services;
use Yume\Fure\Util;
use Yume\Fure\Util\Env;

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
	static private Array $composer = [];
	static private Array $configs = [];
	static private Bool $running = False;
	static private Array $package = [];
	static private App $self;
	static private Array $services = [];
	
	use \Yume\Fure\App\Decorator;
	
	/*
	 * @inherit Yume\Fure\Support\Design\Singleton::__construct
	 *
	 */
	protected function __construct()
	{
		static::$self = $this;
		static::$booting = True;
		static::$running = False;
		
		$this->booting();
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
		Util\Timer::calculate( "booting", function(): Void
		{
			// Parses content from environment files.
			Env\Env::self()->parse();
			
			// Set application localization settings.
			Locale\Locale::self()->setup();
			
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
				Package\Package::import( sprintf( "%s/%s", Path\PathName::SYSTEM_BOOTING->value, $env ) );
			}
			else {
				throw new Error\LogicError( sprintf( "The application environment must be development|production, \"%s\" given", $env ) );
			}
			
			// Setup error handler.
			Erahandora\Erahandora::self()->setup();
			
			// Booting services provider.
			Services\Services::self()->booting();
		});
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
		if( static::$running )
		{
			throw new Error\RuntimeError( "Unable to run the currently running application" );
		}
		static::$running = True;
	}
	
}

?>