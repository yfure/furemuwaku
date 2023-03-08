<?php

namespace Yume\Fure\Error;

uss Throwable;

/*
 * EnumBackedError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\EnumUnitError
 */
class EnumBackedError extends EnumUnitError
{
	
	/*
	 * @inherit Yume\Fure\Error\UnumUnitError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::__ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( $messge, $code, $previous );
	}
	
}

?>