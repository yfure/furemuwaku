<?php

namespace Yume\Fure\View\Template;

use SensitiveParameter;

use Yume\Fure\Config;
use Yume\Fure\Support\Data;
use Yume\Fure\Util;
use Yume\Fure\Util\RegExp;

/*
 * TemplateEngine
 *
 * I have thanked you O Allah,
 * thanks to you I can create and complete the
 * PHP Template Engine that supports this Indentation O Allah.
 *
 * @package Yume\Fure\View\Template
 */
class TemplateEngine implements TemplateEngineInterface
{
	
	public const TOKEN_COMMENT = 56788;
	public const TOKEN_MULTILINE = 65462;
	public const TOKEN_OUTPUT = 75227;
	public const TOKEN_SHORTLINE = 86722;
	
	protected Readonly Bool $commentRemove;
	protected Readonly Bool $commentDisplay;
	
	protected Readonly Int $indentLength;
	protected Readonly String $indentValue;
	
	protected Readonly Data\DataInterface $match;
	protected Readonly Data\DataInterface $patterns;
	
	/*
	 * Syntax processing class.
	 *
	 * @access Protected Readonly
	 *
	 * @values Array
	 */
	protected Readonly Array $syntax;
	
	private Array $syntaxDefault = [
		TemplateSyntaxComponent::class,
		TemplateSyntaxHTML::class,
		TemplateSyntaxPHP::class
	];
	
	public function __construct( Config\Config $configs )
	{
		
		$this->syntax = [
			"default" => $this->syntaxDefault,
			"custom" => $configs->syntax->__toArray()
		];
		
		$this->commentRemove = $configs->comment->remove ?? True;
		$this->commentDisplay = $configs->comment->display ?? False;
		
		$this->indentLength = $configs->indent->length ?: 4;
		$this->indentValue = $config->indent->value ?? "\x20";
		
		$this->patterns = new Data\Data;
		
		$this->patterns->comment = new RegExp\Pattern( flags: "msJ", pattern: implode( "", [
			"(?<comment>",
				"^",
				"(?<indent>\s\s\s\s+)*",
				"(?<taggar>\\#",
					"(?:",
						"(?<html>",
							"\<(?<text>.*?)(?<!\\\)\>",
						")",
						"|",
						"(?<text>[^\n]*)",
					")",
				")",
			")",
			"|",
			"(?<!\\\)",
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
			")"
		]));
		
		$this->patterns->multiline = new RegExp\Pattern( flags: "ms", pattern: implode( "", [
			"^(?<multiline>",
				"(?<indent>\s\s\s\s+)*",
				"(?:\@)",
				"(?<inline>",
					"(?<token>[a-zA-Z0-9]*)",
					"(?:[\s\t]*)",
					"(?<value>.*?)",
				")",
				"(?<!\\\)",
				"(?<symbol>",
					"(?<colon>\:)",
						"|",
					"(?<semicolon>\;)",
				")",
				"(?<outline>([^\n]*))",
			")$"
		]));
		
		$this->patterns->shortline = new RegExp\Pattern( flags: "ms", pattern: implode( "", [
			"(?<!\\\)",
			"(?:\@)",
			"(?<shortline>",
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
			")"
		]));
		
		// Match matching.
		$match = [
			"line" => 0,
			"type" => 0,
			"view" => [
				"raw" => Null,
				"name" => Null,
				"split" => []
			]
		];
		
		$this->match = new Data\Data;
		$this->match->before = $match;
		$this->match->after = $match;
		$this->match->iter = 0;
	}
	
	/*
	 * Handle matched syntax.
	 *
	 * @access Private
	 *
	 * @params String $view
	 * @params String $template
	 * @params Yume\Fure\Support\Data\DataInterface $match
	 *
	 * @return Array|String
	 */
	private function handle( $view, $template, $match )//: Array | String
	{
		$self = $this;
		$syntax = new Data\Data([
			"view" => [
				"name" => $view,
				"template" => $template
			],
			"type" => $match->token,
			"match" => $match->match,
		]);
		
		return( match( $match->token )
		{
			self::TOKEN_OUTPUT => $this->processOutput( $syntax ),
			self::TOKEN_COMMENT => $this->processComment( $syntax ),
			
			/*
			 * Handles if there is a syntax error,
			 * it also checks whether the syntax
			 * has a token or not.
			 *
			 * @return Array|String
			 *
			 * @throws Yume\Fure\View\Template\TemolateSyntaxError
			 */
			self::TOKEN_SHORTLINE,
			self::TOKEN_MULTILINE => call_user_func( static function() use( &$syntax, $self ): Array | String
			{
				// Check if syntax has token.
				if( valueIsNotEmpty( $syntax->match->token ) )
				{
					// ...
					if( $this->isMultilineToken( $syntax->token ) )
					{
					
					}
				}
				throw new TemplateSyntaxError( $syntax->match->matched, $syntax->view->name, $self->getLine( $syntax->match->matched, $syntax->view->template ) );
			})
		});
	}
	
