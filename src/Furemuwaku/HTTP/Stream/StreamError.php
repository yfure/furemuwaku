<?php

namespace Yume\Fure\HTTP\Stream;

use Yume\Fure\HTTP;

/*
 * Stream
 *
 * @package Yume\Fure\HTTP\Stream
 *
 * @extends Yume\Fure\HTTP\HTTPError
 */
class StreamError extends HTTP\HTTPError
{
	
	public const STRINGIFY_ERROR = 54688;
	
	/*
	 * @inherit Yume\Fure\HTTP\HTTPError
	 *
	 */
	protected Array $flags = [
		StreamError::class => [
			self::STRINGIFY_ERROR
		]
	];
	
}

?>