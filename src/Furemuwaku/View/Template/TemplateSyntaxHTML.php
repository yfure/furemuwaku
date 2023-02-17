<?php

namespace Yume\Fure\View\Template;

use Yume\Fure\Config;
use Yume\Fure\Support\Data;
use Yume\Fure\Util;
use Yume\Fure\Util\RegExp;

/*
 * TemplateSyntaxHTML
 *
 * @package Yume\Fure\View\Template
 *
 * @extends Yume\Fure\View\Template\TemplateSyntax
 */
class TemplateSyntaxHTML extends TemplateSyntax
{
	
	/*
	 * HTML Valid Tag Names.
	 *
	 * @access Protected
	 *
	 * @values Array
	 */
	protected Array $tags = [
		"paired" => [
			"a",
			"abbr",
			"acronym",
			"address",
			"applet",
			"article",
			"aside",
			"audio",
			"b",
			"basefont",
			"bdi",
			"bdo",
			"big",
			"blockquote",
			"body",
			"button",
			"canvas",
			"caption",
			"center",
			"cite",
			"code",
			"colgroup",
			"data",
			"datalist",
			"dd",
			"del",
			"details",
			"dfn",
			"dialog",
			"dir",
			"div",
			"dl",
			"dt",
			"em",
			"fieldset",
			"figcaption",
			"figure",
			"font",
			"footer",
			"form",
			"frame",
			"frameset",
			"h1",
			"h2",
			"h3",
			"h4",
			"h5",
			"h6",
			"head",
			"header",
			"html",
			"i",
			"iframe",
			"ins",
			"kbd",
			"label",
			"legend",
			"li",
			"main",
			"map",
			"mark",
			"meter",
			"nav",
			"noframes",
			"noscript",
			"object",
			"ol",
			"optgroup",
			"option",
			"output",
			"p",
			"picture",
			"pre",
			"progress",
			"q",
			"rp",
			"rt",
			"ruby",
			"s",
			"samp",
			"script",
			"section",
			"select",
			"small",
			"span",
			"strike",
			"strong",
			"style",
			"sub",
			"summary",
			"sup",
			"svg",
			"table",
			"tbody",
			"td",
			"template",
			"textarea",
			"tfoot",
			"th",
			"thead",
			"time",
			"title",
			"tr",
			"tt",
			"u",
			"ul",
			"var",
			"video"
		],
		"unpaired" => [
			"area",
			"base",
			"br",
			"col",
			"embed",
			"hr",
			"img",
			"input",
			"keygen",
			"link",
			"meta",
			"param",
			"source",
			"track",
			"wbr"
		]
	];
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntax
	 *
	 */
	public function __construct( Template $engine, Config\Config $configs )
	{
		// Checks if the constructor is called from the child class
		// And if the child class doesn't define a token.
		if( $this->hasToken() === False )
		{
			// Set syntax tokens.
			$this->token = [
				...$this->tags['paired'],
				...$this->tags['unpaired']
			];
		}
		
		// Call parent constructor.
		parent::__construct( $engine );
	}
	
	/*
	 * HTML Attribute builder.
	 *
	 * @access Public
	 *
	 * @params Array $attr
	 *
	 * @return String
	 */
	public function builder( Array $attr ): String
	{
		// Copy object instance.
		$self = $this;
		
		// Attribute stack.
		$stack = [];
		
		// Mapping attributes.
		Util\Arr::map( $attr, static function( Int $i, String $name, Mixed $option ) use( &$stack, $self )
		{
			// Check attribute is data.
			if( $name === "data" )
			{
				// Mapping datasets.
				Util\Arr::map( $option, static function( Int $i, String $name, Mixed $option ) use( &$stack, $self )
				{
					// Push attribute.
					$stack[] = match( True )
					{
						$option['dynamic'] => sprintf( valueIsNotEmpty( $option['values'] ) ? "data-%s=\"<?= %s ?>\"" : "data-%s=\"\"", $name, $self->unclean( $self->clear( $option['values'] ) ) ),
						$option['default'] => sprintf( "data-%s=\"%s\"", $name, $self->unclean( $self->clear( $option['values'] ) ) ),
						default => f( "data-{}", $name )
					};
				});
			}
			else {
				
				// Push attribute.
				$stack[] = match( True )
				{
					$option['dynamic'] => sprintf( valueIsNotEmpty( $option['values'] ) ? "%s=\"<?= %s ?>\"" : "%s=\"\"", $name, $self->unclean( $self->clear( $option['values'] ) ) ),
					$option['default'] => sprintf( "%s=\"%s\"", $name, $self->unclean( $self->clear( $option['values'] ) ) ),
					default => $name
				};
			}
		});
		return( implode( "\x20", $stack ) );
	}
	
