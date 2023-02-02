<?php

namespace Yume\Fure\View;

use Yume\Fure\Error;

/*
 * ViewError
 *
 * @package Yume\Fure\View
 *
 * @extends Yume\Fure\Error\TypeError
 */
class ViewError extends Error\TypeError
{
	
	public const NOT_FOUND_ERROR = 46288;
	public const PARSE_ERROR = 57858;
	
}

?>