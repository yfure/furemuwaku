<?php

namespace Yume\Fure\Util;

/*
 * Value
 * 
 * Initialize if object have value.
 * 
 * Initialize if the object has its own value, this will be useful
 * when passed to Yume's built-in Parameter Reflection, because Yume
 * will identify that the object is not the value passed but the value
 * contained in the object is the original value.
 * 
 * @package Yume\Fure\Util
 */
interface Value {

	/*
	 * Return value of object.
	 * 
	 * @access Public
	 * 
	 * @return Mixed
	 */
	public function getValue(): Mixed;

}

?>