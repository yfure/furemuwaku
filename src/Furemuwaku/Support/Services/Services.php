<?php

namespace Yume\Fure\Support\Services;

use Yume\Fure\App;
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
	 * Provider container.
	 *
	 * @access Private Readonly
	 *
	 * @values Array
	 */
	private Readonly Array $providers;
	
	/*
	 * @inherit Yume\Fure\Support\Design\Singleton
	 *
	 */
	protected function __construct( Bool $booting = False )
	{
		if( $booting )
		{
			$this->booting();
		}
	}
	
	/*
	 * Return if services is available.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $name
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public static function available( Object | String $name, ? Bool $optional = Null ): Bool
	{
		return( $optional === Null ? ( Bool ) App\App::service( $name ) : ( Bool ) App\App::service( $name ) === $optional );
	}
	
	/*
	 * Booting all Service Providers defined.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function booting(): Void
	{
		// Check if providers has initialized.
		if( Reflect\ReflectProperty::isInitialized( $this, "providers" ) ) return;
		
		$providers = [];
		
		// Getting Services Provider.
		config( "app" )->services->map( function( Int $i, Int $idx, $service ) use( &$providers )
		{
			// Check if service class is implement ServiceProviderInterface.
			if( Reflect\ReflectClass::isImplements( $service, ServiceProviderInterface::class, $reflect ) )
			{
				// Get services provide instance.
				$provider = $reflect->newInstance();
				
				// Call register and booting methods.
				Reflect\ReflectMethod::invoke( $provider, "register" );
				Reflect\ReflectMethod::invoke( $provider, "booting" );
			}
			else {
				throw new Error\ClassImplementationError([ $service, ServiceProviderInterface::class ]);
			}
			$providers[] = $provider;
		});
		$this->providers = $providers;
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
		if( $service = App\App::service( $name ) )
		{
			if( is_callable( $service ) )
			{
				return( call_user_func( $service['callback'] ) );
			}
			return( $service['callback'] );
		}
		throw new ServicesLookupError( is_object( $name ) ? $name::class : $name );
	}
	
	/*
	 * Register new services.
	 *
	 * @access Public Static
	 *
	 * @params Array|Object|String $name
	 * @params Object $callback
	 * @params Bool $override
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\Support\Services\ServicesOverrideError
	 */
	public static function register( Array | Object | String $name, Object $callback, Bool $override = True ): Void
	{
		// Check if name is Array type.
		if( is_array( $name ) )
		{
			// Mapping service name.
			Util\Arr::map( $name, fn( Int $i, Int | String $idx, $name ) =>
			
				// Register services.
				self::bind( callback: $callback, override: $override, name: match( True )
				{
					// If service name is valid name.
					is_object( $name ),
					is_string( $name ) => $name,
					
					// If service name type is invalid.
					default => throw new ServicesError( $name, ServiceError::NAME_ERROR )
				})
			);
			return;
		}
		App\App::service( ...[
			"name" => $name,
			"callback" => $callback,
			"override" => $override
		]);
	}
	
}

?>