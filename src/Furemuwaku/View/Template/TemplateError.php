<?php

namespace Yume\Fure\View\Template;

use Throwable;

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
	
	public const CLOSING_ERROR = 46545;
	public const INDENTATION_ERROR = 56828;
	public const SYNTAX_ERROR = 67826;
	public const TOKEN_ERROR = 79825;
	public const UNITIALIZED_TOKEN_ERROR = 82722;
	
	/*
	 * @inherit Yume\Fure\View\ViewError
	 *
	 */
	protected Array $flags = [
		TemplateError::class => [
			self::CLOSING_ERROR,
			self::INDENTATION_ERROR,
			self::SYNTAX_ERROR,
			self::TOKEN_ERROR,
			self::UNITIALIZED_TOKEN_ERROR
		],
		TemplateClosingError::class => [
			self::CLOSING_ERROR,
		],
		TemplateIndentationError::class => [
			self::INDENTATION_ERROR
		],
		TemplateSyntaxError::class => [
			self::SYNTAX_ERROR
		],
		TemplateTokenError::class => [
			self::TOKEN_ERROR
		],
		TemplateUninitializedTokenError::class => [
			self::UNITIALIZED_TOKEN_ERROR
		]
	];
	
	
}

?>