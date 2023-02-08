<?php

namespace Yume\Fure\View\Template;

use Yume\Fure\Util;
use Yume\Fure\Util\RegExp;

/*
 * TemplateSyntaxPHP
 *
 * @package Yume\Fure\View\Template
 *
 * @extends Yume\Fure\View\Template\TemplateSyntax
 */
final class TemplateSyntaxPHP extends TemplateSyntax
{
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntax
	 *
	 */
	public function __construct( TemplateInterface $context, Array | Data\DataInterface $configs )
	{
		// Set syntax tokens.
		$this->token = [
			"break",
			"catch",
			"case",
			"continue",
			"default",
			"do",
			"elif" => [
				"format" => [
					"paired" => "{indent}<?php \} else if( {condition} ) \{ ?>\n{content}\n{indent}<?php \} ?>",
					"unpaired" => "{indent}<?php \} else if( {condition} ) \{ ?>{content}<?php \} ?>",
					"nextmatch" => "{indent}<?php \} else if( {condition} ) \{ ?>\n{content}\n{nextmatch}"
				],
				"inline" => [
					"unpaired" => "<?php \} else if( {condition} ) \{ ?>{content}<?php \} ?>",
					"nextmatch" => "<?php \} else if( {condition} ) \{ ?>\n{content}\n{nextmatch}"
				],
				"paired" => True,
				"unpaired" => True,
				"nextmatch" => [
					"token" => [
						"elif",
						"else",
						"elseif",
						"empty",
						"isset"
					],
					"require" => False
				],
				"condition" => [
					"allow" => True,
					"require" => True
				]
			],
			"else" => [
				"format" => [
					"paired" => "{indent}<?php \} else \{ ?>\n{content}\n{indent}<?php } ?>",
					"unpaired" => "{indent}<?php \} else \{ ?>{content}<?php } ?>",
				],
				"inline" => [
					"unpaired" => "<?php \} else \{ ?>{content}<?php } ?>"
				],
				"paired" => True,
				"unpaired" => True,
				"nextmatch" => False,
				"condition" => [
					"allow" => False,
					"require" => False
				]
			],
			"elseif",
			"empty",
			"for",
			"foreach",
			"if" => [
				"format" => [
					"paired" => "<?php if( {condition} ) { ?>\n{content}\n{indent}<?php } ?>",
					"unpaired" => "<?php if( {condition} ) { ?>{content}<?php } ?>",
					"nextmatch" => "<?php if( {condition} ) { ?>\n{content}\n{nextmatch}"
				],
				"inline" => [
					"unpaired" => "<?php if( {condition} ) { ?>{content}<?php } ?>",
					"nextmatch" => "<?php if( {condition} ) { ?>\n{content}\n{nextmatch}"
				],
				"paired" => True,
				"unpaired" => True,
				"nextmatch" => [
					"token" => [
						"elif",
						"else",
						"elseif",
						"empty",
						"isset"
					],
					"require" => False
				],
				"condition" => [
					"allow" => True,
					"require" => True
				]
			],
			"isset",
			"match",
			"puts",
			"switch",
			"throw",
			"try",
			"use",
			"while"
		];
		
		// Call parent constructor.
		parent::__construct( $context, $configs );
	}
	
	/*
	 * Return if syntax require condition.
	 *
	 * @access Public
	 *
	 * @params String $token
	 *
	 * @return Bool
	 */
	public function isCondition( String $token ): Bool
	{
		return( $this )->token[strtolower( $token )]['condition']['allow'];
	}
	
	/*
	 * Return if syntax doesn't require condition.
	 *
	 * @access Public
	 *
	 * @params String $token
	 *
	 * @return Bool
	 */
	public function isUncondition( String $token ): Bool
	{
		if( $this->isCondition( $token ) )
		{
			return( $this->token[strtolower( $token )]['condition']['require'] === False );
		}
		return( $this )->token[strtolower( $token )]['condition']['allow'];
	}
	
	/*
	 * Return if syntax supported inner content.
	 *
	 * @access Public
	 *
	 * @params String $token
	 *
	 * @return Bool
	 */
	public function isPaired( String $token ): Bool
	{
		return( $this )->token[strtolower( $token )]['paired'];
	}
	
	/*
	 * Return if syntax unsupported inner content.
	 *
	 * @access Public
	 *
	 * @params String $token
	 *
	 * @return Bool
	 */
	public function isUnpaired( String $token ): Bool
	{
		return( $this )->token[strtolower( $token )]['unpaired'];
	}
	
	/*
	 * Return if syntax support & unsupported inner content.
	 *
	 * @access Public
	 *
	 * @params String $token
	 *
	 * @return Bool
	 */
	public function isMultipair( String $token ): Bool
	{
		return( $this->isPaired( $token ) && $this->isUnpaired( $token ) );
	}
	
	/*
	 * Return if syntax supported short typing.
	 *
	 * @access Public
	 *
	 * @params String $token
	 *
	 * @return Bool
	 */
	public function isShortable( String $token ): Bool
	{
		return( $this->token[strtolower( $token )]['inline'] !== False );
	}
	
	/*
	 * Return if syntax supported inner content.
	 *
	 * @access Public
	 *
	 * @params String $token
	 *
	 * @return Bool
	 */
	public function isInnerable( String $token ): Bool
	{
		// ...
	}
	
	/*
	 * Return if syntax supported multimatch.
	 *
	 * @access Public
	 *
	 * @params String $token
	 *
	 * @return Bool
	 */
	public function isMultimatch( String $token ): Bool
	{
		return( $this->token[strtolower( $token )]['nextmatch'] !== False );
	}
	
