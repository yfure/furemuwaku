<?php

namespace Yume\Fure\Error;

use Throwable;

/*
 * ModuleNotFounfError
 *
 * @extends Yume\Fure\Error\ModuleError
 *
 * @package Yume\Fure\Error
 */
final class ModuleNotFoundError extends ModuleError
{
	/*
	 * @inherit Yume\Fure\Error\YumeError::__construct
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::NOT_FOUND_ERROR, ? Throwable $previous = Null, ? String $file = Null, ? Int $line = Null )
	{
		parent::__construct( $message, $code, $previous, $file, $line );
	}
}

?>
