<?php

namespace Yume\Fure\View\Template;

use Yume\Fure\Support\Data;
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
			"doctype",
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
		// Set syntax tokens.
		$this->token = [
			...$this->tags['paired'],
			...$this->tags['unpaired']
		];
		
		// Call parent constructor.
		parent::__construct( $context, $configs );
	}
	
	public function extract( ? String $value )//: Array
	{
		// Copy object instance.
		$self = $this;
		
		// Attribute stacks.
		$attr = [
			"data" => []
		];
		
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
		$replace = RegExp\RegExp::replace( "/(?:(?<attribute>(?<dynamic>\-{2,})*\b(?<name>[a-zA-Z\_\x80-\xff][a-zA-Z0-9\_\-\x80-\xff]*)(\s*=\s*(?<value>(?:\"[^\"\\\]*(?:\\.[^\"\\\]*)*\"|\'[^\'\\\]*(?:\\.[^\'\\\]*)*\'|[^\s]+)))*\b))/ms", $value, static function( Array $match ) use( &$attr, $self )
		{
			// Check if dynamical syntax is exists.
			if( valueIsNotEmpty( $match['dynamic'] ) )
			{
				// Check if dynamical syntax is valid.
				if( strlen( $match['dynamic'] ) !== 2 ) throw new TemplateSyntaxError( $match['dynamic'], $self->context->view, $self->line );
			}
		});
		
		var_dump( $attr );
		
		// Check if replace is not empty.
		if( valueIsNotEmpty( $replace ) )
		{
			// Get first character.
		}
		exit;
		return( $value );
	}
	
	public function isPaired( String $tag ): Bool
	{
		return( in_array( strtolower( $tag ), $this->tags['paired'] ) );
	}
	
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
		if( $captured->colon )
		{
			if( $this->isUnpaired( $captured->token ) )
			{
				throw new TemplateSyntaxError( f( "\"{}\" is an unpaired tag and does not support single-line content and inner content", $captured->token ), $captured->view, $captured->line, 0 );
			}
			else {
				if( $captured->children )
				{
					if( valueIsNotEmpty( $captured->outline ) )
					{
						return( f( "<{captured.inline}>{captured.outline}\n{captured.children}\n</{captured.token}>", captured: $captured ) );
					}
					echo "Has Children\n";
				}
				echo "No Children\n";
			}
		}
		else {
			if( $this->isPaired( $captured->token ) )
			{
				echo "Paired\n";
			}
			else {
				if( valueIsNotEmpty( $captured->outline ) )
				{
					return( f( "<{captured.inline} />{captured.outline}", captured: $captured ) );
				}
				return( f( "<{captured.inline} />", captured: $captured ) );
			}
		}
		echo "\n";
		echo $captured;
		exit;
	}
	
}

?>