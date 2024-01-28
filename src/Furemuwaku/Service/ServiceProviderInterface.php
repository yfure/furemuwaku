<?php

namespace Yume\Fure\Service;

/*
 * ServiceProviderInterface
 * 
 * @package Yume\Fure\Service
 */
interface ServiceProviderInterface {
	
	/*
	 * Service booting.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function booting(): Void;
	
	/*
	 * Register new services.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function register(): Void;

}

?>