<?php

namespace Yume\Fure\IO\Path;

use Throwable;

use Yume\Fure\IO\File;

/*
 * PathNotFoundError
 *
 * @extends Yume\Fure\IO\Path\PathError
 *
 * @package Yume\Fure\IO\Path
 */
final class PathNotFoundError extends PathError {
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		File\FileNotFoundError::class => [
			self::NOT_FOUND_ERROR => "No such directory {}"
		]
	];
	
	/*
	 * @inherit Yume\Fure\Error\YumeError::__construct
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::NOT_FOUND_ERROR, ? Throwable $previous = Null, ? String $file = Null, ? Int $line = Null ) {
		parent::__construct( $message, $code, $previous, $file, $line );
	}
	
}

?>