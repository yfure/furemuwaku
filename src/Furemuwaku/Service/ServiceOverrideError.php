<?php

namespace Yume\Fure\Service;

use Throwable;

/*
 * ServiceOverrideError
 *
 * @extends Yume\Fure\Service\ServiceError
 *
 * @package Yume\Fure\Service
 */
final class ServiceOverrideError extends ServiceError
{
	/*
	 * @inherit Yume\Fure\Error\YumeError::__construct
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::OVERRIDE_ERROR, ? Throwable $previous = Null, ? String $file = Null, ? Int $line = Null )
	{
		parent::__construct( $message, $code, $previous, $file, $line );
	}	
}

?>