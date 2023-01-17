<?php

namespace Yume\Fure\Support\Services;

use Yume\Fure\Support\Design;

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
	 * Services
	 *
	 * @access Static Private
	 *
	 * @values Array
	 */
	static private Array $services = [];
	
	/*
	 * Get services.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $name
	 *
	 * @return Object
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
				$service = $service();
			}
			return( $service );
		}
		throw new ServiceLookupError( $name );
	}
	
	/*
	 * Register new services.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $name
	 * @params Object|Callable $callback
	 * @params Bool $override
	 *
	 * @return Void
	 */
	public static function register( Object | String $name, Callable | Object $callback, Bool $override = True ): Void
	{
		// Check if name is object type.
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

?>