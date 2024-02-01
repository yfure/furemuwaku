<?php

namespace Yume\Fure\Util\RegExp;

use Stringable;

/*
 * Capture
 *
 * @package Yume\Fure\Util\RegExp
 */
final class Capture implements Stringable {
	
	public function __construct(
		public Readonly String $name,
		public Readonly String $value,
		public Readonly Int $index
	) {}
	
	/*
	 * Parse class to String.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function __toString(): String {
		return( $this )->value ?? "";
	}
	
}

?>