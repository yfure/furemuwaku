<?php

namespace Yume\Fure\View\Template;

use Yume\Fure\Support\Data;
use Yume\Fure\Support\File;
use Yume\Fure\Util;
use Yume\Fure\Util\Json;
use Yume\Fure\Util\RegExp;

/*
 * Template
 *
 * @package Yume\Fure\View\Template
 */
class Template implements TemplateInterface
{
	
	/*
	 * Regular expression iteration.
	 *
	 * @access Private
	 *
	 * @values Int
	 */
	private ? Int $iteration = Null;
	
	/*
	 * Regular Expression Pattern.
	 *
	 * @access Protected Readonly
	 *
	 * @values Yume\Fure\Util\RegExp\Pattern
	 */
	protected Readonly RegExp\Pattern $pattern;
	
	/*
	 * Raw template.
	 *
	 * @access Protected Readonly
	 *
	 * @values String
	 */
	protected Readonly String $template;
	
	/*
	 * Template line length.
	 *
	 * @access Protected Readonly
	 *
	 * @values Int
	 */
	protected Readonly Int $templateLength;
	
	/*
	 * Template splited with newline.
	 *
	 * @access Protected Readonly
	 *
	 * @values Array
	 */
	protected Readonly Array $templateSplit;
	
	/*
	 * Construct method of class Template.
	 *
	 * @access Public Instance
	 * 
	 * @params Array|String $template
	 *
	 * @return Void
	 */
	public function __construct( Array | String $template )
	{
		// Check if template is Array type.
		if( is_array( $template ) )
		{
			// Join newline into template.
			$this->template = $this->normalizeTemplate( implode( "\n", $template ) );
		}
		else {
			
			// Set template.
			$this->template = $this->normalizeTemplate( $template );
		}
		
		// Split template with newline.
		$this->templateSplit = explode( "\n", $this->template );
		
		// Get splited length.
		$this->templateLength = count( $this->templateSplit );
		
		// Set pattern.
		$this->pattern = new RegExp\Pattern( flags: "ms", pattern: implode( "", [
			"^(?:",
				"(?<matched>",
					"(?<indent>\s\s+|\t+)*",
					"(?<!\\\)",
					"(?:\@)",
					"(?<inline>",
						"(?<token>[a-zA-Z0-9]*)",
						"(?:[\s\t]*)",
						"(?<value>.*?)",
					")",
					"(?<!\\\)",
					"(?<symbol>",
						"(?<colon>\:)|(?<semicolon>\;)",
					")",
					"(?<outline>[^\n]*)",
				")",
			")"
		]));
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateInterface
	 *
	 */
	public function getIteration(): ? Int
	{
		return( $this )->iteration;
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateInterface
	 *
	 */
	public function getLine( String $content ): False | Int
	{
		// Split raw content.
		$split = explode( "\n", $content );
		
		// Get array index.
		$search = array_search( $split[0], $this->templateSplit );
		
		// Check if index is exists.
		if( $search !== False )
		{
			return( $search +1 );
		}
		return( False );
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateInterface
	 *
	 */
	public function getPattern(): String
	{
		return( $this )->pattern;
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateInterface
	 *
	 */
	public function getTemplate(): String
	{
		return( $this )->template;
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateInterface
	 *
	 */
	public function getTemplateLength(): Int
	{
		return( $this )->templateLength;
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateInterface
	 *
	 */
	public function getTemplateSplit(): Array
	{
		return( $this )->templateSplit;
	}
	
	// Array<Content, Closing>
	private function matchDeepContent( Array $match ): Array
	{
		// Build syntax.
		$syntax = $match['syntax'] ?? $this->reBuildSyntax( $match );
		
		// Split syntax with newline.
		$msplit = explode( "\n", $syntax );
		
		// Get splited syntax length.
		$msplitl = count( $msplit );
		
		// Get default indentation.
		$indent = config( "view.template.indent.length" );
		
		// Get line number of syntax.
		$line = $this->getLine( $syntax );
		
		// ...
		$once = False;
		
		// Deep content stack.
		$content = [];
		
		// Closing content.
		$closing = Null;
		
		// Check if syntax has indentation.
		if( isset( $match['indent'] ) )
		{
			// Get indentation length.
			$indent = strlen( $match['indent'] );
			
			// Check if indentation is is valid.
			if( Util\Number::isEven( $indent ) )
			{
				$indent += 4;
			}
			else {
				throw new TemplateIndentationError( $this->getLine( $msplit[0] ) );
			}
		}
		
		// Looping splited template from current line.
		for( $i = $line + $msplitl; $i < $this->templateLength +1; $i++ )
		{
			// ...
			if( $this->templateSplit[( $i -1 )] !== "" )
			{
				// Check if indentation is valid.
				if( $valid = RegExp\RegExp::match( f( "/^[\s]\{{},}/", $indent ), $this->templateSplit[( $i -1 )] ) )
				{
					// Check if indentation level is invalid.
					if( Util\Number::isOdd( strlen( $this->removeLine( $valid[0] ) ) ) )
					{
						throw new TemplateIndentationError( $i );
					}
					
					// Check if symbol is semicolon.
					if( $match['symbol'] === ";" )
					{
						// Check if iteration is once.
						if( $once === True )
						{
							$closing = $this->templateSplit[( $i -1 )]; break;
						}
						else {
							throw new TemplateIndentationError( $i );
						}
					}
					$content[] = $this->templateSplit[( $i -1 )];
				}
				else {
					$closing = $this->templateSplit[( $i -1 )]; break;
				}
			}
			else {
				$content[] = "";
			}
			
			// ...
			$once = True;
		}
		
		// Check if content is not empty.
		if( count( $content ) !== 0 )
		{
			// Join newline into array contents.
			$content = implode( "\n", $content );
		}
		else {
			
			// Set content as Null.
			$content = Null;
		}
		return([
			$content,
			$closing
		]);
	}
	
	/*
	 * Normalize raw template.
	 *
	 * @access Private
	 *
	 * @params String $template
	 *
	 * @return String
	 */
	private function normalizeTemplate( String $template ): String
	{
		return( str_replace( "\t", str_repeat( config( "view.template.indent.value" ), config( "view.template.indent.length" ) ), $template ) );
	}
	
	/*
	 * Parse raw template.
	 *
	 * @access Public
	 *
	 * @params String $template
	 *
	 * @return String
	 */
	public function parse( String $template ): String
	{
		// Push iteration.
		$this->iteration = $this->iteration !== Null ? $this->iteration +1 : 1;
		
		// While syntax matched.
		while( $match = $this->pattern->match( $template ) )
		{
			// Re-Build syntax.
			$match['syntax'] = $this->reBuildSyntax( $match );
			
			// Check if matched syntax has token.
			if( isset( $match['token'] ) )
			{
				// Get deep and closing content.
				[ $child, $closing ] = $this->matchDeepContent( $match );
				
				var_dump([
					"child" => $child,
					"closing" => $closing
				]);
				
				// Check if syntax has outline value.
				if( valueIsNotEmpty( $match['outline'] ) )
				{
					// Check if syntax has child content.
					if( valueIsNotEmpty( $child ) )
					{
						echo 78;
					}
				}
				
				// Check if syntax is single line.
				else if( isset( $match['semicolon'] ) )
				{
					// Check if syntax has child content.
					if( valueIsNotEmpty( $child ) )
					{
						echo 79;
					}
				}
				else {
					//echo 80;
				}
			}
			else {
				throw new TemplateSyntaxError( $this->getLine() );
			}
			//var_dump( RegExp\RegExp::clear( $match, True ) );
			break;
		}
		//echo $this->getIteration();
		return( htmlspecialchars( $template ) );
	}
	
	protected function reBuildSyntax( Array $match ): String
	{
		return( Util\Str::fmt( "{ indent }@{ inline }{ symbol }{ outline }",
			indent: $match['indent'] ?? "",
			inline: $match['inline'] ?? "",
			symbol: $match['symbol'],
			outline: $match['outline'] ?? ""
		));
	}
	
	/*
	 * Remove new line in indentation value.
	 *
	 * @access Protected
	 *
	 * @params String $indent
	 *
	 * @return String
	 */
	protected function removeLine( String $indent ): String
	{
		return( str_replace( "\n", "", $indent ) );
	}
	
}

?>