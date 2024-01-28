<?php

namespace Yume\Fure\Service;

use Yume\Fure\Util;

/*
 * ServiceProvider
 * 
 * @package Yume\Fure\Service
 */
abstract class ServiceProvider implements ServiceProviderInterface {

	/*
	 * Services container.
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
	 * @params Object $callback
	 * @params Bool $override
	 *
	 * @return Yume\Fure\Support\Service\ServiceProvider
	 */
	protected function bind( Array | Object | String $name, Object $callback, Bool $override = False ): ServiceProvider {
		if( is_array( $name ) ) {
			foreach( $name As $key ) {
				$this->bind( $key, $callback, $override );
			}
		}
		else {
			$name = is_object( $name ) ? $name::class : $name;
			$this->services[$name] = [
				"name" => $name,
				"callback" => $callback,
				"override" => $override
			];
		}
		return( $this );
	}

	/*
	 * @inherit Yume\Fure\Support\Service\ServiceInterface
	 */
	public function booting(): Void {
		Util\Arrays::map( $this->services, fn( $i, $name, $service ) => Service::register( ...$service ) );
	}

}

?>