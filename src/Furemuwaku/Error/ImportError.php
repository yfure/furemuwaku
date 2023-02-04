<?php

namespace Yume\Fure\Error;

use Throwable;

/*
 * ImportError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\ModuleError
 */
class ImportError extends ModuleError
{
	
	/*
	 * @inherit Yume\Fure\Error\ModuleError
	 *
	 */
	protected Array $flags = [
		ImportError::class => [
			self::IMPORT_ERROR
		]
	];
	
	/*
	 * @inherit Yume\Fure\Error\ModuleError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::IMPORT_ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( $message, $code, $previous );
	}
	
}

?>