<?php

namespace Yume\Fure\Support\Services;

use Yume\Fure\App;
use Yume\Fure\Util;

/*
 * ServiceProvider
 *
 * @package Yume\Fure\Support\Services
 */
abstract class ServiceProvider implements ServiceProviderInterface
{
	
	/*
	 * Services
	 *
	 * @access Protected
	 *
	 * @values Array
	 */
	protected Array $services = [];
	
	/*
	 * Service binding.
	 *
	 * @access Protected
	 *
	 * @params Array|Object|String $name
	 * @params Object|Callable $callback
	 * @params Bool $override
	 *
	 * @return Yume\Fure\Support\Services\ServiceProviderInterface
	 */
	protected function bind( Array | Object | String $name, Callable | Object $callback, Bool $override = False ): ServiceProviderInterface
	{
		// Check if name is Array type.
		if( is_array( $name ) )
		{
			// Mapping service name.
			Util\Arr::map( $name, function( Int $i, $idx, $name ) use( $callback, $override )
			{
				// Register services.
				$this->bind( callback: $callback, override: $override, name: match( True )
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
			
			// Set services.
			$this->services[$name] = [
				"name" => $name,
				"callback" => $callback,
				"override" => $override
			];
		}
		return( $this );
	}
	
	/*
	 * @inherit Yume\Fure\Support\Services\ServicesInterface
	 */
	public function booting(): Void
	{
		Util\Arr::map( $this->services, fn( $i, $name, $services ) => Services::register( ...$services ) );
	}
	
}

?>