	public function isMultimatchSupport( String $token, String $catch ): Bool
	{
		return( in_array( strtolower( $catch ), $this->token[strtolower( $token )]['nextmatch']['token'] ) );
	}
	
	public function isMultimatchRequired( String $token ): Bool
	{
		return( $this->isMultimatch( $token ) && $this->token[strtolower( $token )]['nextmatch']['require'] );
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	public function process( TemplateCaptured $syntax ): Array | String
	{
		// ...
		$this->assert( $syntax );
		
		if( $closing = $this->closing( $syntax ) )
		{
			
		}
		else {
			if( $this->isMultimatchRequired( $syntax->token ) )
			{
				echo $syntax->token;
				echo ">Multimatch Required\n";
			}
		}
		
		if( $syntax->multiline )
		{
			if( $syntax->semicolon )
			{
				$format = $this->token[$syntax->token]['format']['unpaired'];
			}
			else {
				
				$format = $this->token[$syntax->token]['format']['paired'];
				
				if( $closing !== False )
				{
					$format = $this->token[$syntax->token]['format']['nextmatch'];
				}
				else {
					// ...
				}
			}
		}
		else {
			
			$format = $this->token[$syntax->token]['inline']['unpaired'];
			
			if( $closing !== False )
			{
				$format = $this->token[$syntax->token]['inline']['nextmatch'];
			}
		}
		
		$format = $this->format( $format, $syntax );
		
		return([
			"result" => $format,
			"raw" => $syntax->raw
		]);
	}
	
	/*
	 * Syntax Assertion.
	 *
	 * @access Private
	 *
	 * @params Yume\Fure\View\Template\TemplateCaptured $syntax
	 *
	 * @return Void
	 */
	private function assert( TemplateCaptured $syntax ): Void
	{
		if( $syntax->multiline )
		{
			if( $syntax->semicolon )
			{
				if( $this->isMultipair( $syntax->token ) === False &&
					$this->isUnpaired( $syntax->token ) === False )
				{
					echo $syntax->token;
					echo ">Unsupported Short Typing\n";
				}
			}
			else {
				if( $this->isMultipair( $syntax->token ) === False &&
					$this->isPaired( $syntax->token ) === False )
				{
					echo $syntax->token;
					echo ">Unsupported Inner Content\n";
				}
			}
		}
		else {
			if( $this->isShortable( $syntax->token ) === False )
			{
				echo $syntax->token;
				echo ">Unsupported Short Typing\n";
			}
		}
		
		if( $syntax->value )
		{
			if( $this->isUncondition( $syntax->token ) )
			{
				echo $syntax->token;
				echo ">Unsupported Condition\n";
			}
		}
		else {
			if( $this->isCondition( $syntax->token ) )
			{
				echo $syntax->token;
				echo ">Required Condition\n";
			}
		}
	}
	
	/*
	 * Re-Match closing syntax.
	 *
	 * @access Private
	 *
	 * @params Yume\Fure\Support\Data\DataInterface $syntax
	 *
	 * @return Array|False|String
	 */
	private function closing( TemplateCaptured $syntax ): Array | False | String
	{
		$syntax->closing->nextmatch = [
			"syntax" => Null,
			"captured" => Null
		];
		
		if( $this->isMultimatch( $syntax->token ) )
		{
			// ...
			if( valueIsEmpty( $syntax->closing->syntax ) ) return( False );
			
			// If closing syntax is valid.
			if( $syntax->closing->valid ) return( False );
			
			// ...
			$regexp = f( "/^(?<matched>(?<multiline>(?<indent>\s\{{},\})(?:\@)(?<inline>(?<token>(?:{})\b)(?:[\s\t]*)(?<value>.*?))(?<!\\\)(?<symbol>(?<colon>\:)|(?<semicolon>\;))(?<outline>([^\n]*))))/ms", $syntax->indent->length, implode( "|", $this->token[$syntax->tokenLower]['nextmatch']['token'] ) );
			
			// ...
			$content = $this->context->getTemplateSLine( $syntax->closing->line );
			
			// ....
			if( $match = RegExp\RegExp::match( $regexp, $content ) )
			{
				$capture = $this->context->captured( $match );
				$process = $this->process( $capture );
				
				if( is_array( $process ) )
				{
					$syntax->closing->nextmatch->syntax = $process['result'] ?? $process[0];
					$syntax->closing->nextmatch->captured = $process['raw'] ?? $process[1];
				}
				else {
					$syntax->closing->nextmatch->syntax = $process;
					$syntax->closing->nextmatch->captured = $capture->raw;
				}
				
				if( $capture->closing->nextmatch->syntax )
				{
					$capture->closing->syntax = $capture->closing->nextmatch->captured;
					$capture->closing->valid = True;
					$capture->raw = $this->context->reBuilSyntaxCapture( $capture );
					$syntax->closing->syntax = $capture->raw;
					$syntax->closing->valid = True;
					$syntax->raw = $this->context->reBuilSyntaxCapture( $syntax );
				}
				else {
					$syntax->closing->syntax = $capture->raw;
					$syntax->closing->valid = True;
					$syntax->raw = $this->context->reBuilSyntaxCapture( $syntax );
				}
				return( $process );
			}
		}
		return( False );
	}
	
	/*
	 * Format PHP Syntax.
	 *
	 * @access Private
	 *
	 * @params String $format
	 * @params Yume\Fure\Support\Data\DataInterface $values
	 *
	 * @return String
	 */
	private function format( String $format, TemplateCaptured $syntax ): String
	{
		// Return formated syntax.
		return( f( $format, ...[
			"nextmatch" => $syntax->closing->nextmatch->syntax ?? "",
			"condition" => $syntax->value ?? "",
			"content" => $syntax->children ?? "",
			"indent" => $syntax->indent->value
		]));
	}
	
}

?>