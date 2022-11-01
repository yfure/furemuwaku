<?php

namespace Yume\Fure\App;

use Throwable;

use Yume\Fure\Error;
use Yume\Fure\HTTP;
use Yume\Fure\IO;
use Yume\Fure\Support;
use Yume\Fure\Util;
use Yume\Fure\View;

/*
 * App
 *
 * @package Yume\Fure\App
 */
final class App
{
	
	/*
	 * A collection of classes to bind to parameters.
	 *
	 * @access Private
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	private Support\Data\DataInterface $binding;
	
	/*
	 * A collection of configurations that have been loaded.
	 *
	 * @access Private
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	private Support\Data\DataInterface $configs;
	
	private String $context;
	
	private $response;
	
	/*
	 * Service class instance collection.
	 *
	 * @access Private
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	private Support\Data\DataInterface $services;
	
	private function __construct()
	{
		// New Data instance.
		$this->binding = new Support\Data\Data;
		$this->configs = new Support\Data\Data;
		
		// Testing package bind.
		$this->binding->__set( \Test::class, new \StdClass );
		
		try
		{
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
	
	/*
	 * Prevent the instance from being cloned.
	 *
	 * @access Protected
	 *
	 * @return Void
	 */
	private function __clone() {}
	
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
	public function __wakeup()
	{
		throw new Error\RuntimeError( f( "Cannot unserialize {}.", $this::class ) );
	}
	
	/*
	 * Bind class instance.
	 *
	 * @access Public
	 *
	 * @params String $package
	 * @params Object $instance
	 *
	 * @return Void
	 */
	public function bind( String $package, Object $instance ): Void
	{
		$this->binding->__set( $package, $instance );
	}
	
	public function binded( String $package ): False | Object
	{
		return( $this->binding )->__get( $package );
	}
	
	/*
	 * Return or import configuration.
	 *
	 * @access Public Static
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
			
			// If the configuration has not been registered or if re-import is allowed.
			if( $this->configs->__isset( $split[0] ) === False || $import )
			{
				$this->configs->__set( $split[0], Support\Package\Package::import( f( "/system/configs/{}", $split[0] ) ) );
			}
		}
		else {
			throw new Error\ArgumentError(
				code: Error\ArgumentError::VALUE_ERROR,
				message: "Configuration name can't be empty."
			);
		}
		return( Util\Arr::ify( $split, $this->configs ) );
	}
	
	/*
	 * Launch the application.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function run(): Void
	{
		// Check if application context has ben defined.
		if( defined( "YUME_CONTEXT" ) )
		{
			// Define application environment.
			define( "ENVIRONMENT", env( "ENVIRONMENT", "development" ) );
			
			// If appication environment name is invalid.
			if( ENVIRONMENT !== "development" && 
				ENVIRONMENT !== "prpduction" )
			{
				throw new Error\LogicError( "Unknown application environment named." );
			}
			
			// Set application context.
			$this->context = YUME_CONTEXT;
			
			try
			{
				// Import application configuration based environment.
				Support\Package\Package::import( f( "app/Runtime/{}.php", ENVIRONMENT ) );
			}
			catch( Error\ModuleError $e )
			{
				// Check if Throwable thrown is ModuleError instance.
				if( $e Instanceof Error\ImportError )
				{
					throw new Error\RuntimeError( f( "Something error when import app/Runtime/{}.php", ENVIRONMENT ), 0, $e );
				}
				throw new Error\RuntimeError( "Unknown application environment.", 0, $e );
			}
			
			// Run the entire service provider class.
			Support\Services\Services::self();
			
			// Testing.
			Support\Package\Package::import( "app/Tests/Test.php" );
		}
		else {
			throw new Error\LogicError( "Unknown application context." );
		}
	}
	
	/*
	 * Create new application instance.
	 *
	 * @access Public Static
	 *
	 * @return Static
	 */
	public static function create(): Static
	{
		// Check if application has instanced.
		if( self::created() )
		{
			throw new Error\LogicError( f( "Can't duplicate {} instance!", App\App::class ) );
		}
		else {
			Support\Services\Services::self( new Static );
		}
		return( Support\Services\Services::app() );
	}
	
	/*
	 * Check if application has instanced.
	 *
	 * @access Public Static
	 *
	 * @return Bool
	 */
	public static function created(): Bool
	{
		return( Support\Services\Services::app() Instanceof App );
	}
	
}

?>