<?php

namespace Yume\Fure\Error;

use Throwable;

/*
 * EnumUnitError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\EnumError
 */
class EnumUnitError extends EnumError
{
	
	/*
	 * @inherit Yume\Fure\Error\EnumError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::__ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( $messge, $code, $previous );
	}
	
}

?>