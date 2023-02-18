<?php

namespace Yume\Fure\App;

use Yume\App\Tests;

use Yume\Fure\CLI;
use Yume\Fure\Config;
use Yume\Fure\Error;
use Yume\Fure\Error\Handler As ErrorHandler;
use Yume\Fure\HTTP;
use Yume\Fure\Locale;
use Yume\Fure\Secure;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\Design;
use Yume\Fure\Support\Package;
use Yume\Fure\Support\Path;
use Yume\Fure\Support\Reflect;
use Yume\Fure\Support\Services;
use Yume\Fure\Util;
use Yume\Fure\Util\Env;
use Yume\Fure\Util\Random;
use Yume\Fure\Util\RegExp;

/*
 * App
 *
 * @package Yume\Fure\App
 *
 * @extends Yume\Fure\Support\Design\Singleton
 */
final class App extends Design\Singleton
{
	
	/*
	 * Application configs.
	 *
	 * @access Private Readonly
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	private Readonly Data\DataInterface $configs;
	
	/*
	 * Configuration path saved.
	 *
	 * @access Private Readonly
	 *
	 * @values String
	 */
	private Readonly String $configPath;
	
	/*
	 * Application context.
	 *
	 * @access Private Readonly
	 *
	 * @values String
	 */
	private Readonly String $context;
	
	/*
	 * If application is running.
	 *
	 * @access Static Private
	 *
	 * @values Bool
	 */
	static private Bool $run = False;
	
	/*
	 * @inherit Yume\Fure\Support\Design\Singleton
	 *
	 */
	protected function __construct()
	{
		Util\Timer::calculate( "booting", function()
		{
			// Parse environment file.
			Env\Env::self();
			
			// Setup localization application.
			Locale\Locale::self();
			
			// Get application environment.
			$env = strtolower( env( "ENVIRONMENT" ) );
			
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
						
						default => throw new Error\LogicError( Util\Str::fmt( "Unknown application context for \"{}\"", YUME_CONTEXT ) )
					};
				}
				else {
					throw new Error\LogicError( "The application has no context" );
				}
				
				// Define application evironment.
				define( "YUME_ENVIRONMENT", $env === "development" ? YUME_DEVELOPMENT : YUME_PRODUCTION );
				
				// Import runtime settings file by environment.
				Package\Package::import( Util\Str::fmt( "{}/{}", Path\PathName::SYSTEM_BOOTING->value, $env ) );
			}
			else {
				throw new Error\LogicError( Util\Str::fmt( "The application environment must be development|production, \"{}\" given", $env ) );
			}
			
			// Set config collections.
			$this->configs = new Data\Data([]);
			$this->configPath = f( "{}/\{\}", Path\PathName::SYSTEM_CONFIG->value );
			
			// Set error handler.
			ErrorHandler\Handler::setup();
		});
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
		if( static::$run )
		{
			throw new Error\RuntimeError( "Unable to run the currently running application" );
		}
		else {
			
			// Set application as run.
			static::$run = True;
			
			// Setup application token.
			Secure\Token::self();
			
			// Run application services.
			Services\Services::self();
			
			// This is only testing!
			$test = new Tests\Test();
			$test->main();
		}
	}
	
	/*
	 * Get or import configuration.
	 *
	 * @access Public
	 *
	 * @params String $name
	 * @params Bool $import
	 *
	 * @return Mixed
	 *
	 * @throws Yume\Fure\Error\ValueError
	 */
	public function config( String $name, Bool $import = False ): Mixed
	{
		// Check if config is not empty.
		if( valueIsNotEmpty( $name ) )
		{
			// Split config name.
			$split = Util\Arr::ifySplit( $name );
			$split[0] = strtolower( $split[0] );
			
			// If the configuration has not been registered or if re-import is allowed.
			if( isset( $this->configs[$split[0]] ) === False || $import )
			{
				$this->configs[$split[0]] = Package\Package::import( f( $this->configPath, $split[0] ) );
			}
		}
		else {
			throw new Error\ValueError( "Unable to fetch or import configuration, configuration name is required" );
		}
		return( Util\Arr::ify( $split, $this->configs ) );
	}
	
	/*
	 * Get configuration path saved.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getConfigPath(): String
	{
		return( $this )->configPath;
	}
	
	/*
	 * Get current application context.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getContext(): String
	{
		return( $this )->context;
	}
	
	/*
	 * Return if application context is cli.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function isCli(): Bool
	{
		return( YUME_CONTEXT_CLI );
	}
	
	/*
	 * Return if application context is cli server.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function isCliServer(): Bool
	{
		return( YUME_CONTEXT_CLI_SERVER );
	}
	
	/*
	 * Return if application context is web.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function isWeb(): Bool
	{
		return( YUME_CONTEXT_WEB );
	}
	
	/*
	 * Return if application is currently running.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function isRun(): Bool
	{
		return( $this )->run;
	}
	
}

?>