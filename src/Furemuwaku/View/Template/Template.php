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
 * I have thanked you O Allah, thanks to you
 * I can create and complete the PHP Template Engine
 * that supports this Indentation O Allah.
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
		$this->pattern = new RegExp\Pattern( flags: "msJ", pattern: implode( "", [
			"(?:",
				"(?<matched>",
					"(?<multiline>",
						"^",
						"(?<indent>\s\s\s\s+|\t+)*",
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
						"(?<outline>([^\n]*))",
					")|",
					"(?<oneline>",
						"(?<!\\\)",
						"(?:",
							"(?<comment>",
								"(?<taggar>\\#",
									"(?:",
										"(?<html>",
											"\<(?<text>.*?)(?<!\\\)\>",
										")",
										"|",
										"(?<text>[^\n]*)",
									")",
								")",
								"|",
								"(?<html>",
									"\<\+\+(?<text>.*?)\+\+\>",
								")",
							")",
							"|",
							"(?<online>",
								"(?:\@)",
								"(?:",
									"(?<with>",
										"(?<inline>",
											"(?<token>[a-zA-Z0-9]*)",
											"(?:[\s\t]*)",
											"(?<value>[^?<!\\\\:]*)",
										")",
										"(?<!\\\)",
										"(?:\:)",
										"(?:[\s\t]*)",
										"(?<outline>[^?<!\\\\;]*)",
										"(?<!\\\)",
										"(?<symbol>",
											"(?<semicolon>\;)",
										")",
									")",
									"|",
									"(?<without>",
										"(?<inline>",
											"(?<token>[a-zA-Z0-9]*)",
											"(?:[\s\t]*)",
											"(?<value>[^?<!\\\\:]*)",
										")",
										"(?<!\\\)",
										"(?<symbol>",
											"(?<semicolon>\;)",
										")",
									")",
								")",
							")",
						")",
					")",
				")",
			")"
		]));
	}
	
	/*
	 * Process matched closing syntax.
	 *
	 * @access Protected
	 *
	 * @params Yume\Fure\View\TemplateCaptured $captured
	 *
	 * @return Yume\Fure\Support\Data\DataInterface
	 */
	protected function closing( TemplateCaptured $captured ): Data\DataInterface
	{
		// Default result.
		$captured->closing = [
			"syntax" => $captured->closing,
			"valid" => False
		];
		
		// Check if closing is valid closing.
		if( $captured->closing->syntax && $valid = RegExp\RegExp::match( f( "/(?<match>^[\s]\{{},}(?<!\\\)(?:@)(?<token>[a-zA-Z0-9]*)(?<closing>(?<!\\\)(?<dollar>\\$+)|(?<slash>\/+)(?<pass>[a-zA-Z0-9]*))*(?<outline>[^\n]*))/", $captured->indent->length ), $captured->closing->syntax ) )
		{
			// Closing line number.
			$captured->closing->line = $this->getLine( $captured->closing->syntax );
			
			// Check if closing is empty.
			if( valueIsEmpty( $valid['closing'] ) )
			{
				// Check if token is equal "pass"
				if( strtolower( $valid['token'] ) === "pass" )
				{
					// Check if outline syntax is not empty && syntax is not comment type.
					if( valueIsNotEmpty( $valid['outline'] ) && $this->isComment( $valid['outline'] ) === False ) throw new TemplateSyntaxError( $valid['outline'], $this->view, $this->getLine( $closing ) );
					
					// Push token.
					$captured->closing->token = "pass";
					
					// Set as valid for closing.
					$captured->closing->valid = True;
				}
			}
			
			// Check if closing is Dollar type.
			else if( isset( $valid['dollar'] ) )
			{
				// Check if token is not equals.
				if( strtolower( $valid['token'] ) !== $captured->tokenLower ) throw new TemplateSyntaxError( $valid['token'], $this->view, $this->getLine( $captured->closing->syntax ) );
				
				// Check if dollar is more than one.
				if( strlen( $valid['dollar'] ) > 1 ) throw new TemplateSyntaxError( $valid['dollar'], $this->view, $this->getLine( $captured->closing->syntax ) );
				
				// Check if outline syntax is not empty && syntax is not comment type.
				if( valueIsNotEmpty( $valid['outline'] ) && $this->isComment( $valid['outline'] ) === False ) throw new TemplateSyntaxError( $valid['outline'], $this->view, $this->getLine( $captured->closing->syntax ) );
				
				// Push token.
				$captured->closing->token = "$";
				
				// Set as valid for closing.
				$captured->closing->valid = True;
			}
			
			// Check if closing is Slash type.
			else if( isset( $valid['slash'] ) )
			{
				// Check if token is not equals.
				if( strtolower( $valid['token'] ) !== $captured->tokenLower ) throw new TemplateSyntaxError( $valid['token'], $this->view, $this->getLine( $captured->closing->syntax ) );
				
				// Check if pass is empty.
				if( valueIsEmpty( $valid['pass'] ) ) throw new TemplateSyntaxError( $valid['closing'], $this->view, $this->getLine( $captured->closing->syntax ) );
				
				// Check if slash is more than one.
				if( strlen( $valid['slash'] ) > 1 ) throw new TemplateSyntaxError( $valid['slash'], $this->view, $this->getLine( $captured->closing->syntax ) );
				
				// Check if pass is not pass.
				if( strtolower( $valid['pass'] ) !== "pass" ) throw new TemplateSyntaxError( $valid['pass'], $this->view, $this->getLine( $captured->closing->syntax ) );
				
				// Push token.
				$captured->closing->token = $valid['closing'];
				
				// Set as valid for closing.
				$captured->closing->valid = True;
			}
		}
		
		// Check if syntax is not multiple line.
		// But closing syntax is matched.
		if( $captured->semicolon && $captured->closing->valid ) throw new TemplateClosingError( $captured->closing->syntax, $this->view, $this->getLine( $captured->closing->syntax ) );
		
		// Return closing.
		return( $captured->closing );
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateInterface
	 *
	 */
	public function getInline( String $content ): False | Int
	{
		// Mapping template syntax.
		foreach( $this->templateSplit As $i => $template )
		{
			// Check if content in position.
			if( strpos( $template, $content ) !== False )
			{
				return( $i +1 );
			}
		}
		return( False );
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
		return( RegExp\RegExp::test( "/[\s]*((?<!\\\)\#[^\n]*|\<\!(--(.*?--\>)*)|(\+\+(.*?\+\+\>)*))/ms", $syntax ) );
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
		// While syntax matched.
		while( $match = $this->pattern->match( $template ) )
		{
			// Push iteration.
			$this->iteration = $this->iteration !== Null ? $this->iteration +1 : 1;
			
			// Check if captured syntax is multiline syntax.
			if( isset( $match['multiline'] ) )
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
					
					// Set captured syntax as multiline.
					$captured->multiline = True;
					
					// Set captured view name.
					$captured->view = $this->view;
					
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
					
					// Default indentation.
					$captured->indent = [
						"value" => "",
						"length" => 0
					];
					
					// Check if syntax has indentation.
					if( isset( $captured->match->indent ) )
					{
						// Set indentation.
						$captured->indent->value = $this->resolveIndent( $captured->match->indent );
						
						// Set indentation length.
						$captured->indent->length = strlen( $captured->indent->value );
					}
					
					// Set line number of syntax.
					$captured->line = $this->getLine(
						
						// Re-Build begin syntax.
						$captured->begin = $this->reBuildSyntaxBegin( $captured )
					);
					
					// Check if syntax is single line.
					if( $captured->outline && $captured->semicolon )
					{
						// Check if syntax has outline value.
						if( valueIsNotEmpty( $captured->outline ) && $this->isComment( $captured->outline ) === False )
						{
							throw new TemplateSyntaxError( $captured->outline, $this->view, $this->getLine( $captured->begin ) );
						}
					}
					
					// Capture deep and closing content.
					$this->parseDeep( $captured );
					
					// Build full captured syntax.
					$captured->raw = $this->reBuilSyntaxCapture( $captured );
					
					// Processing captured syntax.
					$result = $this->processing( $captured );
					
					// Replace template.
					$template = str_replace( $captured->raw, f( "{}{}", $captured->indent->value, $result ), $template );
					
					// Continue matching.
					continue;
				}
				else {
					throw new TemplateSyntaxError( $match['syntax'], $this->view, $this->getLine( $match['syntax'] ) );
				}
			}
			
			// Check if captured syntax is commented.
			else if( isset( $match['comment'] ) )
			{
				// Default replacement is blank for comment.
				$replace = "";
				
				// Check if comment is taggar type.
				if( isset( $match['taggar'] ) )
				{
					// Check if comment is html type.
					if( isset( $match['html'] ) )
					{
						// Change to html comment only.
						$replace = f( "<!--{}-->", $match['text'] ?? "" );
					}
					else {
						
						// Check if comment taggar is Doctument Type.
						if( $taggar = RegExp\RegExp::match( "/^(?:\!DOCTYPE(?:\s*)\/(?:\s*)(?<type>[a-zA-Z0-9_\-]+))(?:[\s\t]*)$/i", $match['text'] ) ) $replace = f( "<!DOCTYPE {}>", $taggar['type'] );
					}
				}
				else {
					
					// Change to html comment only.
					$replace = f( "<!--{}-->", $match['text'] ?? "" );
				}
				
				// Replace template.
				$template = str_replace( $match['comment'], $replace, $template );
				
				// Continue matching.
				continue;
			}
			
			// Only one line mode.
			else {
				
				echo htmlspecialchars( json_encode( RegExp\RegExp::clear( $match, True ), JSON_PRETTY_PRINT ) );
				exit;
			}
			break;
		}
		
		// Return Replaced unmatched syntax.
		//return( RegExp\RegExp::replace( "/\\\(\@|\<|\>|\-|\:|\;)/", $template, "$1" ) );
		return( $template );
	}
	
	/*
	 * Parse deep contents.
	 *
	 * @access Public
	 *
	 * @params Yume\Fure\View\TemplateCaptured $match
	 *
	 * @return Yume\Fure\Support\Data\DataInterface
	 */
	public function parseDeep( TemplateCaptured $captured ): Data\DataInterface
	{
		// ...
		$once = False;
		
		// Deep content stack.
		$captured->children = [];
		
		// Closing content.
		$captured->closing = Null;
		
		// Split syntax with newline.
		$msplit = explode( "\n", $captured->begin );
		
		// Get splited syntax length.
		$msplitl = count( $msplit );
		
		// Get default indentation.
		$indent = config( "view.template.indent.length" );
		
		// Check if syntax has indentation.
		if( $captured->indent->length !== 0 )
		{
			// Get indentation length.
			$indent = $captured->indent->length;
			
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
		for( $i = $captured->line + $msplitl; $i < $this->templateLength +1; $i++ )
		{
			// Check if line is not exists.
			if( isset( $this->templateSplit[( $i -1 )] ) === False ) break;
			
			// Check if content is not empty value.
			if( $this->templateSplit[( $i -1 )] !== "" )
			{
				// Check if indentation is valid.
				if( $valid = RegExp\RegExp::match( f( "/^[\s]\{{},\}/", $indent,), $this->templateSplit[( $i -1 )] ) )
				{
					// Get indent length.
					$validIndentLength = strlen( $this->resolveIndent( $valid[0] ) );
					
					// Check if indentation level is invalid.
					if( Util\Number::isOdd( $validIndentLength ) )
					{
						throw new TemplateIndentationError( "*", $this->view, $i );
					}
					
					// Check if symbol is semicolon.
					if( $captured->semicolon )
					{
						// Check if iteration is once.
						if( $once === True )
						{
							// Set closing syntax and break the loop.
							$captured->closing = $this->templateSplit[( $i -1 )]; break;
						}
						else {
							
							// Check if value is not empty.
							if( valueIsNotEmpty( $this->templateSplit[( $i -1 )] ) && $this->isComment( $this->templateSplit[( $i -1 )] ) === False )
							{
								throw new TemplateIndentationError( "*", $this->view, $i );
							}
						}
					}
					else {
						
						// Check if outline value is not empty
						// And outline is not comment syntax.
						if( valueIsNotEmpty( $captured->outline ) && $this->isComment( $captured->outline ) === False )
						{
							// Check if syntax has inner content.
							if( count( $captured->children ) >= 1 ) throw new TemplateIndentationError( "*", $this->view, $i );
						}
					}
					$captured->children[] = $this->templateSplit[( $i -1 )];
				}
				else {
					
					// Re-Match indentation.
					if( $valid = RegExp\RegExp::match( "/(^[\s]*)([^\n]*)/", $this->templateSplit[( $i -1 )] ) )
					{
						// Get indent length.
						$validIndentLength = strlen( $this->resolveIndent( $valid[1] ) );
						
						// Check if indentation level is invalid.
						if( Util\Number::isOdd( $validIndentLength ) )
						{
							throw new TemplateIndentationError( "*", $this->view, $i );
						}
						if( $validIndentLength < $indent || $valid[2] )
						{
							$captured->closing = $this->templateSplit[( $i -1 )]; break;
						}
						$content[] = $this->templateSplit[( $i -1 )];
					}
					else {
						
						// Set closing syntax and break the loop.
						$captured->closing = $this->templateSplit[( $i -1 )]; break;
					}
				}
				
				// Check if captured syntax use
				// colon symbol for closing outline.
				if( $captured->colon )
				{
					// Check if outline value is not empty
					// And outline is not comment syntax.
					if( valueIsNotEmpty( $captured->outline ) && $this->isComment( $captured->outline ) === False )
					{
						// If syntax has inner content.
						if( count( $captured->children ) >= 1 ) throw new TemplateIndentationError( "*", $this->view, $i );
					}
				}
			}
			else {
				
				// Empty content will be allowed.
				$captured->children[] = "";
			}
			
			// Set once as True.
			$once = True;
		}
		
		// Process matched closing syntax.
		$this->closing( $captured );
		
		// Check if content is not empty.
		if( count( $captured->children ) !== 0 )
		{
			// Clear last line in content.
			$captured->children = $this->removeLastLine( $captured->children->__toArray(), $captured->closing->valid );
			
			// Re-Check if content is not empty.
			if( count( $captured->children ) !== 0 )
			{
				// Join newline into array contents.
				$captured->children = $this->reBuildSyntaxChild( $captured->children->__toArray() );
			}
			else {
				$captured->children = Null;
			}
		}
		else {
			
			// Set content as Null.
			$captured->children = Null;
		}
		
		// Return children content & closing.
		return( new Data\Data([
			"children" => $captured->children,
			"closing" => $captured->closing
		]));
	}
	
	/*
	 * Parse raw template based on position captured.
	 *
	 * @access Public
	 *
	 * @params String $template
	 *
	 * @return String
	 */
	public function parsePost( String $template ): String
	{
	}
	
	/*
	 * Processing captured syntax.
	 *
	 * @access Private
	 *
	 * @params Yume\Fure\View\Template\TemplateCaptured $captured
	 *
	 * @return String
	 */
	private function processing( TemplateCaptured $captured ): String
	{
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
					// Check if syntax is skiped.
					if( $class->isSkip() )
					{
						// Skip looping execution.
						break;
					}
					
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
	 * @params Yume\Fure\View\Template\TemplateCaptured $match
	 * @params Bool $outline
	 *
	 * @return String
	 */
	protected function reBuildSyntaxBegin( TemplateCaptured $captured, Bool $outline = True ): String
	{
		return( Util\Str::fmt( "{ indent }@{ inline }{ symbol }{ outline }", indent: $captured->indent->value ?? "", inline: $captured->inline ?? "", symbol: $captured->symbol, outline: str_replace( "\n", "", $captured->outline ?? "" ) ) );
	}
	
	/*
	 * Re-Build syntax capture.
	 *
	 * @access Protected
	 *
	 * @params Yume\Fure\View\TemplateCaptured $captured
	 *
	 * @return String
	 */
	protected function reBuilSyntaxCapture( TemplateCaptured $captured ): String
	{
		// Check if closing syntax is allowed.
		if( $captured->closing->valid )
		{
			// Check if captured has children.
			if( $captured->children !== Null )
			{
				return( Util\Str::fmt( "{begin}\n{children}\n{closing}", begin: $captured->begin, children: $captured->children, closing: $captured->closing->syntax ) );
			}
			return( Util\Str::fmt( "{begin}\n{closing}", begin: $captured->begin, closing: $captured->closing->syntax ) );
		}
		else {
			
			// Check if captured has children.
			if( $captured->children !== Null )
			{
				return( Util\Str::fmt( "{begin}\n{children}", begin: $captured->begin, children: $captured->children ) );
			}
			return( $captured->begin );
		}
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
	 * Remove all last line.
	 *
	 * @access Protected
	 *
	 * @params Array $content
	 * @params Bool $closing
	 *
	 * @return Array
	 */
	protected function removeLastLine( Array $content, Bool $closing ): Array
	{
		if( count( $content ) !== 0 )
		{
			// Get last content value.
			$last = end( $content );
			
			// Check if last deep content is empty
			// And syntax does not have closing.
			if( ( $last  === "" || $last === "\n" ) && $closing === False )
			{
				// Unset last content.
				array_pop( $content );
				
				// Looping!
				$content = $this->removeLastLine( $content, $closing );
			}
		}
		return( $content );
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
	
	/*
	 * Resolve indentation value.
	 *
	 * @access Protected
	 *
	 * @params String $indent
	 *
	 * @return String
	 */
	protected function resolveIndent( String $indent ): String
	{
		// Split indentation with new line.
		$split = explode( "\n", $indent );
		
		// Get last splited indentation.
		return( end( $split ) );
	}
	
}

?>