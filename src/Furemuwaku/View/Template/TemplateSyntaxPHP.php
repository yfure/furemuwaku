<?php

namespace Yume\Fure\View\Template;

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
			"elif",
			"else",
			"elseif",
			"empty",
			"for",
			"foreach",
			"if" => [
				"format" => [
					"paired" => "<?php if( {condition} ) { ?>\n{content}\n{indent}<?php } ?>",
					"unpaired" => "<?php if( {condition} ) { ?>{content}<?php } ?>",
					"nextmatch" => "<?php if( {condition} ) { ?>\n{content}\n{closing}"
				],
				"inline" => [
					"unpaired" => "<?php if( {condition} ) { ?>{content}<?php } ?>",
					"nextmatch" => "<?php if( {condition} ) { ?>\n{content}\n{closing}"
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
		return( $this )->token[strtolower( $token )]['nextmatch']['require'];
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	public function process( TemplateCaptured $syntax ): String
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
		
		echo $closing;
		echo "\n\n";
		echo $syntax;
		exit;
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
	
	private function closing( TemplateCaptured $syntax )
	{
		if( $this->isMultimatch( $syntax->token ) )
		{
			// ...
			if( valueIsEmpty( $syntax->closing->syntax ) ) return( False );
			
			// If closing syntax is valid.
			if( $syntax->closing->valid ) return( False );
			
			// ...
			$regexp = f( "/^(?<matched>(?<indent>\s\{{},\})(?:\@)(?<inline>(?<token>(?:{implode(\{\})})\b)(?:[\s\t]*)(?<value>.*?))(?<!\\\)(?<symbol>(?<colon>\:)|(?<semicolon>\;))(?<outline>([^\n]*)))/ms", $syntax->indent->length, [ "|", $this->token[$syntax->tokenLower]['nextmatch']['token'] ] );
			
			// ...
			$content = $this->context->getTemplateSLine( $syntax->closing->line );
			
			if( $match = RegExp\RegExp::match( $regexp, $content ) )
			{
				// Template captured data.
				$captured = new TemplateCaptured;
				
				// Set matched results.
				$captured->match = RegExp\RegExp::clear( $match, True );
				
				// Set token name.
				$captured->token = $captured->match->token;
				
				// Set normalized token name.
				$captured->tokenLower = strtolower( $captured->match->token );
				$captured->tokenUpper = strtolower( $captured->match->token );
				
				$captured->multiline = True;
				$captured->view = $syntax->view;
				$captured->value = $captured->match->value ?? Null;
				$captured->inline = $captured->match->inline ?? Null;
				$captured->outline = $captured->match->outline ?? Null;
				$captured->symbol = $captured->match->symbol;
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
					$captured->indent->value = $this->context->resolveIndent( $captured->match->indent );
					
					// Set indentation length.
					$captured->indent->length = strlen( $captured->indent->value );
				}
				
				// Set line number of syntax.
				$captured->line = $this->context->getLine(
					
					// Re-Build begin syntax.
					$captured->begin = $this->context->reBuildSyntaxBegin( $captured )
				);
				
				// Check if syntax is single line.
				if( $captured->outline && $captured->semicolon )
				{
					// Check if syntax has outline value.
					if( valueIsNotEmpty( $captured->outline ) && $this->context->isComment( $captured->outline ) === False )
					{
						throw new TemplateSyntaxError( $captured->outline, $this->view, $this->context->getLine( $captured->begin ) );
					}
				}
				
				// Capture deep and closing content.
				$this->context->parseDeep( $captured );
				
				// Build full captured syntax.
				$captured->raw = $this->context->reBuilSyntaxCapture( $captured );
				
				echo $captured;
			}
		}
		
		return( False );
	}
	
}

?>