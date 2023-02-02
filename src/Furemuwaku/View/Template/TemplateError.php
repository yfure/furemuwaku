<?php

namespace Yume\Fure\View\Template;

use Yume\Fure\View;

/*
 * TemplateError
 *
 * @package Yume\Fure\View\Template
 *
 * @extends Yume\Fure\View\ViewError
 */
class TemplateError extends View\ViewError
{
	
	public const INDENTATION_ERROR = 56828;
	public const SYNTAX_ERROR = 67826;
	public const TOKEN_ERROR = 79825;
	
}

?>