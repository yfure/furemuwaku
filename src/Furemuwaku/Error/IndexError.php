<?php

namespace Yume\Fure\Error;

use Throwable;

/*
 * IndexError
 *
 * @extends Yume\Fure\Error\LookupError
 *
 * @package Yume\Fure\Error
 */
class IndexError extends LookupError
{
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		IndexError::class => [
			self::INDEX_ERROR => "Index {} out of range"
		]
	];
	
	/*
	 * @inherit Yume\Fure\Error\YumeError::__construct
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::INDEX_ERROR, ? Throwable $previous = Null, ? String $file = Null, ? Int $line = Null )
	{
		parent::__construct( $message, $code, $previous, $file, $line );
	}
	
}

?>