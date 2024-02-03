<?php

namespace Yume\Fure\Http\Header;

use Yume\Fure\Error;

/*
 * HeaderError
 * 
 * @extends Yume\Fure\Error\HttpError
 * 
 * @package Yume\Fure\Http\Header
 */
class HeaderError extends Error\HttpError {

	public const FOLDING_ERROR = 324292;
	public const KEYSET_ERROR = 453672;
	public const VALUE_ERROR = 672663;
	
	protected Array $flags = [
		HeaderError::class => [
			self::FOLDING_ERROR => "The header value must not generate a message that includes line folding",
			self::KEYSET_ERROR => "Invalid header name, the header name must be valid, {} provided",
			self::VALUE_ERROR => "Invalid or unsupported value of header {}"
		]
	];
}

?>