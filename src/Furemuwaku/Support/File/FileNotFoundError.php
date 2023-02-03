<?php

namespace Yume\Fure\Support\File;

use Throwable;

/*
 * FileNotFoundError
 *
 * @package Yume\Fure\Support\File
 *
 * @extends Yume\Fure\Support\File\FileError
 */
class FileNotFoundError extends FileError
{
	/*
	 * @inherit Yume\Fure\Support\File\FileError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = parent::NOT_FOUND_ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( ...func_get_args() );
	}
}

?>