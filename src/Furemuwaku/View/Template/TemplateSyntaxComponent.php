<?php

namespace Yume\Fure\View\Template;

use Yume\Fure\Support\File;
use Yume\Fure\Support\Package;
use Yume\Fure\Util\RegExp;
use Yume\Fure\View\Component;

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
		"template",
		"slot"
	];
	
	protected String $namespace = "Yume\\App\\Views\\Components\\";
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxHTML
	 *
	 */
	public function build( Array $attr ): String
	{
		// ...
	}
	
	public function matchSlot( TemplateCaptured $parent )//: Array
	{
		// Slot stacks.
		$slots = [];
		
		// Regular Expression.
		$regexp = f( "/^(?<matched>(?<indent>\s\{{},\})*(?:\@)(?<inline>(?<token>(?:template)*)(?:[\s\t]*)(?<value>.*?))(?<!\\\)(?<symbol>(?<colon>\:)|(?<semicolon>\;))(?<outline>([^\n]*)))/msJi", $parent->indent->length );
		
		// Get children content.
		$content = $parent->children;
		
		// Matching syntax.
		while( $match = RegExp\RegExp::match( $regexp, $content ) )
		{
			// Check if matched syntax has token.
			if( valueIsNotEmpty( $match['token'] ?? "" ) )
			{
				// Template captured data.
				$captured = new TemplateCaptured;
				
				// Set Regular Expression matched results.
				$captured->match = RegExp\RegExp::clear( $match, True );
				
				// Set token name.
				$captured->token = $captured->match->token;
				
				// Set normalized token name.
				$captured->tokenLower = strtolower( $captured->match->token );
				$captured->tokenUpper = strtolower( $captured->match->token );
				
				// Default indentation.
				$captured->indent = [
					"value" => "",
					"length" => 0
				];
				
				// Check if syntax has indentation.
				if( isset( $captured->match->indent ) )
				{
					// Set indentation.
					$captured->indent->value = $this->context->resolveIndent( $captured->match->indent );
					
					// Set indentation length.
					$captured->indent->length = strlen( $captured->indent->value );
				}
				
				// Set captured syntax as multiline.
				$captured->multiline = True;
				
				// Set captured view name.
				$captured->view = $this->context->view;
				
				// Set value inline.
				$captured->value = $captured->match->value ?? Null;
				
				// Set inline values.
				$captured->inline = $captured->match->inline ?? Null;
				
				// Set outline values.
				$captured->outline = $captured->match->outline ?? Null;
				
				// Set symbol.
				$captured->symbol = $captured->match->symbol;
				
				// Set symbol mode.
				$captured->colon = isset( $match['colon'] );
				$captured->semicolon = isset( $match['semicolon'] );
				
				// Set line number of syntax.
				$captured->line = $this->context->getLine(
					
					// Re-Build begin syntax.
					$captured->begin = $this->context->reBuildSyntaxBegin( $captured )
				);
				
				// Check if syntax is single line.
				if( $captured->outline && $captured->semicolon )
				{
					// Check if syntax has outline value.
					if( valueIsNotEmpty( $captured->outline ) && $this->context->isComment( $captured->outline ) === False )
					{
						throw new TemplateSyntaxError( $captured->outline, $this->view, $this->context->getLine( $captured->begin ) );
					}
				}
				
				// Check if indentation length is valid.
				if( $captured->indent->length === $parent->indent->length ||
					$captured->indent->length === $parent->indent->length +4 )
				{
					// Capture deep and closing content.
					$this->context->parseDeep( $captured );
					
					// Build full captured syntax.
					$captured->raw = $this->context->reBuilSyntaxCapture( $captured );
					
					// Check if syntax has inline value.
					if( valueIsNotEmpty( $captured->inline ) )
					{
						// Extract attribute.
						$extract = $this->extract( $captured->inline, False );
						
						// Check if key slot is exists.
						if( isset( $extract['slot'] ) )
						{
							// Copy slot name.
							$name = $extract['slot'];
							
							// Create props.
							$props = $this->props( $extract );
							
							// Push slot template.
							$slots[$name] = $captured;
						}
					}
					else {
						
					}
				}
				else {
					throw new TemplateIndentationError( "*", $parent->view, $captured->line );
				}
				
				echo $captured;
				// Continue matching.
				//continue;
			}
			else {
				echo "Default";
				//throw new TemplateSyntaxError( $match['syntax'], $this->view, $this->getLine( $match['syntax'] ) );
			}
			break;
		}
		exit;
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	public function process( TemplateCaptured $syntax ): Array | String
	{
		// Check if token name === "template"
		if( $syntax->tokenLower === "template" )
		{
			throw new TemplateSyntaxError( "Template writing must be inside the component and not outside", $syntax->view, $syntax->line, 0 );
		}
		
		// Check if component has slot.
		if( valueIsNotEmpty( $syntax->children ) )
		{
			// Get all component slots.
			$slots = $this->matchSlot( $syntax );
		}
		
		// Extract component attributes.
		$extract = $this->extract( $syntax->value ?? "", False );

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
					throw new Component\ComponentError( $name, $this->context->view, $syntax->line, Component\ComponentError::NAME_ERROR );
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
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxHTML
	 *
	 */
	public function props( Array $attr ): String
	{
		// Prop stacks.
		$props = [];
		
		
	}
	
}

?>