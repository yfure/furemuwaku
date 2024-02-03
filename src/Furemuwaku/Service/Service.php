<?php

namespace Yume\Fure\Service;

use Closure;

use Yume\Fure\Error;
use Yume\Fure\Support;
use Yume\Fure\Util\Reflect;

/*
 * Service
 * 
 * @extends Yume\Support\Singleton
 * 
 * @package Yume\Fure\Service
 */
final class Service extends Support\Singleton {

	/*
	 * Service providers container.
	 * 
	 * @access Protected
	 * 
	 * @values Array<Yume\Fure\Service\ServiceProviderInterface>
	 */
	protected Array $providers;

	/*
	 * Container for application services.
	 *
	 * @access Static Private
	 *
	 * @values Array<String,Object>
	 */
	static protected Array $services = [];

	/*
	 * @inherit Yume\Fure\Support\Singleton
	 * 
	 */
	protected function __construct( Bool $booting = False ) {
		if( $booting ) $this->booting();
	}

	/*
	 * Return if service is available.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $name
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public static function available( Object | String $name, ? Bool $optional = Null ): Bool {
		return( $optional !== Null ? self::available( $name ) === $optional : isset( static::$services[self::normalize( $name )] ) );
	}

	/*
	 * Booting all Service Providers defined.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function booting(): Void {
		
		// Skip if providers has initialized.
		if( Reflect\ReflectProperty::isInitialized( $this, "providers" ) ) return;
		
		$providers = [];
		$services = config( "app" )->services;
		$services->map( function( Int $i, Int $idx, $service ) use( &$providers ) {
			if( Reflect\ReflectClass::isImplements( $service, ServiceProviderInterface::class, $reflect ) ) {
				
				$provider = $reflect->newInstance();
				
				// Call register and booting methods.
				Reflect\ReflectMethod::invoke( $provider, "register" );
				Reflect\ReflectMethod::invoke( $provider, "booting" );
			}
			else {
				throw new Error\ClassImplementationError([ $service, ServiceProviderInterface::class ]);
			}
			$this->providers[] = $provider;
		});
		$this->providers = $providers;
	}

	/*
	 * Get service.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $name
	 * @params Mixed ...$args
	 *
	 * @return Object
	 *
	 * @throws Yume\Fure\Support\Services\ServiceLookupError
	 */
	public static function get( Object | String $name, Mixed ...$args ): Object {
		$name = self::normalize( $name );
		if( isset( static::$services[$name] ) ) {
			$service = static::$services[$name];
			if( $service['callback'] Instanceof Closure ) {
				return( call_user_func( $service['callback'], ...$args ) );
			}
			return( $service['callback'] );
		}
		throw new ServiceLookupError( $name );
	}

	public static function getAll() {
		return( static::$services );
	}

	/*
	 * Normalize service name.
	 * 
	 * @access Private Static
	 * 
	 * @params Object|String $name
	 * 
	 * @return String
	 */
	private static function normalize( Object | String $name ): String {
		return( is_object( $name ) && $name Instanceof Closure === False ? $name::class : $name );
	}

	/*
	 * Return if servuce is overrideable.
	 * 
	 * @access Public Static
	 * 
	 * @params Object|String $name
	 * @params Bool $optional
	 * 
	 * @return Bool
	 */
	public static function overideable( Object | String $name, ? Bool $optional = Null ): Bool {
		return( $optional !== Null ? self::overideable( $name ) === $optional : ( self::available( $name, True ) ? static::$services[self::normalize( $name )]['override'] : True ) );
	}

	/*
	 * Register new services.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $name
	 * @params Object $callback
	 * @params Bool $override
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\Support\Service\ServiceOverrideError
	 */
	public static function register( Object | String $name, Object $callback, Bool $override = True ): Void {
		$name = self::normalize( $name );
		if( self::overideable( $name, False ) ) {
			throw new ServiceOverrideError( $name );
		}
		static::$services[$name] = [
			"name" => $name,
			"callback" => $callback,
			"override" => $override
		];
	}

}

?>