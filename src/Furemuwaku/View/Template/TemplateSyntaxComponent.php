<?php

namespace Yume\Fure\View\Template;

/*
 * TemplateSyntaxComponent
 *
 * @package Yume\Fure\View\Template
 *
 * @extends Yume\Fure\View\Template\TemplateSyntaxHTML
 */
class TemplateSyntaxComponent extends TemplateSyntaxHTML
{
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntax
	 *
	 */
	protected Array | String $token = [
		"component",
		"template"
	];
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	public function process( TemplateCaptured $captured ): String
	{
		// Check if token name === "template"
		if( $captured->tokenLower === "template" )
		{
			throw new TemplateSyntaxError( "Template writing must be inside the component and not outside", $captured->view, $captured->line, 0 );
		}
		
		// Extract component attributes.
		$extract = $this->extract( $captured->value ?? "", False );
		var_dump( $extract );
		exit;
	}
	
}

?>