	/*
	 * Extract html attributes.
	 *
	 * @access Public
	 *
	 * @params String $params
	 * @params Bool $build
	 *
	 * @return Array|String
	 */
	public function extract( ? String $params, Bool $build = True ): Array | String
	{
		// Copy object instance.
		$self = $this;
		
		// Attribute stacks.
		$attr = [];
		$data = [];
		
		// Attribute regular expression.
		$regexp = "/(?<matched>\b(?:(?<data>data)\-)*(?<name>[a-zA-Z\_\x80-\xff](?:[a-zA-Z0-9\_\-\x80-\xff]*[a-zA-Z\_\x80-\xff])*)(?:(?<dynamic>(?:\s*(?<!\\\)(\=)\s*)*(?:\[)(?<value>[^\]\\\]*(?:.[^\]\\\]*)*)(?:\]))|(?<default>(?:\s*(?<!\\\)(\=)\s*)(?:\"(?<value>.*?)(?<!\\\)\"|\'(?<value>[^\'\\\]*(?:\\.[^\'\\\]*)*)\'|(?<value>[^\s\"\']+))))*)/msiJ";
		
		// Capture and replace all valid attributes.
		$replace = RegExp\RegExp::replace( $regexp, $params, function( Array $match ) use( &$attr, &$data, $self, $params )
		{
			// Clear match result.
			$match = RegExp\RegExp::clear( $match );
			
			// Get attribute name.
			$name = strtolower( $match['name'] );
			
			// Default attribute value.
			$value = [
				"dynamic" => False,
				"default" => False,
				"value" => True
			];
			
			// If attribute is dynamically value.
			if( isset( $match['dynamic'] ) )
			{
				// Set value type as dynamic.
				$value['dynamic'] = True;
				
				// Normalize dynamic value.
				$value['values'] = $this->normalize( $match['value'] ?? "", [ "\"", "\'", "\\[", "\\]", "\\:", "\\;", "\\=" ] );
			}
			
			// If attribute is default type.
			else if( isset( $match['default'] ) )
			{
				// Checks if there are double quotes in the attribute value.
				if( strpos( $match['value'] ?? "", "\"" ) === False )
				{
					// Set value type as default.
					$value['default'] = True;
					
					// Normalize dynamic value.
					$value['values'] = $this->normalize( $match['value'] ?? "", [ "\'", "\\[", "\\]", "\\:", "\\;", "\\=" ] );
				}
				else {
					
					/*
					 * I don't know why PHP is not throwing
					 * this error, if the error is thrown
					 * it will just show a blank page,
					 * I will fix this as soon as possible.
					 *
					 */
					throw new TemplateSyntaxError( "Non-dynamic HTML attribute values cannot contain double quotes", $this->getCurrentViewName(), $this->getCurrentLine(), 0 );
					//$value['values'] = htmlspecialchars( $this->normalize( $match['value'] ?? "", [ "\"", "\'", "\\[", "\\]", "\\:", "\\;", "\\=" ] ) );
				}
			}
			
			// Check if attribute is data.
			if( valueIsNotEmpty( $match['data'] ) )
			{
				// Push attribute on data.
				$data[$name] = $value;
			}
			else {
				$attr[$name] = $value;
			}
		});
		
		/*
		 * Check if there are characters that are not caught.
		 *
		 * And if after being replaced there are characters that
		 * are not caught then these characters will be
		 * considered invalid syntax.
		 *
		 */
		if( $char = RegExp\RegExp::match( "/([^\s])/", $replace ) )
		{
			throw new TemplateSyntaxError( f( "Invalid \"{}\" character for attribute, in {}", $char[0], $this->unclean( $params ) ), $this->getCurrentViewName(), $this->getCurrentLine(), 0 );
		}
		else {
			
			// Add datasets.
			$attr['data'] = $data;
			
			// Return extracted attributes or builded attribute.
			return( $build ? $this->builder( $attr ) : $attr );
		}
	}
	
