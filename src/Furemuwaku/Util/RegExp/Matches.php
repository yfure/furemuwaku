<?php

namespace Yume\Fure\Util\RegExp;

use Yume\Fure\Util\Array;

/*
 * Matches
 *
 * @package Yume\Fure\Util\RegExp
 */
final class Matches extends Array\Lists
{
	
	/*
	 * @inherit Yume\Fure\Util\Array\Lists
	 *
	 */
	public function __construct( Array $matches, public Readonly Array\Associative $groups, public Readonly Int $position )
	{
		parent::__construct( $matches );
	}
	
}

?>