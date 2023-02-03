<?php

namespace Yume\Fure\View\Template;

use Throwable;

/*
 * TemplateUninitializedTokenError
 *
 * @package Yume\Fure\View\Template
 *
 * @extends Yume\Fure\View\Template\TemplateError
 */
class TemplateUninitializedTokenError extends TemplateError
{
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateError
	 *
	 */
	public function __construct( Array | Int | String $message, ? String $file = Null, ? Int $line = Null, Int $code = self::UNITIALIZED_TOKEN_ERROR, ? Throwable $previous = Null )
	{
		parent::__construct( $message, $file, $line, $code, $previous );
	}
	
}

?>