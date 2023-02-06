<?php

namespace Yume\Fure\View\Template;

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
	public function __construct( TemplateInterface $context, Array | Data\DataInterface $configs )
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
		parent::__construct( $context, $configs );
	}
	
	public function builder( Array $attr ): String
	{
		// Copy object instance.
		$self = $this;
		
		// Attribute stack.
		$stack = [];
		
		// Mapping attributes.
		Util\Arr::map( $attr, static function( Int $i, String $name, Mixed $option ) use( &$stack )
		{
			// Check attribute is data.
			if( $name === "data" )
			{
				// Mapping datasets.
				Util\Arr::map( $option, static function( Int $i, String $name, Mixed $option ) use( &$stack )
				{
					// Push attribute.
					$stack[] = match( True )
					{
						$option['dynamic'] => f( "data-{}=\"<? {} ?>\"", $name, $option['value'] ),
						$option['default'] => f( "data-{}=\"{}\"", $name, $option['value'] ),
						defaut => f( "data-{}", $name )
					};
				});
			}
			else {
				
				// Push attribute.
				$stack[] = match( True )
				{
					$option['dynamic'] => f( "{}=\"<? {} ?>\"", $name, $option['value'] ),
					$option['default'] => f( "{}=\"{}\"", $name, $option['value'] ),
					defaut => $name
				};
			}
		});
		return( implode( "\x20", $stack ) );
	}
	
	public function extract( ? String $params, Bool $build = True )//: Array | String
	{
		// Copy object instance.
		$self = $this;
		
		// Attribute stacks.
		$attr = [];
		$data = [];
		
		// Attribute regular expression.
		$regexp = "/(?<matched>\b(?:(?<data>data)\-)*(?<name>[a-zA-Z\_\x80-\xff](?:[a-zA-Z0-9\_\-\x80-\xff]*[a-zA-Z\_\x80-\xff])*)(?:(?<dynamic>(?:\s*(?<!\\\)(\=)\s*)*(?:\[)(?<value>[^\]\\\]*(?:.[^\]\\\]*)*)(?:\]))|(?<default>(?:\s*(?<!\\\)(\=)\s*)(?:\"(?<value>[^\"\\\]*(?:\\.[^\"\\\]*)*)\"|\'(?<value>[^\'\\\]*(?:\\.[^\'\\\]*)*)\'|(?<value>[^\s\"\']+))))*)/msiJ";
		
		/*
		 * Capture and replace all attributes.
		 * Keep in mind that not all html attribute
		 * types can be captured here, because basically
		 * HTML has a fairly complex attribute syntax.
		 *
		 * If there are characters that are still left
		 * or not caught after being overwritten, it will
		 * be considered as a syntax error.
		 */
		$replace = RegExp\RegExp::replace( $regexp, $params, static function( Array $match ) use( &$attr, &$data, $self )
		{
			// Clear match result.
			$match = RegExp\RegExp::clear( $match );
			
			// Get attribute name.
			$name = strtolower( $match['name'] );
			
			// Get attribute value.
			$value = match( True )
			{
				// If attribute is dynamically value.
				isset( $match['dynamic'] ) => [
					"dynamic" => True,
					"default" => False,
					"value" => $match['value'] ?? ""
				],
				
				// If attribute is default typed.
				isset( $match['default'] ) => [
					"dynamic" => False,
					"default" => True,
					"value" => $match['value'] ?? ""
				],
				
				// If attribute is only name.
				default => [
					"dynamic" => False,
					"default" => False,
					"value" => True
				]
			};
			
			// Clear all backslash.
			$value['value'] = $self->normalize( $value['value'], [ "\"", "\'", "\\[", "\\]", "\\:", "\\;", "\\=" ] );
			
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
		
		// Check if there are characters that are not caught.
		if( $char = RegExp\RegExp::match( "/([^\s])/", $replace ) )
		{
			throw new TemplateSyntaxError( f( "Invalid \"{}\" character for attribute syntax", $char[0] ), $this->context->view, $this->context->getInline( $params ), 0 );
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
	public function process( TemplateCaptured $captured ): String
	{
		// Check if tag has attributes.
		if( valueIsNotEmpty( $captured->value ) )
		{
			// Get extracted attributes.
			$attr = $this->extract( $captured->value );
			
			// Default captured attribute.
			$captured->attr = "";
			
			// Check if tag has attribute.
			if( valueIsNotEmpty( $attr ) )
			{
				$captured->attr = "\x20$attr";
			}
		}
		
		// Check if tag has inner content.
		if( $captured->children )
		{
			// Remove all first empty line in content.
			$children = $this->removeFirstLine( explode( "\n", $captured->children ) );
			
			// Check if inner content is not empty.
			if( count( $children ) !== 0 )
			{
				// Re-Parse inner content.
				$captured->children = $this->context->parse( implode( "\n", $children ) );
			}
			else {
				$captured->children = Null;
			}
		}
		
		// Check if tag captured with colon symbol.
		if( $captured->colon )
		{
			// Check if tag is unpaired type.
			if( $this->isUnpaired( $captured->token ) )
			{
				throw new TemplateSyntaxError( f( "\"{}\" is an unpaired tag and does not support single-line content and inner content", $captured->token ), $captured->view, $captured->line, 0 );
			}
			else {
				
				// Check if tag has inner content.
				if( $captured->children )
				{
					// Default format with inner content.
					$format = "<{token}{attr}>\n{children}\n{indent}</{token}>";
					
					// Check if tag has outline content.
					if( valueIsNotEmpty( $captured->outline ) )
					{
						// Add outline in tag inner.
						$format = "<{token}{attr}>{outline}\n{children}\n{indent}</{token}>";
					}
				}
				else {
					
					// Default format without inner content.
					$format = "<{token}{attr}></{token}>";
					
					// Check if tag has outline content.
					if( valueIsNotEmpty( $captured->outline ) )
					{
						// Add outline into inner content.
						$format = "<{token}{attr}>{outline}</{token}>";
					}
				}
			}
		}
		else {
			
			// Check if tag is paired type.
			if( $this->isPaired( $captured->token ) )
			{
				// Default format without outline content.
				$format = "<{token}{attr}></{token}>";
				
				// Check if tag has outine content.
				if( valueIsNotEmpty( $captured->outline ) )
				{
					// Add outline into inner content.
					$format = "<{token}{attr}>{outline}</{token}>";
				}
			}
			else {
				
				// Default format without outline content.
				$format = "<{token}{attr} />";
				
				// Check if tag has outine content.
				if( valueIsNotEmpty( $captured->outline ) )
				{
					// Add outline into inner content.
					$format = "<{token}{attr} />{outline}";
				}
			}
		}
		
		// Return formated content.
		return( $this )->format( $format, $captured );
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
			"outline" => $values->outline ?? "",
			"children" => $values->children ?? ""
		]));
	}
	
}

?>