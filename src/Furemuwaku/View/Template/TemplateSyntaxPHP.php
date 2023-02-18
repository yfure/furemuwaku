<?php

namespace Yume\Fure\View\Template;

use ReflectionMethod;

use Yume\Fure\Config;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\Reflect;
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
	 * Instance of class ReflectionMethod.
	 *
	 * @access Private Readonly
	 *
	 * @values ReflectionMethod
	 */
	private Readonly ReflectionMethod $indent;
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntax
	 *
	 */
	protected Array | String $token = [
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
				"paired" => "<?php \} catch( {condition} ) \{ ?>\x0a{content}\x0a{indent}<?php \} ?>",
				"unpaired" => "<?php \} catch( {condition} ) \{ ?>{content}<?php \} ?>",
				"nextmatch" => "<?php \} catch( {condition} ) \{ ?>\x0a{content}\x0a{nextmatch}"
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
				"nextmatch" => "<?php do \{ ?>\x0a{content}\x0a{nextmatch}"
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
				"paired" => "<?php \} else if( {condition} ) \{ ?>\x0a{content}\x0a{indent}<?php \} ?>",
				"unpaired" => "<?php \} else if( {condition} ) \{ ?>{content}<?php \} ?>",
				"nextmatch" => "<?php \} else if( {condition} ) \{ ?>\x0a{content}\x0a{nextmatch}"
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
				"paired" => "<?php \} else \{ ?>\x0a{content}\x0a{indent}<?php } ?>",
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
				"paired" => "<?php \} else if( {condition} ) \{ ?>\x0a{content}\x0a{indent}<?php \} ?>",
				"unpaired" => "<?php \} else if( {condition} ) \{ ?>{content}<?php \} ?>",
				"nextmatch" => "<?php \} else if( {condition} ) \{ ?>\x0a{content}\x0a{nextmatch}"
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
				"paired" => "<?php if( empty( {condition} ) ) \{ ?>\x0a{content}\x0a{indent}<?php \} ?>",
				"unpaired" => "<?php if( empty( {condition} ) ) \{ ?>{content}<?php \} ?>",
				"nextmatch" => "<?php if( empty( {condition} ) ) \{ ?>\x0a{content}\x0a{nextmatch}"
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
				"paired" => "<?php \} finally \{ ?>\x0a{content}\x0a{indent}<?php } ?>",
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
				"paired" => "<?php for( {condition} ) \{ ?>\x0a{content}\x0a{indent}<?php \} ?>",
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
				"paired" => "<?php foreach( {condition} ) \{ ?>\x0a{content}\x0a{indent}<?php \} ?>",
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
				"paired" => "<?php if( {condition} ) \{ ?>\x0a{content}\x0a{indent}<?php \} ?>",
				"unpaired" => "<?php if( {condition} ) \{ ?>{content}<?php \} ?>",
				"nextmatch" => "<?php if( {condition} ) \{ ?>\x0a{content}\x0a{nextmatch}"
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
				"paired" => "<?php if( isset( {condition} ) ) \{ ?>\x0a{content}\x0a{indent}<?php \} ?>",
				"unpaired" => "<?php if( isset( {condition} ) ) \{ ?>{content}<?php \} ?>",
				"nextmatch" => "<?php if( isset( {condition} ) ) \{ ?>\x0a{content}\x0a{nextmatch}"
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
				"nextmatch" => "<?php try \{ ?>\x0a{content}\x0a{nextmatch}"
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
				"paired" => "<?php while( {condition} ) \{ ?>\x0a{content}\x0a{indent}<?php \} ?>",
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
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntax
	 *
	 */
	public function __construct( private Readonly Template $engine, Config\Config $configs )
	{
		// Create reflection for `matchIndent` method.
		$this->indent = new ReflectionMethod( $engine, "matchIndented" );
		
		// Call parent constructor.
		parent::__construct( $engine );
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
	 * @inherit Yume\Fure\View\Template\Template::matchIndented
	 *
	 */
	private function matchIndented( Data\DataInterface $syntax ): Void
	{
		Reflect\ReflectMethod::invoke( $this->engine, "matchIndented", [$syntax], $this->indent );
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	public function process( Data\DataInterface $syntax ): Array | String
	{
		// Syntax assertion.
		$this->assert( $syntax );
		
		$closing = $this->closing( $syntax );
		
		if( $closing === False )
		{
			if( $this->isMultimatchRequired( $syntax->token ) )
			{
				throw new TemplateSyntaxError( f( "Syntax \"{}\" must end with closing syntax \"{}\"", $syntax->token, implode( "|", $this->token[$syntax->tokenLower]['nextmatch']['token'] ) ), $syntax->view->name, $syntax->line, 0 );
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
						echo "\x0a\x0a";
						echo "Has Closing, No Content >> ";
						echo $syntax->token;
						echo "\n";
						echo $syntax->begin;
						echo "\x0a\x0a";
					}
				}
				else {
					if( $syntax->children === Null )
					{
						echo "\n\n";
						echo "No Closing, No Content >> ";
						echo $syntax->token;
						echo "\n";
						echo $syntax->begin;
						echo "\n\n";
					}
				}
			}
		}
		else {
			
			$syntax->children = $syntax->outline;
			
			$format = $this->token[$syntax->token]['inline']['unpaired'];
			
			if( $closing !== False )
			{
				$format = $this->token[$syntax->token]['inline']['nextmatch'];
			}
		}
		
		$result = $this->format( $format, $syntax );
		
		return([
			"raw" => $syntax->raw,
			"result" => $result
		]);
	}
	
	/*
	 * Syntax Assertion.
	 *
	 * @access Private
	 *
	 * @params Yume\Fure\View\Template\Data\DataInterface $syntax
	 *
	 * @return Void
	 */
	private function assert( Data\DataInterface $syntax ): Void
	{
		if( $syntax->multiline )
		{
			if( $syntax->semicolon )
			{
				if( $this->isMultipair( $syntax->token ) === False &&
					$this->isUnpaired( $syntax->token ) === False )
				{
					throw new TemplateSyntaxError( f( "Syntax \"{}\" does not support short typing", $syntax->token ), $syntax->view->name, $syntax->line, 0 );
				}
			}
			else {
				if( $this->isMultipair( $syntax->token ) === False &&
					$this->isPaired( $syntax->token ) === False )
				{
					throw new TemplateSyntaxError( f( "Syntax \"{}\" does not support inner content", $syntax->token ), $syntax->view->name, $syntax->line, 0 );
				}
			}
		}
		else {
			if( $this->isShortable( $syntax->token ) === False )
			{
				throw new TemplateSyntaxError( f( "Syntax \"{}\" does not support short typing", $syntax->token ), $syntax->view->name, $syntax->line, 0 );
			}
		}
		if( $syntax->value )
		{
			if( $this->isUncondition( $syntax->token ) )
			{
				throw new TemplateSyntaxError( f( "The syntax \"{}\" does not support any conditions", $syntax->token ), $syntax->view->name, $syntax->line, 0 );
			}
		}
		else {
			if( $this->isCondition( $syntax->token ) )
			{
				throw new TemplateSyntaxError( f( "The syntax \"{}\" requires a conditional", $syntax->token ), $syntax->view->name, $syntax->line, 0 );
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
	private function closing( Data\DataInterface $syntax ): Array | False | String
	{
		$syntax->closing->nextmatch = [
			"syntax" => Null,
			"captured" => Null
		];
		if( $this->isMultimatch( $syntax->token ) )
		{
			// Check if syntax has no contents in closing.
			if( valueIsEmpty( $syntax->closing->syntax ) ) return( False );
			
			// If closing syntax is valid.
			if( $syntax->closing->valid || $syntax->closing->line === Null ) return( False );
			
			if( $syntax->multiline )
			{
				// Regular Expression for capture next match syntax.
				$regexp = f( "/^(?<matched>(?<multiline>(?<indent>\s\{{},\})(?:\@)(?<inline>(?<token>(?:{})\b)(?:[\s\t]*)(?<value>.*?))(?<!\\\)(?<symbol>(?<colon>\:)|(?<semicolon>\;))(?<outline>([^\x0a]*))))/ms", $syntax->indent->length, implode( "|", $this->token[$syntax->tokenLower]['nextmatch']['token'] ) );
				
				// Get splited line.
				$content = $this->getSLine( $syntax->view->name, $syntax->closing->line );
				
				// Split content with newline.
				$contentSplit = explode( "\x0a", $content );
				$contentLength = count( $contentSplit );
				
				// If closing syntax matched.
				if( preg_match( $regexp, $content, $match ) )
				{
					$capture = $this->handle( ...[
						new Data\Data([
							"token" => Template::TOKEN_MULTILINE,
							"match" => RegExp\RegExp::clear( $match )
						]),
						$syntax->view->name,
						$syntax->view->split->__toArray(),
						$syntax->view->length
					]);
					
					$syntax->closing->nextmatch->syntax = $capture->result;
					$syntax->closing->nextmatch->captured = $capture->raw;
					
					if( $capture->closing->nextmatch->syntax )
					{
						$capture->closing->syntax = $capture->closing->nextmatch->captured;
						$capture->closing->valid = True;
						$capture->raw = $this->reBuildSyntaxCapture( $capture );
						$syntax->closing->syntax = $capture->raw;
						$syntax->closing->valid = True;
						$syntax->raw = $this->reBuildSyntaxCapture( $syntax );
					}
					else {
						$syntax->closing->syntax = $capture->raw;
						$syntax->closing->valid = True;
						$syntax->raw = $this->reBuildSyntaxCapture( $syntax );
					}
					return([
						"raw" => $capture->raw,
						"result" => $capture->result
					]);
				}
			}
			else {
				echo "Unhandle Multimatch Shortline";
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
	private function format( String $format, Data\DataInterface $syntax ): String
	{
		// Return formated syntax.
		return( f( $format, ...[
			"nextmatch" => $syntax->closing->nextmatch->syntax ?? "",
			"condition" => $this->normalize( $syntax->value ?? "", [ "\\:", "\\;" ] ),
			"content" => $syntax->children ?? "",
			"indent" => $syntax->indent->value
		]));
	}
	
}

?>