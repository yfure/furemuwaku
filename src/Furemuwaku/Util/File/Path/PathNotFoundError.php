<?php

namespace Yume\Fure\Util\File\Path;

use Throwable;

/*
 * PathNotFoundError
 *
 * @package Yume\Fure\Util\File\Path
 *
 * @extends Yume\Fure\Util\File\Path\PathError
 */
class PathNotFoundError extends PathError
{
	
	/*
	 * @inherit Yume\Fure\Util\File\Path\PathError
	 *
	 */
	protected Array $flags = [
		PathNotFoundError::class => [
			self::NOT_FOUND_ERROR
		]
	];
	
	/*
	 * @inherit Yume\Fure\Util\File\Path\PathError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = parent::NOT_FOUND_ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( $message, $code, $previous );
	}
	
}

?>