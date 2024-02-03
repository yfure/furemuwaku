<?php

namespace Yume\Fure\Main;

use Throwable;

use Yume\Fure\Config;
use Yume\Fure\Error;
use Yume\Fure\Error\Erahandora;
use Yume\Fure\Http\Controller;
use Yume\Fure\Http\Request;
use Yume\Fure\Http\Response;
use Yume\Fure\Http\Router;
use Yume\Fure\IO\Path;
use Yume\Fure\Locale;
use Yume\Fure\Service;
use Yume\Fure\Support;
use Yume\Fure\Util;
use Yume\Fure\Util\Env;

/*
 * Main
 *
 * @package Yume\Fure\Main
 */
final class Main {
	
	/*
	 * Instance of class Main.
	 *
	 * @access Static Private
	 *
	 * @values Yume\Fure\Main\Main
	 */
	static private ?Main $context = Null;
	
	/*
	 * Container for imported configs.
	 *
	 * @access Static Private
	 *
	 * @values Array
	 */
	static private Array $configs = [];
	
	/*
	 * Instance of class Controller.
	 *
	 * @access Private
	 *
	 * @values Yume\Fure\Controller\ControllerInterface
	 */
	private Controller\ControllerInterface $controller;
	
	/*
	 * Application environment.
	 *
	 * @access Private
	 *
	 * @values String
	 */
	private String $environment;
	
	/*
	 * Identify if application is running.
	 *
	 * @access Static Private
	 *
	 * @values Bool
	 */
	static private Bool $running = False;
	
	/*
	 * Instance of class Request.
	 *
	 * @access Private
	 *
	 * @values Yume\Fure\Http\Request\RequestInterface
	 */
	private Request\RequestInterface $request;
	
	/*
	 * Instance of class Response.
	 *
	 * @access Private
	 *
	 * @values Yume\Fure\Http\Response\ResponseInterface
	 */
	private Response\ResponseInterface $response;
	
	/*
	 * Instance of class Router.
	 *
	 * @access Private
	 *
	 * @values Yume\Fure\Http\Router\RouterInterface
	 */
	private Router\RouterInterface $router;
	
	/*
	 * Construct method for class Main.
	 *
	 * @access Protected Initialize
	 *
	 * @return Void
	 */
	protected function __construct() {
		$this->initialize();
	}
	
	/*
	 * Prevent the instance from being cloned.
	 *
	 * @access Protected
	 *
	 * @return Void
	 */
	protected function __clone(): Void {
	}
	
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
	public function __wakeup(): Void {
		throw new Error\RuntimeError( sprintf( "Cannot unserialize %s", __CLASS__ ) );
	}
	
	/*
	 * Get or import configuration.
	 *
	 * @access Public Static
	 *
	 * @syntax configuration
	 * @syntax configuration.config...
	 *
	 * @params String $name
	 *  Configuration name.
	 * @params Mixed $optional
	 *  When you only need the value from the config of a
	 *  configuration it will override the value if the
	 *  config is not found in the configuration.
	 * @params Bool $shared
	 *  Allow configurations to be reused.
	 * @params Bool $import
	 *  Re-import configuration module.
	 *
	 * @return Mixed
	 *
	 * @throws Yume\Fure\Error\LookupError
	 * @throws Yume\Fure\Error\ValueError
	 */
	public static function config( String $name, Mixed $optional = Null, Bool $shared = True, Bool $import = False ): Mixed {
		if( valueIsNotEmpty( $name ) ) {
			$split = Util\Arrays::ifySplit( $name );
			$name = strtolower( array_shift( $split ) );
			$config = Null;
			if( isset( static::$configs[$name] ) && !$import ) {
				if( count( $configs = array_filter( static::$configs[$name], fn( Config\Config $config ) => $config->isSharedable() ) ) ) {
					$config = end( $configs );
				}
			}
			if( $config === Null ) {
				$config = Support\Package::import( join( "/", [ Path\Paths::SystemConfig->value, $name ] ) );
				if( $config Instanceof Config\Config === False ) {
					$config = new Config\Config( $name, $shared, $config );
				}
				static::$configs[$name][] = $config;
			}
			try {
				if( count( $split ) ) {
					return( Util\Arrays::ify( $split, $config ) );
				}
			}
			catch( Error\LookupError $e ) {
				if( $optional ) {
					return( $optional );
				}
				throw $e;
			}
			return( $config );
		}
		throw new Error\ValueError( "Unable to fetch or import configuration, configuration name is required" );
	}

	/*
	 * Initialize the appliaztion
	 * 
	 * @access Private
	 * 
	 * @return Void
	 */
	private function initialize(): Void {
		try {

			// Initialize installed packages.
			Support\Package::self();
			
			// Parse and load environment variables.
			Env\Env::self( Env\Env::DEFAULT )->parse();
			
			// Setup localization application.
			Locale\Locale::setLanguage();
			Locale\Locale::setTimezone();
			
			// Setup services.
			Service\Service::self()->booting();

			// Get application environment.
			$env = strtolower( env( "ENVIRONMENT", "development" ) );
			
			if( $env === "development" || $env === "production" ) {
				defined( "YUME_ENVIRONMENT" ) | define( "YUME_ENVIRONMENT", $env === "development" ? YUME_DEVELOPMENT : YUME_PRODUCTION );
			}
			else {
				throw new Error\LogicError( sprintf( "The application environment must be development|production, \"%s\" given", $env ) );
			}
			Support\Package::import( sprintf( "%s/%s", Path\Paths::SystemBooting->value, $env ) );
			Erahandora\Erahandora::setup();
		}
		catch( Throwable $e ) {
			e( $e );
		}
	}
	
	/*
	 * Yume's starting point to start running the Framework.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function main(): Void {
		if( static::$running ) {
			throw new Error\UnexpectedError( "Unable to run running application" );
		}

		// Set Application Status as Running.
		static::$running = True;

		// ...
		$this->request = new Request\Request();
		$this->response = new Response\Response();
	}
	
	/*
	 * Gets the instance via lazy initialization.
	 *
	 * @access Public Static
	 *
	 * @return Static
	 */
	public static function self(): Static {
		return( static::$context ??= new Static() );
	}
	
}

?>
