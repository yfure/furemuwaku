<?php

namespace Yume\Fure\Util\RegExp;

use Yume\Fure\Util\Arr;

/*
 * Matches
 *
 * @package Yume\Fure\Util\RegExp
 */
final class Matches extends Arr\Lists {
	
	/*
	 * @inherit Yume\Fure\Util\Arr\Lists
	 *
	 */
	public function __construct( Array $matches, public Readonly Arr\Associative $groups, public Readonly Int $position, public Readonly Int $lastPosition ) {
		parent::__construct( $matches );
	}
	
}

?>