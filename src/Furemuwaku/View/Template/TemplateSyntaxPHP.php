<?php

namespace Yume\Fure\View\Template;

use Yume\Fure\Util;
use Yume\Fure\Util\RegExp;

/*
 * TemplateSyntaxPHP
 *
 * The class that processes the captured php syntax, currently this class only supports a few syntaxes such as break, catch, continue, do-while, if-elseif-else, and many more, but apart from that it is not possible to process the various syntaxes  quite difficult to process, such as matches and switches are not currently supported and you as a developer also have to be careful when writing syntax that supports multiple closing catches such as try-catch-finally, do-while, and also if-elseif-else if you write it wrong  you will get syntax errors also some errors may be caught but some may not be possible unless you want to stay over night to fix them.
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
			"break" => [
				"format" => False,
				"inline" => [
					"unpaired" => "<?php break; ?>"
				],
				"paired" => False,
				"unpaired" => True,
				"nextmatch" => False,
				"condition" => [
					"allow" => False,
					"require" => False
				]
			],
			"catch" => [
				"format" => [
					"paired" => "<?php \} catch( {condition} ) \{ ?>\n{content}\n{indent}<?php \} ?>",
					"unpaired" => "<?php \} catch( {condition} ) \{ ?>{content}<?php \} ?>",
					"nextmatch" => "<?php \} catch( {condition} ) \{ ?>\n{content}\n{indent}{nextmatch}"
				],
				"inline" => [
					"unpaired" => "<?php \} catch( {condition} ) \{ ?>{content}<?php \} ?>",
					"nextmatch" => "<?php \} catch( {condition} ) \{ ?>{content}{nextmatch}"
				],
				"paired" => True,
				"unpaired" => True,
				"nextmatch" => [
					"token" => [
						"catch",
						"finally"
					],
					"require" => False
				],
				"condition" => [
					"allow" => True,
					"require" => True
				]
			],
			"case",
			"continue" => [
				"format" => False,
				"inline" => [
					"unpaired" => "<?php continue; ?>"
				],
				"paired" => False,
				"unpaired" => True,
				"nextmatch" => False,
				"condition" => [
					"allow" => False,
					"require" => False
				]
			],
			"default",
			"do" => [
				"format" => [
					"nextmatch" => "<?php do \{ ?>\n{content}\n{indent}{nextmatch}"
				],
				"inline" => False,
				"paired" => True,
				"unpaired" => False,
				"nextmatch" => [
					"token" => [
						"while"
					],
					"require" => True
				],
				"condition" => [
					"allow" => False,
					"require" => False
				]
			],
			"elif" => [
				"format" => [
					"paired" => "<?php \} else if( {condition} ) \{ ?>\n{content}\n{indent}<?php \} ?>",
					"unpaired" => "<?php \} else if( {condition} ) \{ ?>{content}<?php \} ?>",
					"nextmatch" => "<?php \} else if( {condition} ) \{ ?>\n{content}\n{indent}{nextmatch}"
				],
				"inline" => [
					"unpaired" => "<?php \} else if( {condition} ) \{ ?>{content}<?php \} ?>",
					"nextmatch" => "<?php \} else if( {condition} ) \{ ?>{content}{nextmatch}"
				],
				"paired" => True,
				"unpaired" => True,
				"nextmatch" => [
					"token" => [
						"elif",
						"else",
						"elseif"
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
					"paired" => "<?php \} else \{ ?>\n{content}\n{indent}<?php } ?>",
					"unpaired" => "<?php \} else \{ ?>{content}<?php } ?>"
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
			"elseif" => [
				"format" => [
					"paired" => "<?php \} else if( {condition} ) \{ ?>\n{content}\n{indent}<?php \} ?>",
					"unpaired" => "<?php \} else if( {condition} ) \{ ?>{content}<?php \} ?>",
					"nextmatch" => "<?php \} else if( {condition} ) \{ ?>\n{content}\n{indent}{nextmatch}"
				],
				"inline" => [
					"unpaired" => "<?php \} else if( {condition} ) \{ ?>{content}<?php \} ?>",
					"nextmatch" => "<?php \} else if( {condition} ) \{ ?>{content}{nextmatch}"
				],
				"paired" => True,
				"unpaired" => True,
				"nextmatch" => [
					"token" => [
						"elif",
						"else",
						"elseif"
					],
					"require" => False
				],
				"condition" => [
					"allow" => True,
					"require" => True
				]
			],
			"empty" => [
				"format" => [
					"paired" => "<?php if( empty( {condition} ) ) \{ ?>\n{content}\n{indent}<?php \} ?>",
					"unpaired" => "<?php if( empty( {condition} ) ) \{ ?>{content}<?php \} ?>",
					"nextmatch" => "<?php if( empty( {condition} ) ) \{ ?>\n{content}\n{indent}{nextmatch}"
				],
				"inline" => [
					"unpaired" => "<?php if( empty( {condition} ) ) \{ ?>{content}<?php \} ?>",
					"nextmatch" => "<?php if( empty( {condition} ) ) \{ ?>{content}{nextmatch}"
				],
				"paired" => True,
				"unpaired" => True,
				"nextmatch" => [
					"token" => [
						"elif",
						"else",
						"elseif"
					],
					"require" => False
				],
				"condition" => [
					"allow" => True,
					"require" => True
				]
			],
			"finally" => [
				"format" =>  [
					"paired" => "<?php \} finally \{ ?>\n{content}\n{indent}<?php } ?>",
					"unpaired" => "<?php \} finally \{ ?>{content}<?php } ?>"
				],
				"inline" => [
					"unpaired" => "<?php \} finally \{ ?>{content}<?php } ?>"
				],
				"paired" => True,
				"unpaired" => True,
				"nextmatch" => False,
				"condition" => [
					"allow" => False,
					"require" => False
				]
			],
			"for" => [
				"format" => [
					"paired" => "<?php for( {condition} ) \{ ?>\n{content}\n{indent}<?php \} ?>",
					"unpaired" => "<?php for( {condition} ) \{ ?>{content}<?php \} ?>"
				],
				"inline" => [
					"unpaired" => "<?php for( {condition} ) \{ ?>{content}<?php \} ?>"
				],
				"paired" => True,
				"unpaired" => True,
				"nextmatch" => False,
				"condition" => [
					"allow" => True,
					"require" => True
				]
			],
			"foreach" => [
				"format" => [
					"paired" => "<?php foreach( {condition} ) \{ ?>\n{content}\n{indent}<?php \} ?>",
					"unpaired" => "<?php foreach( {condition} ) \{ ?>{content}<?php \} ?>"
				],
				"inline" => [
					"unpaired" => "<?php foreach( {condition} ) \{ ?>{content}<?php \} ?>"
				],
				"paired" => True,
				"unpaired" => True,
				"nextmatch" => False,
				"condition" => [
					"allow" => True,
					"require" => True
				]
			],
			"if" => [
				"format" => [
					"paired" => "<?php if( {condition} ) \{ ?>\n{content}\n{indent}<?php \} ?>",
					"unpaired" => "<?php if( {condition} ) \{ ?>{content}<?php \} ?>",
					"nextmatch" => "<?php if( {condition} ) \{ ?>\n{content}\n{indent}{nextmatch}"
				],
				"inline" => [
					"unpaired" => "<?php if( {condition} ) \{ ?>{content}<?php \} ?>",
					"nextmatch" => "<?php if( {condition} ) \{ ?>{content}{nextmatch}"
				],
				"paired" => True,
				"unpaired" => True,
				"nextmatch" => [
					"token" => [
						"elif",
						"else",
						"elseif"
					],
					"require" => False
				],
				"condition" => [
					"allow" => True,
					"require" => True
				]
			],
			"isset" => [
				"format" => [
					"paired" => "<?php if( isset( {condition} ) ) \{ ?>\n{content}\n{indent}<?php \} ?>",
					"unpaired" => "<?php if( isset( {condition} ) ) \{ ?>{content}<?php \} ?>",
					"nextmatch" => "<?php if( isset( {condition} ) ) \{ ?>\n{content}\n{indent}{nextmatch}"
				],
				"inline" => [
					"unpaired" => "<?php if( isset( {condition} ) ) \{ ?>{content}<?php \} ?>",
					"nextmatch" => "<?php if( isset( {condition} ) ) \{ ?>{content}{nextmatch}"
				],
				"paired" => True,
				"unpaired" => True,
				"nextmatch" => [
					"token" => [
						"elif",
						"else",
						"elseif"
					],
					"require" => False
				],
				"condition" => [
					"allow" => True,
					"require" => True
				]
			],
			"match",
			"puts" => [
				"format" => False,
				"inline" => [
					"unpaired" => "<?php puts( {condition} ); ?>"
				],
				"paired" => False,
				"unpaired" => True,
				"nextmatch" => False,
				"condition" => [
					"allow" => True,
					"require" => True
				]
			],
			"switch",
			"throw" => [
				"format" => False,
				"inline" => [
					"unpaired" => "<?php throw {condition}; ?>"
				],
				"paired" => False,
				"unpaired" => True,
				"nextmatch" => False,
				"condition" => [
					"allow" => True,
					"require" => True
				]
			],
			"try" => [
				"format" => [
					"nextmatch" => "<?php try \{ ?>\n{content}\n{indent}{nextmatch}"
				],
				"inline" => False,
				"paired" => True,
				"unpaired" => False,
				"nextmatch" => [
					"token" => [
						"catch",
						"finally"
					],
					"require" => True
				],
				"condition" => [
					"allow" => False,
					"require" => False
				]
			],
			"use" => [
				"format" => False,
				"inline" => [
					"unpaired" => "<?php use {condition}; ?>"
				],
				"paired" => False,
				"unpaired" => True,
				"nextmatch" => False,
				"condition" => [
					"allow" => True,
					"require" => True
				]
			],
			"while" => [
				"format" => [
					"paired" => "<?php while( {condition} ) \{ ?>\n{content}\n{indent}<?php \} ?>",
					"unpaired" => "<?php \} while( {condition} ); ?>"
				],
				"inline" => [
					"unpaired" => "<?php \} while( {condition} ); ?>"
				],
				"paired" => True,
				"unpaired" => True,
				"nextmatch" => False,
				"condition" => [
					"allow" => True,
					"require" => True
				]
			]
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
		
		$closing = $this->closing( $syntax );
		
		if( $closing === False )
		{
			if( $this->isMultimatchRequired( $syntax->token ) )
			{
				throw new TemplateSyntaxError( f( "Syntax \"{}\" must end with closing syntax \"{}\"", $syntax->token, implode( "|", $this->token[$syntax->tokenLower]['nextmatch']['token'] ) ), $syntax->view, $syntax->line, 0 );
			}
		}
		
		if( $syntax->multiline )
		{
			if( $syntax->semicolon )
			{
				$format = $this->token[$syntax->token]['format']['unpaired'] ?? $this->token[$syntax->token]['inline']['unpaired'];
			}
			else {
				
				$format = $this->token[$syntax->token]['format']['paired'] ?? "";
				
				if( $closing !== False )
				{
					$format = $this->token[$syntax->token]['format']['nextmatch'];
					
					if( $syntax->children === Null )
					{
						/*
						echo "\n\n";
						echo "Has Closing, No Content >> ";
						echo $syntax->token;
						echo "\n\n";
						*/
					}
				}
				else {
					/*
					echo "\n\n";
					echo "No Closing, No Content >> ";
					echo $syntax->token;
					echo "\n\n";
					*/
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
					throw new TemplateSyntaxError( f( "Syntax \"{}\" does not support short typing", $syntax->token ), $syntax->view, $syntax->line, 0 );
				}
			}
			else {
				if( $this->isMultipair( $syntax->token ) === False &&
					$this->isPaired( $syntax->token ) === False )
				{
					throw new TemplateSyntaxError( f( "Syntax \"{}\" does not support inner content", $syntax->token ), $syntax->view, $syntax->line, 0 );
				}
			}
		}
		else {
			if( $this->isShortable( $syntax->token ) === False )
			{
				throw new TemplateSyntaxError( f( "Syntax \"{}\" does not support short typing", $syntax->token ), $syntax->view, $syntax->line, 0 );
			}
		}
		
		if( $syntax->value )
		{
			if( $this->isUncondition( $syntax->token ) )
			{
				throw new TemplateSyntaxError( f( "The syntax \"{}\" does not support any conditions", $syntax->token ), $syntax->view, $syntax->line, 0 );
			}
		}
		else {
			if( $this->isCondition( $syntax->token ) )
			{
				throw new TemplateSyntaxError( f( "The syntax \"{}\" requires a conditional", $syntax->token ), $syntax->view, $syntax->line, 0 );
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
			if( $syntax->closing->valid || $syntax->closing->line === Null ) return( False );
			
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
			"condition" => $this->normalize( $syntax->value ?? "", [ "\\:", "\\;" ] ),
			"content" => $this->context->parse( $syntax->children ?? "" ),
			"indent" => $syntax->indent->value
		]));
	}
	
}

?>