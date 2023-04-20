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
 */
final class App
{
	
	/*
	 * Aplication has booted.
	 *
	 * @access Static Private
	 *
	 * @values Bool
	 */
	static private Bool $booted = False;
	
	/*
	 * Application on booting.
	 *
	 * @access Static Private
	 *
	 * @values Bool
	 */
	static private Bool $booting = False;
	
	/*
	 * Application config container.
	 *
	 * @access Static Private
	 *
	 * @values Array
	 */
	static private Array $configs = [];
	
	/*
	 * Application context.
	 *
	 * @access Static Private
	 *
	 * @values String
	 */
	static private String $context;
	
	/*
	 * Application on running.
	 *
	 * @access Static Private
	 *
	 * @values Bool
	 */
	static private Bool $running = False;
	
	/*
	 * Instance of class App.
	 *
	 * @access Static Private
	 *
	 * @values Yume\Fure\App\App
	 */
	static private App $self;
	
	/*
	 * Application service container.
	 *
	 * @access Static Private
	 *
	 * @values Array
	 */
	static private Array $services = [];
	
	use \Yume\Fure\App\AppDecoratorTrait;
	
	/*
	 * Construct method of class App.
	 * Is not allowed to call from outside to
	 * prevent from creating multiple instances.
	 *
	 * @access Protected
	 *
	 * @return Void
	 */
	protected function __construct()
	{
		Timer\Timer::execute( "booting", fn() => $this->booting() );
	}
	
	/*
	 * Prevent the instance from being cloned.
	 *
	 * @access Protected
	 *
	 * @return Void
	 */
	protected function __clone(): Void
	{}

	/*
	 * Prevent from being unserialized.
	 * Or which would create a second instance of it.
	 *
	 * @access Public
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\Error\RuntimeError
	 */
	public function __wakeup(): Void
	{
		throw new Error\RuntimeError( sprintf( "Cannot unserialize %s", $this::class ) );
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
				static::$context = match( True )
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
			Package\Package::import( sprintf( "%s/%s", Path\Paths::SystemBooting->value, $env ) );
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