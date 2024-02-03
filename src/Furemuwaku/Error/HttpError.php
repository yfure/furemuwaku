<?php

namespace Yume\Fure\Error;

/*
 * HTTPError
 *
 * @extends Yume\Fure\Error\YumeError
 *
 * @package Yume\Fure\Error
 */
class HttpError extends YumeError {

	public const METHOD_ERROR = 276235;
	public const STATUS_ERROR = 263637;

	/*
	 * @inherit Yume\Fure\Error\YumeError::$flags
	 *
	 */
	protected Array $flags = [
		HttpError::class => [
			self::METHOD_ERROR => "Invalid or unsupported request method {}",
			self::STATUS_ERROR => "Invalid http status code {} response"
		]
	];

}

?>