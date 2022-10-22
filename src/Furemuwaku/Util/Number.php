<?php

namespace Yume\Fure\Util;

use Yume\Fure\Support;

/*
 * Number
 *
 * @package Yume\Fure\Util
 */
abstract class Number
{
	
	/*
	 * Check if string is number only.
	 *
	 * @access Public Static
	 *
	 * @params String $ref
	 *
	 * @return Bool
	 */
	public static function valid( String $ref ): Bool
	{
		return( Support\RegExp\RegExp::test( "/^(?:\d+)$/", $ref ) );
	}
	
}

?>