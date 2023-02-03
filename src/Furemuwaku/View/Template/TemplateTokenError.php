<?php

namespace Yume\Fure\View\Template;

use Throwable;

/*
 * TemplateTokenError
 *
 * @package Yume\Fure\View\Template
 *
 * @extends Yume\Fure\View\Template\TemplateError
 */
class TemplateTokenError extends TemplateError
{
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateError
	 *
	 */
	public function __construct( Array | Int | String $message, ? String $file = Null, ? Int $line = Null, Int $code = self::TOKEN_ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( $message, $file, $line, $code, $previous );
	}
	
}

?>