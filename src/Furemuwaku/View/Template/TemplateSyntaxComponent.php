<?php

namespace Yume\Fure\View\Template;

use Yume\Fure\Support\File;
use Yume\Fure\Support\Package;

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
	
	protected String $namespace = "Yume\\App\\Views\\Components\\";
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxHTML
	 *
	 */
	public function builder( Array $attr ): String
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	public function process( TemplateCaptured $captured ): Array | String
	{
		// Check if token name === "template"
		if( $captured->tokenLower === "template" )
		{
			//throw new TemplateSyntaxError( "Template writing must be inside the component and not outside", $captured->view, $captured->line, 0 );
		}
		
		// Extract component attributes.
		$extract = $this->extract( $captured->value ?? "", False );

		//Check if component has attribute <name>
		if( isset( $extract['name'] ) )
		{
			// ...
			if( $extract['name']['default'] )
			{
				// Get component name.
				$name = $this->resolveName( $extract['name']['values'] );
				
				// Check if component exists.
				if( File\File::exists( $name ) )
				{
					// ...
					echo "Exists\n";
				}
				else {
					//throw new Component\ComponentError( $name, $this->context->view, $captured->line, Component\ComponentError::NAME_ERROR );
				}
			}
			else {
				// ...
				echo 88;
			}
		}exit;
		//throw new TemplateError( "" );
	}
	
	public function resolveName( String $name ): String
	{
		// Check if name is resolved.
		if( strpos( $name, $this->namespace ) === False )
		{
			// Add namespace.
			$name = f( "{}{}", $this->namespace, $name );
		}
		
		// ...
		$name = Package\Package::name( $name );
		
		// Add extension and replace backslash into slash.
		return( f( "{}{}", str_replace( "\\", "/", $name ), substr( $name, -4 ) !== ".php" ? ".php" : "" ) );
	}
	
}

?>