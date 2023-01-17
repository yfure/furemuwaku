<?php

namespace Yume\Fure\Support\Services;

use Yume\Fure\Support\Design;
use Yume\Fure\Util;

/*
 * ServicesProvider
 *
 * @package Yume\Fure\Support\Services
 *
 * @extends Yume\Fure\Support\Design\Singleton
 */
abstract class ServicesProvider extends Design\Singleton implements ServicesProviderInterface
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
	 * @params Object|String $name
	 * @params Object|Callable $callback
	 * @params Bool $override
	 *
	 * @return Static
	 */
	protected function bind( Object | String $name, Callable | Object $callback, Bool $override = True ): ServicesProviderInterface
	{
		// Check if name is object type.
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
		
		return( $this );
	}
	
	/*
	 * @inherit Yume\Fure\Support\Services\ServicesInterface
	 */
	public function boot(): Void
	{
		Util\Arr::map( $this->services, fn( $i, $name, $service ) => Services::register( ...$services ) );
	}
	
}

?>