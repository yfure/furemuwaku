<?php

namespace Yume\Fure\View\Template;

/*
 * TemplateSyntaxPHP
 *
 * @package Yume\Fure\View\Template
 *
 * @extends Yume\Fure\View\Template\TemplateSyntax
 */
class TemplateSyntaxPHP extends TemplateSyntax
{
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntax
	 *
	 */
	protected Array | String $token = [
		"catch",
		"do",
		"elif",
		"else",
		"elseif",
		"for",
		"foreach",
		"if",
		"match",
		"switch",
		"try",
		"use",
		"while"
	];
	
	public function isConditional( String $token ): Bool
	{
		return( in_array( strtolower( $token ), [ "elif", "for", "foreach", "if", "while" ] ) );
	}
	
	public function isSingle( String $token ): Bool
	{
		return( in_array( strtolower( $token ), [ "elif", "else", "elseif", "for", "foreach", "if", "while" ] ) );
	}
	
	public function isUnpaired( String $token ): Bool
	{
		return( in_array( strtolower( $token ), [ "use" ] ) );
	}
	
	public function normalizeToken( String $token ): String
	{
		return( match( strtolower( $token ) )
		{
			"elif" => "else if",
			
			default => $token
		});
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	public function process( TemplateCaptured $captured ): String
	{
		// Check if token is unpaired type.
		if( $this->isUnpaired( $captured->token ) )
		{
			// Check if closing symbol is valid.
			if( $captured->symbol === ":" ) throw new TemplateSyntaxError( f( "Invalid closing symbol for unpaired token \"{}\" syntax", $captured->token ), $this->context->view, $captured->line, 0 );
			
			echo 89;
		}
		else {
			
			// Check if closing symbol is valid.
			if( $captured->symbol === ";" ) throw new TemplateSyntaxError( f( "Invalid closing symbol for paired token \"{}\" syntax", $captured->token ), $this->context->view, $captured->line, 0 );
			
			// Check if outline is available.
			if( valueIsNotEmpty( $captured->outline ) )
			{
				// Check if token is allowed single line.
				if( $this->isSingle( $captured->token ) )
				{
					// Check if token is required condition.
					// But conditional value is empty.
					if( valueIsEmpty( $captured->value ) && $this->isConditional( $captured->token ) )
					{
						throw new TemplateSyntaxError( f( "The \"{}\" syntax requires a conditional", $captured->token ), $this->context->view, $captured->line, 0 );
					}
					
					// Normalize token name.
					$captured->token = $this->normalizeToken( $captured->token );
					
					// Reparse outline content.
					$captured->outline = $this->context->parseLine( $captured->outline );
					
					// Return formated syntax.
					exit( htmlspecialchars( f( "{captured.indent.value}<?php {captured.token}( {captured.value} ) \{ ?> {captured.outline} <?php } ?>", captured: $captured ) ) );
				}
				throw new TemplateSyntaxError( f( "The \"{}\" syntax does not support single-line writing", $captured->token ), $this->context->view, $captured->line, 0 );
			}
			else {
				
			}
		}
	}
	
}

?>