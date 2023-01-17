<?php

namespace Yume\Fure\App;

use Yume\Fure\Config;
use Yume\Fure\Error;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\Design;
use Yume\Fure\Support\Package;
use Yume\Fure\Support\Reflect;
use Yume\Fure\Support\Services;
use Yume\Fure\Util;
use Yume\Fure\Util\Env;
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
	private Data\DataInterface $configs;
	
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
		// Parse environment file.
		Env\Env::self();
		
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
			Package\Package::import( Util\Str::fmt( "/app/Runtime/{}", $env ) );
			
			// Register application.
			Services\Services::register( "app", $this, False );
			Services\Services::register( $this, $this, False );
		}
		else {
			throw new Error\LogicError( Util\Str::fmt( "The application environment must be development|production, \"{}\" given", $env ) );
		}
		
		// Check if application has no error handler.
		if( Env\Env::isset( "ERROR_HANDLER", False ) )
		{
			// Set default error handler into env file.
			Env\Env::set( "ERROR_HANDLER", "Yume\\Fure\\Handler\\Error::handler" );
		}
		
		// Check if application has no exception handler.
		if( Env\Env::isset( "EXCEPTION_HANDLER", False ) )
		{
			Env\Env::set( "EXCEPTION_HANDLER", "Yume\\Fure\\Handler\\Exception::handler" );
		}
		
		// Sets a user-defined error & exception handler function.
		set_error_handler( env( "ERROR_HANDLER" ) );
		set_exception_handler( env( "EXCEPTION_HANDLER" ) );
		
		// ...
		$this->configs = new Data\Data([]);
		$this->configPath = "/system/configs/{}";
	}
	
	/*
	 * Run application.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function run(): Void
	{
		// Check if the application is running.
		if( static::$run )
		{
			throw new Error\RuntimeError( "Unable to run the currently running application" );
		}
		else {
			
			// Set application as run.
			static::$run = True;
			
			// Check if application is running on cli mode.
			if( $this->isCli() )
			{
				echo 0;
			}
			else {
				
			}
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
	 * Return if applicaion context is cli.
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
	 * Return if applicaion context is cli server.
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
	 * Return if applicaion context is web.
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