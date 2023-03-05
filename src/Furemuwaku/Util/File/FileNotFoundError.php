<?php

namespace Yume\Fure\Util\File;

use Throwable;

/*
 * FileNotFoundError
 *
 * @package Yume\Fure\Util\File
 *
 * @extends Yume\Fure\Util\File\FileError
 */
class FileNotFoundError extends FileError
{
	
	/*
	 * @inherit Yume\Fure\Util\File\FileError
	 *
	 */
	protected Array $flags = [
		FileNotFoundError::class => [
			self::NOT_FOUND_ERROR
		]
	];
	
	/*
	 * @inherit Yume\Fure\Util\File\FileError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = parent::NOT_FOUND_ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( $message, $code, $previous );
	}
	
}

?>