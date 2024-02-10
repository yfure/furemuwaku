<?php

namespace Yume\Fure\Support;

use Yume\Fure\Error;

/*
 * Uninitialize
 * 
 * Disable Initialize Class.
 * 
 * @package Yume\Fure\Support
 */
abstract class Uninitialize {

	/*
	 * Construct method of class Uninitialize.
	 * 
	 * @access Protected
	 * 
	 * @return Void
	 */
	final protected function __construct() {
		throw new Error\ClassInstanceError( __CLASS__, Error\ClassInstanceError::INSTANCE_ERROR );
	}

}

?>