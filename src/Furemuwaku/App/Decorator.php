<?php

namespace Yume\Fure\App;

use Yume\Fure\Error;
use Yume\Fure\Support\Path;
use Yume\Fure\Support\Package;
use Yume\Fure\Support\Reflect;
use Yume\Fure\Support\Services;
use Yume\Fure\Util;

/*
 * Decorator
 *
 * @package Yume\Fure\App
 */
trait Decorator
{
	
	/*
	 * Get or import configuration.
	 *
	 * @access Public Static
	 *
	 * @params String $name
	 * @params Bool $import
	 *
	 * @return Mixed
	 *
	 * @throws Yume\Fure\Error\ValueError
	 */
	public static function config( String $name, Bool $import = False ): Mixed
	{
		if( valueIsNotEmpty( $name ) )
		{
			// Split config name.
			$split = Util\Arr::ifySplit( $name );
			
			// Normalize configuration name.
			$split[0] = strtolower( $split[0] );
			
			// If the configuration has not been registered or if re-import is allowed.
			if( isset( static::$configs[$split[0]] ) === False || $import )
			{
				static::$configs[$split[0]] = Package\Package::import( sprintf( "%s/%s", Path\PathName::SYSTEM_CONFIG->value, $split[0] ) );
			}
			return( Util\Arr::ify( $split, static::$configs ) );
		}
		throw new Error\ValueError( "Unable to fetch or import configuration, configuration name is required" );
	}
	
	/*
	 * Return application instance.
	 *
	 * @access Public Static
	 *
	 * @return Yume\Fure\App\App
	 *
	 * @throws Yume\Fure\Error\LogicError
	 */
	public static function context(): App
	{
		if( self::isBooted() ||
			self::isBooting() ||
			self::isRunning() )
		{
			return( static::$self );
		}
		throw new Error\LogicError( "Can't get application instance, because the application doesn't not initialized" );
	}
	
	/*
	 * Return if application has booted.
	 *
	 * @access Public Static
	 *
	 * @return Bool
	 */
	public static function isBooted(): Bool
	{
		return( static::$booted );
	}
	
	/*
	 * Return if application is on booting process.
	 *
	 * @access Public Static
	 *
	 * @return Bool
	 */
	public static function isBooting(): Bool
	{
		return( static::$booting );
	}
	
	/*
	 * Return if application is running.
	 *
	 * @access Public Static
	 *
	 * @return Bool
	 */
	public static function isRunning(): Bool
	{
		return( static::$running );
	}
	
	/*
	 * Return if application context is cli.
	 *
	 * @access Public Static
	 *
	 * @return Bool
	 */
	public static function isCli(): Bool
	{
		return( YUME_CONTEXT_CLI );
	}
	
	/*
	 * Return if application context is cli server.
	 *
	 * @access Public Static
	 *
	 * @return Bool
	 */
	public static function isCliServer(): Bool
	{
		return( YUME_CONTEXT_CLI_SERVER );
	}
	
	/*
	 * Return if application context is web.
	 *
	 * @access Public Static
	 *
	 * @return Bool
	 */
	public static function isWeb(): Bool
	{
		return( YUME_CONTEXT_WEB );
	}
	
	/*
	 * Get or Register new services.
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $name
	 * @params Object $callback
	 * @params Bool $override
	 *
	 * @return Array
	 *
	 * @throws Yume\Fure\Support\Services\ServicesOverrideError
	 */
	public static function service( Object | String $name, Null | Object $callback = Null, Bool $override = True ): ? Array
	{
		if( is_object( $name ) )
		{
			$name = $name::class;
		}
		if( $callback !== Null )
		{
			// Check if services is exists.
			if( isset( static::$services[$name] ) )
			{
				// Check if services is not overrideable.
				if( static::$services[$name]['override'] === False )
				{
					throw new Services\ServicesOverrideError( $name );
				}
			}
			static::$services[$name] = [
				"name" => $name,
				"callback" => $callback,
				"override" => $override
			];
		}
		return( static::$services[$name] ?? Null );
	}
	
}

?>