<?php

namespace Yume\Fure\View\Template;

use SensitiveParameter;

use Yume\Fure\Support\Data;
use Yume\Fure\Util;
use Yume\Fure\Util\RegExp;

/*
 * TemplateParser
 *
 * @package Yume\Fure\View\Template
 */
class TemplateParser// implements TemplateParserInterface
{
	
	public Readonly Data\DataInterface $match;
	public Readonly Data\DataInterface $patterns;
	
	public function __construct()
	{
		
		$this->pattern = new Data\Data;
		
		$this->pattern->commented = new RegExp\Pattern( flags: "msJ", pattern: implode( "", [
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
		]);
		
		$this->pattern->multilines = new RegExp\Pattern( flags: "ms", pattern: implode( "", [
			"^(?<multilines>",
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
		]);
		
		$this->pattern->shorttyping = new RegExp\Pattern( flags: "ms", pattern: implode( "", [
			"(?<!\\\)",
			"(?:\@)",
			"(?<shorttyping>",
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
		]);
		
		$this->match = new Data\Data([
			"iteration" => 0,
			"previous" => [],
			"currenty" => [
				"line" => 0,
				"view" => Null
			]
		]);
	}
	
	public function hasCached( String $view )//: Bool
	{
		return( File\File::exists( $this->path( $view, True ) ) );
	}
	
	public function hasModify( String $view )//: Bool
	{
		return( File\File::modify( $this->path( $view ), 60 ) );
	}
	
	public function handle( String $view, Array $match )//: String
	{}
	
	private function matcher( String $view, String $template )//: String
	{
	
	}
	
	public function parse( String $view, ? String $template = Null )//: String
	{}
	
}

?>