	/*
	 * Return if token is comment.
	 *
	 * @access Public
	 *
	 * @params Int $token
	 *
	 * @return Bool
	 */
	public function isCommentToken( Int $token ): Bool
	{
		return( $token === self::TOKEN_COMMENT );
	}
	
	/*
	 * Return if token is multiline.
	 *
	 * @access Public
	 *
	 * @params Int $token
	 *
	 * @return Bool
	 */
	public function isMultilineToken( Int $token ): Bool
	{
		return( $token === self::TOKEN_MULTILINE );
	}
	
	/*
	 * Return if token is output.
	 *
	 * @access Public
	 *
	 * @params Int $token
	 *
	 * @return Bool
	 */
	public function isOutputToken( Int $token ): Bool
	{
		return( $token === self::TOKEN_OUTPUT );
	}
	
	/*
	 * Return if token is shortline.
	 *
	 * @access Public
	 *
	 * @params Int $token
	 *
	 * @return Bool
	 */
	public function isShortlineToken( Int $token ): Bool
	{
		return( $token === self::TOKEN_SHORTLINE );
	}
	
	/*
	 * Capture syntax.
	 *
	 * @access Private
	 *
	 * @params String $template
	 *
	 * @return False|Yume\Fure\Support\Data\DataInterface
	 */
	private function match( String $template ): False | Data\DataInterface
	{
		// Mapping available patterns.
		foreach( $this->patterns->__toArray() As $type => $pattern )
		{
			// If there are captured syntax.
			if( $match = $pattern->match( $template ) )
			{
				return( new Data\Data([
					
					// Cleaning matching result.
					"match" => RegExp\RegExp::clear( $match, True ),
					
					// Get token type.
					"token" => match( strtolower( $type ) )
					{
						"output" => self::TOKEN_OUTPUT,
						"comment" => self::TOKEN_COMMENT,
						"shortline" => self::TOKEN_SHORTLINE,
						"multiline" => self::TOKEN_MULTILINE
					}
				]));
			}
		}
		return( False );
	}
	
	/*
	 * Normalize raw template.
	 *
	 * @access Public
	 *
	 * @params String $template
	 *
	 * @return String
	 */
	public function normalize( String $template ): String
	{
		return( str_replace( "\t", str_repeat( $this->indentValue, $this->indentLength ), $template ) );
	}
	
	public function parse( String $view, String $template )//: String
	{
		// Normalize template.
		$template = $this->normalize( $template );
		
		while( $match = $this->match( $template ) )
		{
			$result = $this->handle( $view, $template, $match );
			
			var_dump([
				$result,
				$match
			]);
			exit;
		}
		return( $template );
	}
	
	private function processComment( $syntax )//: ? String
	{
		// Default replacement is blank for comment.
		$replace = "";
		
		// Check if comment is taggar type.
		if( isset( $syntax->match->taggar ) )
		{
			// Check if comment is html type.
			if( isset( $syntax->match->html ) )
			{
				// Change to html comment only.
				$replace = f( "<!--{}-->", $syntax->match->text ?? "" );
			}
			else {
				
				// Check if comment taggar is Doctument Type.
				if( $taggar = RegExp\RegExp::match( "/^(?:\!DOCTYPE(?:\s*)\/(?:\s*)(?<type>[a-zA-Z0-9_\-]+))(?:[\s\t]*)$/i", $syntax->match->text ?? "" ) )
				{
					$replace = f( "<!DOCTYPE {}>", $taggar['type'] );
				}
			}
		}
		else {
			
			// Change to html comment only.
			$replace = f( "<!--{}-->", $match['text'] ?? "" );
		}
		echo htmlspecialchars( $replace );
	}
	
}

?>