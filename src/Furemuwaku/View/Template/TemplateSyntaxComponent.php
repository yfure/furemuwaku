<?php

namespace Yume\Fure\View\Template;

/*
 * TemplateSyntaxComponent
 *
 * @package Yume\Fure\View\Template
 *
 * @extends Yume\Fure\View\Template\TemplateSyntaxHTML
 */
class TemplateSyntaxComponent extends TemplateSyntax
{
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntax
	 *
	 */
	protected Array | String $token = "component";
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	public function process( TemplateCaptured $captured ): String
	{
		// ...
	}
	
}

?>