	/*
	 * Return if tag is paired type.
	 *
	 * @access Public
	 *
	 * @params String $tag
	 *
	 * @return Bool
	 */
	public function isPaired( String $tag ): Bool
	{
		return( in_array( strtolower( $tag ), $this->tags['paired'] ) );
	}
	
	/*
	 * Return if tag is unpaired type.
	 *
	 * @access Public
	 *
	 * @params String $tag
	 *
	 * @return Bool
	 */
	public function isUnpaired( String $tag ): Bool
	{
		return( in_array( strtolower( $tag ), $this->tags['unpaired'] ) );
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	public function process( Data\DataInterface $syntax ): Array | String
	{
		// Check if tag has attributes.
		if( valueIsNotEmpty( $syntax->value ) )
		{
			// Get extracted attributes.
			$attr = $this->extract( $syntax->value );
			
			// Default captured attribute.
			$syntax->attr = "";
			
			// Check if tag has attribute.
			if( valueIsNotEmpty( $attr ) )
			{
				$syntax->attr = "\x20$attr";
			}
		}
		
		// Check if tag has inner content.
		if( $syntax->children )
		{
			// Remove all first empty line in content.
			$children = $this->removeFirstLine( explode( "\x0a", $syntax->children ) );
			
			// Check if inner content is not empty.
			if( count( $children ) !== 0 )
			{
				// Re-Parse inner content.
				//$syntax->children = $this->match( implode( "\x0a", $children ) );
				$syntax->children = implode( "\x0a", $children );
			}
			else {
				$syntax->children = Null;
			}
		}
		
		// Check if tag captured with colon symbol.
		if( $syntax->colon )
		{
			// Check if tag is unpaired type.
			if( $this->isUnpaired( $syntax->token ) )
			{
				throw new TemplateSyntaxError( f( "\"{}\" is an unpaired tag and does not support single-line content and inner content", $syntax->token ), $syntax->view->name, $syntax->line, 0 );
			}
			else {
				
				// Check if tag has inner content.
				if( $syntax->children )
				{
					// Default format with inner content.
					$format = "<{token}{attr}>\x0a{children}\x0a{indent}</{token}>";
					
					// Check if tag has outline content.
					if( valueIsNotEmpty( $syntax->outline ) )
					{
						// Add outline in tag inner.
						$format = "<{token}{attr}>{outline}\x0a{children}\x0a{indent}</{token}>";
					}
				}
				else {
					
					// Default format without inner content.
					$format = "<{token}{attr}></{token}>";
					
					// Check if tag has outline content.
					if( valueIsNotEmpty( $syntax->outline ) )
					{
						// Add outline into inner content.
						$format = "<{token}{attr}>{outline}</{token}>";
					}
				}
			}
		}
		else {
			
			// Check if tag is paired type.
			if( $this->isPaired( $syntax->token ) )
			{
				// Default format without outline content.
				$format = "<{token}{attr}></{token}>";
				
				// Check if tag has inner content.
				if( valueIsNotEmpty( $syntax->children ) )
				{
					// Add outline into inner content.
					$format = "<{token}{attr}>{outline}{children}</{token}>";
				}
			}
			else {
				
				// Default format without outline content.
				$format = "<{token}{attr} />";
				
				// Check if tag has inner content.
				if( valueIsNotEmpty( $syntax->children ) )
				{
					throw new TemplateSyntaxError( f( "\"{}\" is an unpaired tag and does not support single-line content and inner content", $syntax->token ), $syntax->view->name, $syntax->line, 0 );
				}
				
				// Check if tag has outine content.
				if( valueIsNotEmpty( $syntax->outline ) )
				{
					// Add outline into inner content.
					$format = "<{token}{attr} />{outline}";
				}
			}
		}
		
		// Return formated content.
		return( $this )->format( $format, $syntax );
	}
	
	/*
	 * Format HTML.
	 *
	 * @access Protected
	 *
	 * @params String $format
	 * @params Yume\Fure\Support\Data\DataInterface $values
	 *
	 * @return String
	 */
	protected function format( String $format, Data\DataInterface $values ): String
	{
		return( f( $format, ...[
			"attr" => $values->attr ?? "",
			"token" => $values->token ?? "",
			"indent" => $values->indent->value,
			"outline" => $this->clear( $values->outline ?? "" ),
			"children" => $values->children ?? ""
		]));
	}
	
}

?>