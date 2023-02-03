<?php

namespace Yume\Fure\View\Template;

use Yume\Fure\Error;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\File;
use Yume\Fure\Support\Reflect;
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
	 * Syntax processing class.
	 *
	 * @access Protected
	 *
	 * @values Array
	 */
	protected Array $syntax = [
		"default" => [
			TemplateSyntaxComponent::class,
			TemplateSyntaxHTML::class,
			TemplateSyntaxPHP::class
		],
		"custom" => []
	];
	
	/*
	 * Construct method of class Template.
	 *
	 * @access Public Instance
	 * 
	 * @params Public Readonly String $view
	 * @params Array|String $template
	 *
	 * @return Void
	 */
	public function __construct( public Readonly String $view, Array | String $template )
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
	 * Process matched closing syntax.
	 *
	 * @access Protected
	 *
	 * @params Array $match
	 * @params String $closing
	 *
	 * @return Array
	 */
	protected function closing( Array $match, ? String $closing ): ? Array
	{
		// Default result.
		$result = [
			"syntax" => $closing,
			"valid" => False
		];
		
		// Default indentation length is zero.
		$indent = 0;
		
		// Check if syntax has indentation.
		if( isset( $match['indent'] ) )
		{
			// Get indentation length.
			$indent = strlen( $this->removeLine( $match['indent'] ) );
		}
		
		// Check if closing is valid closing.
		if( $closing !== Null && $valid = RegExp\RegExp::match( f( "/(?<match>^[\s]\{{},}(?<!\\\)(?:@)(?<token>[a-zA-Z0-9]*)(?<closing>(?<!\\\)(?<dollar>\\$+)|(?<slash>\/+)(?<pass>[a-zA-Z0-9]*))*(?<outline>[^\n]*))/", $indent ), $closing ) )
		{
			// Check if closing is empty.
			if( valueIsEmpty( $valid['closing'] ) )
			{
				// Check if token is equal "pass"
				if( strtolower( $valid['token'] ) === "pass" )
				{
					// Check if outline syntax is not empty && syntax is not comment type.
					if( valueIsNotEmpty( $valid['outline'] ) && $this->isComment( $valid['outline'] ) === False ) throw new TemplateSyntaxError( $valid['outline'], $this->view, $this->getLine( $closing ) );
					
					// Push token.
					$result['token'] = "pass";
					
					// Set as valid for closing.
					$result['valid'] = True;
				}
			}
			
			// Check if closing is Dollar type.
			else if( isset( $valid['dollar'] ) )
			{
				// Check if token is not equals.
				if( strtolower( $valid['token'] ) !== strtolower( $match['token'] ) ) throw new TemplateSyntaxError( $valid['token'], $this->view, $this->getLine( $closing ) );
				
				// Check if dollar is more than one.
				if( strlen( $valid['dollar'] ) > 1 ) throw new TemplateSyntaxError( $valid['dollar'], $this->view, $this->getLine( $closing ) );
				
				// Check if outline syntax is not empty && syntax is not comment type.
				if( valueIsNotEmpty( $valid['outline'] ) && $this->isComment( $valid['outline'] ) === False ) throw new TemplateSyntaxError( $valid['outline'], $this->view, $this->getLine( $closing ) );
				
				// Push token.
				$result['token'] = "$";
				
				// Set as valid for closing.
				$result['valid'] = True;
			}
			
			// Check if closing is Slash type.
			else if( isset( $valid['slash'] ) )
			{
				// Check if token is not equals.
				if( strtolower( $valid['token'] ) !== strtolower( $match['token'] ) ) throw new TemplateSyntaxError( $valid['token'], $this->view, $this->getLine( $closing ) );
				
				// Check if pass is empty.
				if( valueIsEmpty( $valid['pass'] ) ) throw new TemplateSyntaxError( $valid['closing'], $this->view, $this->getLine( $closing ) );
				
				// Check if slash is more than one.
				if( strlen( $valid['slash'] ) > 1 ) throw new TemplateSyntaxError( $valid['slash'], $this->view, $this->getLine( $closing ) );
				
				// Check if pass is not pass.
				if( strtolower( $valid['pass'] ) !== "pass" ) throw new TemplateSyntaxError( $valid['pass'], $this->view, $this->getLine( $closing ) );
				
				// Push token.
				$result['token'] = $valid['closing'];
				
				// Set as valid for closing.
				$result['valid'] = True;
			}
		}
		
		// Check if syntax is not multiple line.
		// But closing syntax is matched.
		if( isset( $match['semicolon'] ) && $result['valid'] ) throw new TemplateClosingError( $result['syntax'], $this->view, $this->getLine( $closing ) );
		
		return( $result );
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
	public function getPattern(): RegExp\Pattern
	{
		return( $this )->pattern;
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateInterface
	 *
	 */
	public function getPatternAsString(): String
	{
		return( $this )->pattern->__toString();
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
	
	/*
	 * Check if syntax is commented.
	 *
	 * @access Public
	 *
	 * @params String $syntax
	 *
	 * @return Bool
	 */
	public function isComment( String $syntax ): Bool
	{
		return( RegExp\RegExp::test( "/[\s]*(?<!\\\)\#[^\n]*/", $syntax ) );
	}
	
	/*
	 * Get deep contents.
	 *
	 * @access Public
	 *
	 * @params Array $match
	 *
	 * @return Array
	 */
	public function matchDeepContent( Array $match ): Array
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
			$indent = strlen( $this->removeLine( $match['indent'] ) );
			
			// Check if indentation is is valid.
			if( Util\Number::isEven( $indent ) )
			{
				$indent += 4;
			}
			else {
				throw new TemplateIndentationError( "*", $this->view, $this->getLine( $msplit[0] ) );
			}
		}
		
		// Looping splited template from current line.
		for( $i = $line + $msplitl; $i < $this->templateLength +1; $i++ )
		{
			// Check if line is not exists.
			if( isset( $this->templateSplit[( $i -1 )] ) === False ) break;
			
			// Check if content is not empty value.
			if( $this->templateSplit[( $i -1 )] !== "" )
			{
				// Check if indentation is valid.
				if( $valid = RegExp\RegExp::match( f( "/^[\s]\{{},}/", $indent ), $this->templateSplit[( $i -1 )] ) )
				{
					// Check if indentation level is invalid.
					if( Util\Number::isOdd( strlen( $this->removeLine( $valid[0] ) ) ) )
					{
						throw new TemplateIndentationError( "*", $this->view, $i );
					}
					
					// Check if symbol is semicolon.
					if( $match['symbol'] === ";" )
					{
						// Check if iteration is once.
						if( $once === True )
						{
							// Set closing syntax and break the loop.
							$closing = $this->templateSplit[( $i -1 )]; break;
						}
						else {
							
							// Check if value is not empty.
							if( valueIsNotEmpty( $this->templateSplit[( $i -1 )] ) )
							{
								throw new TemplateIndentationError( "*", $this->view, $i );
							}
						}
					}
					else {
						if( valueIsNotEmpty( $match['outline'] ) )
						{
							if( count( $content ) >= 1 ) throw new TemplateIndentationError( "*", $this->view, $i );
						}
					}
					$content[] = $this->templateSplit[( $i -1 )];
				}
				else {
					
					// Set closing syntax and break the loop.
					$closing = $this->templateSplit[( $i -1 )]; break;
				}
				
				// ...
				if( $match['symbol'] === ":" )
				{
					if( valueIsNotEmpty( $match['outline'] ) )
					{
						if( count( $content ) >= 1 )
						{
							throw new TemplateIndentationError( "*", $this->view, $i );
						}
					}
				}
			}
			else {
				
				// Empty content will be allowed.
				$content[] = "";
			}
			
			// Set once as True.
			$once = True;
		}
		
		// Check if content is not empty.
		if( count( $content ) !== 0 )
		{
			// Join newline into array contents.
			$content = $this->reBuildSyntaxChild( $content );
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
	 * @access Protected
	 *
	 * @params String $template
	 *
	 * @return String
	 */
	protected function normalizeTemplate( String $template ): String
	{
		return( str_replace( "\t", str_repeat( config( "view.template.indent.value" ), config( "view.template.indent.length" ) ), $template ) );
	}
	
	/*
	 * Parse raw template multiple line mode.
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
			// Re-Build begin syntax.
			$begin = $match['syntax'] = $this->reBuildSyntaxBegin( $match );
			
			// Check if matched syntax has token.
			if( valueIsNotEmpty( $match['token'] ?? "" ) )
			{
				// Get deep and closing content.
				[ $children, $closing ] = $this->matchDeepContent( $match );
				
				// Get token name.
				$token = $match['token'];
				
				// Get value inline.
				$value = $match['value'] ?? Null;
				
				// Get inline values.
				$inline = $match['inline'] ?? Null;
				
				// Get outline values.
				$outline = $match['outline'] ?? Null;
				
				// Process matched closing syntax.
				$closing = $this->closing( $match, $closing );
				
				// Check if syntax is single line.
				if( isset( $match['semicolon'] ) )
				{
					// Check if syntax has outline value.
					if( valueIsNotEmpty( $outline ) && $this->isComment( $outline ) === False ) throw new TemplateSyntaxError( $outline, $this->view, $this->getLine( $begin ) );
					
					// Parser outline content.
					$outline = $this->parseLine( $outline );
				}
				else {
					
					$outline = $this->parseLine( $outline );
				}
				
				// Default indentation.
				$indent = "";
				$indentLength = 0;
				
				// Check if syntax has indentation.
				if( isset( $match['indent'] ) )
				{
					// Get indentation.
					$indent = $match['indent'];
					
					// Get indentation length.
					$indentLength = strlen( $this->removeLine( $match['indent'] ) );
				}
				
				// Build full captured syntax.
				$raw = $this->reBuilSyntaxCapture(
					begin: $begin,
					children: $children,
					closing: $closing
				);
				
				// Process captured syntax.
				$result = $this->process( new TemplateCaptured([
					"raw" => $raw,
					"indent" => [
						"value" => $indent,
						"length" => $indentLength
					],
					"symbol" => $match['symbol'],
					"token" => $token,
					"value" => $value,
					"begin" => $begin,
					"inline" => $inline,
					"outline" => $outline,
					"children" => $children,
					"closing" => $closing
				]));
				
				// Replace template.
				$template = str_replace( $raw, f( "{}{}", $result, $match['symbol'] === ";" ? "\n" : "" ), $template );
			}
			else {
				throw new TemplateSyntaxError( $match['syntax'], $this->view, $this->getLine( $match['syntax'] ) );
			}
		}
		return( $template );
	}
	
	/*
	 * Parse raw template single line mode.
	 *
	 * @access Public
	 *
	 * @params String $template
	 *
	 * @return String
	 */
	public function parseLine( String $template ): String
	{
		$pattern = new RegExp\Pattern( flags: "msJ", pattern: implode( "", [
			"(?:",
				"(?<matched>",
					"(?<!\\\)*",
					"(?:",
						"(?<comment>\\#",
							"(?:",
								"(?<html>",
									"\<",
									"(?<text>[^\>]*)",
									"\>",
								")",
								"|",
								"(?<text>[^\n]*)",
							")",
						")",
						"|",
						"(?:\@)",
						"(?<inline>",
							"(?<token>[a-zA-Z0-9]*)",
							"(?:[\s\t]*)",
							"(?<value>.*?)",
						")",
						"(?<!\\\)",
						"(?:\:)",
						"(?<outline>[^\n]*)",
						"(?<symbol>",
							"(?<semicolon>\;)",
						")",
						"|",
						"(?:\@)",
						"(?<inline>",
							"(?<token>[a-zA-Z0-9]*)",
							"(?:[\s\t]*)",
							"(?<value>.*?)",
						")",
						"(?<!\\\)",
						"(?<symbol>",
							"(?<semicolon>\;)",
						")",
					")",
				")",
			")"
		]));
		
		// While syntax matched.
		while( $match = $pattern->match( $template ) )
		{
			// Check if matched syntax is comment type.
			if( valueIsNotEmpty( $match['comment'] ) )
			{
				// Check if comment is html mode.
				if( valueIsNotEmpty( $match['html'] ) )
				{
					// Build HTML comment syntax.
					$comment = f( "<!-- {} -->", $match['text'] ?? "" );
				}
				
				// Replace comment.
				$template = str_replace( $match['matched'], $comment ?? "", $template );
			}
			else {
				
				echo new TemplateCaptured([
					"raw" => "",//$raw,
					"indent" => [
						"value" => "",
						"length" => 0
					],
					"symbol" => $match['symbol'],
					"token" => $match['token'],
					"value" => $match['value'] ?? Null,
					"begin" => "*",
					"inline" => $match['inline'] ?? Null,
					"outline" => $match['outline'] ?? Null,
					"children" => Null,
					"closing" => [
						"syntax" => Null,
						"valid" => False
					]
				]);exit;
			}
			var_dump( RegExp\RegExp::clear( $match, True ) );
			break;
		}exit;
		return( $template );
	}
	
	/*
	 * Process captured syntax.
	 *
	 * @access Private
	 *
	 * @params Yume\Fure\View\Template\TemplateCaptured $captured
	 *
	 * @return String
	 */
	private function process( TemplateCaptured $captured ): String
	{
		// Get captured line.
		$captured->line = $this->getLine( $captured->begin );
		
		// Mapping syntax processor groups.
		foreach( $this->syntax As $group => $lists )
		{
			// Mapping syntax processor.
			foreach( $lists As $name => $class )
			{
				// Check if class is string type.
				if( is_string( $class ) )
				{
					// Unset from group.
					unset( $this->syntax[$group][$name] );
					
					// Check if class is not implements TemplateSyntaxInterface class.
					if( Reflect\ReflectClass::isImplements( $class, TemplateSyntaxInterface::class, $reflect ) === False )
						
						// We do not allow classes that do not implement the
						// TemplateSyntaxInterface to be used for syntax processing.
						throw new Error\ClassImplementationError([ $class, TemplateSyntaxInterface::class ]);
					
					// Create new Template Syntax instance.
					$class = $this->syntax[$group][$class] = $reflect->newInstance( $this, [] );
					
					// Set name value with class name.
					$name = $class::class;
				}
				
				// Check if the syntax supports tokens.
				if( $class->isSupportedToken( $captured->token ) )
				{
					// Return the result of the syntax that has been processed.
					return( $class->process( $captured ) );
				}
			}
		}
		throw new TemplateTokenError( $captured->token, $this->view, $captured->line );
	}
	
	/*
	 * Re-Build syntax begin.
	 *
	 * @access Protected
	 *
	 * @params Array $match
	 * @params Bool $outline
	 *
	 * @return String
	 */
	protected function reBuildSyntaxBegin( Array $match, Bool $outline = True ): String
	{
		return( Util\Str::fmt( "{ indent }@{ inline }{ symbol }{ outline }", indent: $this->removeLine( $match['indent'] ?? "" ), inline: $match['inline'] ?? "", symbol: $match['symbol'], outline: $match['outline'] ?? "" ) );
	}
	
	/*
	 * Re-Build syntax capture.
	 *
	 * @access Protected
	 *
	 * @params String $begin
	 * @params String $children
	 * @params Array $closing
	 *
	 * @return String
	 */
	protected function reBuilSyntaxCapture( String $begin, ? String $children, Array $closing ): String
	{
		// Check if closing syntax is allowed.
		if( $closing['valid'] )
		{
			return( Util\Str::fmt( "{begin}\n{children}\n{closing.syntax}", begin: $begin, children: $children ?? "", closing: $closing ) );
		}
		return( Util\Str::fmt( "{begin}\n{children}", begin: $begin, children: $children ?? "" ) );
	}
	
	/*
	 * Re-Build syntax children.
	 *
	 * @access Protected
	 *
	 * @params Array $children
	 *
	 * @return String
	 */
	protected function reBuildSyntaxChild( Array $children ): String
	{
		return( implode( "\n", $children ) );
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