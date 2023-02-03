<?php

namespace Yume\Fure\View\Template;

use Throwable;

/*
 * TemplateSyntaxError
 *
 * @package Yume\Fure\View\Template
 *
 * @extends Yume\Fure\View\Template\TemplateError
 */
class TemplateSyntaxError extends TemplateError
{
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateError
	 *
	 */
	public function __construct( Array | Int | String $message, ? String $file = Null, ? Int $line = Null, Int $code = self::SYNTAX_ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( $message, $file, $line, $code, $previous );
	}
	
}

?>