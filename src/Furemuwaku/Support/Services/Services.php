<?php

namespace Yume\Fure\Support\Services;

use Yume\Fure\Error;
use Yume\Fure\Config;
use Yume\Fure\Support\Design;
use Yume\Fure\Support\Reflect;
use Yume\Fure\Util;

/*
 * Services
 *
 * @package Yume\Fure\Support\Services
 *
 * @extends Yume\Fure\Support\Design\Singleton
 */
final class Services extends Design\Singleton
{
	
	/*
	 * Provide instances.
	 *
	 * @access Static Private
	 *
	 * @values Array
	 */
	static private Array $provides = [];
	
	/*
	 * Services instances.
	 *
	 * @access Static Private
	 *
	 * @values Array
	 */
	static private Array $services = [];
	
	use \Yume\Fure\Config\ConfigTrait;
	
	/*
	 * @inherit Yume\Fure\Support\Design\Singleton
	 *
	 */
	protected function __construct()
	{
		// Getting Services Provider.
		self::config( function( Config\Config $services )
		{
			// Mapping Services Provider.
			Util\Arr::map( $services, function( Int $i, Int $idx, $service )
			{
				// Check if service class is implement ServiceProviderInterface.
				if( Reflect\ReflectClass::isImplements( $service, ServiceProviderInterface::class, $reflect ) )
				{
					// Get services provide instance.
					static::$provides[] = $reflect->newInstance();
					
					// Call register and booting methods.
					Reflect\ReflectMethod::invoke( end( static::$provides ), "register" );
					Reflect\ReflectMethod::invoke( end( static::$provides ), "booting" );
				}
				else {
					throw new ClassError( [ $service ] );
				}
			});
		});
	}
	
	/*
	 * Get services.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $name
	 *
	 * @return Object
	 *
	 * @throws Yume\Fure\Support\Services\ServicesLookupError
	 */
	public static function get( Object | String $name ): Object
	{
		// Check if name is object type.
		if( is_object( $name ) )
		{
			$name = $name::class;
		}
		
		// Check if services is exists.
		if( isset( static::$services[$name] ) )
		{
			// Get service callback value.
			$service = static::$services[$name]['callback'];
			
			// Check if services is callable.
			if( is_callable( $service ) )
			{
				return( call_user_func( $service ) );
			}
			return( $service );
		}
		throw new ServicesLookupError( $name );
	}
	
	/*
	 * Register new services.
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $name
	 * @params Object|Callable $callback
	 * @params Bool $override
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\Support\Services\ServicesError
	 */
	public static function register( Array | Object | String $name, Callable | Object $callback, Bool $override = True ): Void
	{
		// Check if name is Array type.
		if( is_array( $name ) )
		{
			// Mapping service name.
			Util\Arr::map( $name, function( Int $i, $idx, $name ) use( $callback, $override )
			{
				// Register services.
				self::register( callback: $callback, override: $override, name: match( True )
				{
					// If service name is valid name.
					is_object( $name ),
					is_string( $name ) => $name,
					
					// If service name type is invalid.
					default => throw new ServicesError( $name, ServiceError::NAME_ERROR )
				});
			});
		}
		else {
			
			// Check if name is Object type.
			if( is_object( $name ) )
			{
				$name = $name::class;
			}
			
			// Check if services is exists.
			if( isset( static::$services[$name] ) )
			{
				// Check if services is not overrideable.
				if( static::$services[$name]['override'] === False )
				{
					throw new ServicesOverrideError( $name );
				}
			}
			
			// Set services.
			static::$services[$name] = [
				"name" => $name,
				"callback" => $callback,
				"override" => $override
			];
		}
	}
	
}

?>