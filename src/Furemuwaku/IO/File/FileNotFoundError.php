<?php

namespace Yume\Fure\IO\File;

use Throwable;

/*
 * FileNotFoundError
 *
 * @extends Yume\Fure\IO\File\FileError
 *
 * @package Yume\Fure\IO\File
 */
final class FileNotFoundError extends FileError
{
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		FileNotFoundError::class => [
			self::NOT_FOUND_ERROR => "No such file {}"
		]
	];
	
	/*
	 * @inherit Yume\Fure\Error\YumeError::__construct
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = self::NOT_FOUND_ERROR, ? Throwable $previous = Null, ? String $file = Null, ? Int $line = Null )
	{
		parent::__construct( ...function_get_args() );
	}
	
}

?>