<?php

namespace Yume\Fure\View\Template;

use Yume\Fure\Error;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\File;
use Yume\Fure\Support\Reflect;
use Yume\Fure\Util;
use Yume\Fure\Util\RegExp;

/*
 * Template
 *
 * All praises be to Allah.
 *
 * @package Yume\Fure\View\Template
 */
class Template
{
	
	/*
	 * The constants for tokenization syntax that are captured are comments.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const TOKEN_COMMENT = 56788;
	
	/*
	 * The constants for tokenization syntax that are captured are multiline syntax.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const TOKEN_MULTILINE = 65462;
	
	/*
	 * The constants for tokenization syntax that are captured are output.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const TOKEN_OUTPUT = 75227;
	
	/*
	 * The constants for tokenization syntax that are captured are shortline syntax.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const TOKEN_SHORTLINE = 86722;
	
	/*
	 * Allow or deny deleting comments with a hash symbol.
	 *
	 * @access Public Readonly
	 *
	 * @values Bool
	 */
	public Readonly Bool $commentRemove;
	
	/*
	 * Allow comments to be overwritten with html comment syntax.
	 *
	 * @access Public Readonly
	 *
	 * @values Bool
	 */
	public Readonly Bool $commentDisplay;
	
	/*
	 * Minimum indent length.
	 *
	 * @access Public Readonly
	 *
	 * @values Int
	 */
	public Readonly Int $indentLength;
	
	/*
	 * Indentation value.
	 *
	 * @access Public Readonly
	 *
	 * @values String
	 */
	public Readonly String $indentValue;
	
	/*
	 * Match info.
	 *
	 * This includes the pattern type, row, match result,
	 * as well as information about the view such as name,
	 * raw content, split content, split content length.
	 *
	 * Note that the comment syntax and output do not include
	 * the lines where they were captured.
	 *
	 * @access Protected Readonly
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	protected Readonly Data\DataInterface $match;
	
	/*
	 * List of regular expression patterns to capture syntax.
	 *
	 * @access Protected Readonly
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	protected Readonly Data\DataInterface $patterns;
	
	/*
	 * Syntax processing class.
	 *
	 * @access Protected
	 *
	 * @values Array
	 */
	protected Array $syntax;
	
	/*
	 * Construct method of class Template.
	 *
	 * @access Public Instance
	 *
	 * @return Void
	 */
	public function __construct()
	{
		// Get template configuration.
		$configs = config( "view.template" );
		
		// Set syntax processing.
		$this->syntax = [
			"default" => [
				//TemplateSyntaxComponent::class,
				TemplateSyntaxHTML::class,
				TemplateSyntaxPHP::class
			],
			"custom" => $configs->syntax->keys()
		];
		
		$this->commentRemove = $configs->comment->remove ?? True;
		$this->commentDisplay = $configs->comment->display ?? False;
		$this->indentLength = $configs->indent->length ?? 4;
		$this->indentValue = $config->indent->value ?? "\x20";
		
		// Set patterns.
		$this->patterns = new Data\Data([
			
			/*
			 * The regular expression pattern for capturing comments,
			 * syntax beginning with the hash symbol supports both
			 * indentation and not.
			 *
			 */
			"comment" => "/(?<matched>(?<comment>^(?<indent>\s\s\s\s+)*(?<taggar>\\#(?:(?<html>\<(?<text>.*?)(?<!\\\)\>)|(?:\s*)(?<text>[^\x0a]*))))|(?<!\\\)(?<comment>(?<taggar>\\#(?:(?<html>\<(?<text>.*?)(?<!\\\)\>)|(?<text>[^\x0a]*)))|(?<html>\<\+\+(?<text>.*?)\+\+\>)))/msJ",
			
			/*
			 * Regular expression patterns to capture syntax based on
			 * indentation, not only PHP but HTML content can also be
			 * written like code in Python.
			 *
			 * Note that this will also catch shorthanded syntax.
			 *
			 */
			"multiline" => "/^(?<matched>(?<multiline>(?<indent>\s\s\s\s+)*(?:\@)(?<inline>(?<token>[a-zA-Z0-9]*)(?:[\s\t]*)(?<value>.*?))(?<!\\\)(?<symbol>(?<colon>\:)|(?<semicolon>\;))(?<outline>([^\x0a]*))))$/ms",
			
			/*
			 * This is similar to multiline except that it doesn't use
			 * indentation as a rule, it catches the syntax everywhere.
			 *
			 */
			"shortline" => implode( "", [
				"/(?<matched>",
					"(?<!\\\)",
					"(?:\@)",
					"(?<shortline>",
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
								"(?<children>[^?<!\\\\;]*)",
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
						//"(?<outline>[^\n]*)",
					")",
				")/J"
			]),
			
			/*
			 * The regular expression for capture output syntax.
			 *
			 */
			"output" => "/(?<matched>(?<output>\{\{(?:(?<value>[^}\\\]+|\\.})*)\}\}))/"
		]);
		
		// Match matching.
		$match = [
			"line" => 0,
			"type" => 0,
			"view" => [
				"raw" => Null,
				"name" => Null,
				"split" => Null,
				"length" => 0
			]
		];
		
		$this->match = new Data\Data([
			"current" => $match,
			"previous" => $match,
			"iteration" => 0
		]);
	}
	
	/*
	 * Called class Assertion.
	 *
	 * @access Static Private
	 *
	 * @params String $class
	 *
	 * @return Void
	 */
	static private function assertClass( String $class ): Void
	{
		if( $class !== static::class )
		{
			if( in_array( $class, $this->syntax->default ) ||
				in_array( $class, $this->syntax->default ) )
			{
				return;
			}
			//throw new 
		}
	}
	
	/*
	 * Cleaning sensitive symbols.
	 *
	 * @access Public
	 *
	 * @params String $content
	 * @params Array $except
	 *
	 * @return String
	 */
	public function clean( String $content, Array $except = [] ): String
	{
		// Default symbols will replace.
		$chars = [ "\\#", "\\:", "\\;", "\\\"", "\\'", "\\=", "\\@", "\\[", "\\]", "\\{", "\\}", "\\<", "\\>" ];
		
		// Mapping symbols.
		foreach( $except As $char )
		{
			// If symbols exists.
			if( False !== $idx = array_search( $char, $chars ) )
			{
				unset( $chars[$idx] );
			}
		}
		return( preg_replace( f( "/\\\({implode(+)})/", [ "|", $chars ] ), "$1", $content ) );
	}
	
	/*
	 * Clear all space in first line and end line.
	 *
	 * @access Public
	 *
	 * @params String $value
	 *
	 * @return String
	 */
	public function clear( String $value ): String
	{
		return( preg_replace( "/^\s+|\s+$/", "", $value ) );
	}
	
	/*
	 * Return current matched line.
	 *
	 * @access Public
	 *
	 * @return 
	 */
	public function getCurrentLine(): Int
	{
		return( $this )->match->current->line;
	}
	
	/*
	 * Return current match type.
	 *
	 * @access Public
	 *
	 * @return 
	 */
	public function getCurrentType(): Int
	{
		return( $this )->match->current->type;
	}
	
	/*
	 * Return current view.
	 *
	 * @access Public
	 *
	 * @return 
	 */
	public function getCurrentView(): Data\DataInterface
	{
		return( $this )->match->current->view;
	}
	
	/*
	 * Return current view raw.
	 *
	 * @access Public
	 *
	 * @return 
	 */
	public function getCurrentViewRaw(): ? String
	{
		return( $this )->match->current->view->raw;
	}
	
	/*
	 * Return current view name.
	 *
	 * @access Public
	 *
	 * @return 
	 */
	public function getCurrentViewName(): ? String
	{
		return( $this )->match->current->view->name;
	}
	
	/*
	 * Return current view split.
	 *
	 * @access Public
	 *
	 * @return 
	 */
	public function getCurrentViewSplit(): ? Data\DataInterface
	{
		return( $this )->match->current->view->split;
	}
	
	/*
	 * Return current view split length.
	 *
	 * @access Public
	 *
	 * @return 
	 */
	public function getCurrentViewLength(): Int
	{
		return( $this )->match->current->view->length;
	}
	
	/*
	 * Return current match iteration.
	 *
	 * @access Public
	 *
	 * @return 
	 */
	public function getIteration(): Int
	{
		return( $this )->match->iteration;
	}
	
	/*
	 * Get line by captured syntax.
	 *
	 * @access Public
	 *
	 * @params String $view
	 * @params String $content
	 *
	 * @return False|Int
	 */
	public function getLine( String $view, String $content ): False | Int
	{
		$template = match( True )
		{
			$this->match->current->view->name === $view => $this->match->current->view->split->__toArray(),
			$this->match->previous->view->name === $view => $this->match->previous->view->split->__toArray(),
			
			default => File\File::readline( $view )
		};
		
		// Explode raw content captured.
		$split = explode( "\x0a", $content );
		
		// Get array index.
		$search = array_search( end( $split ), $template );
		
		// Check if index is exists.
		if( $search !== False )
		{
			return( $search +1 );
		}
		return( False );
	}
	
	/*
	 * Get line by position.
	 *
	 * @access Public
	 *
	 * @params String $view
	 * @params String $content
	 *
	 * @return False|Int
	 */
	public function getPLine( String $view, String $content ): False | Int
	{
		$template = match( True )
		{
			/*
			$this->match->current->view->name === $view => $this->match->current->view->split->__toArray(),
			$this->match->previous->view->name === $view => $this->match->previous->view->split->__toArray(),
			*/
			default => File\File::readline( $view )
		};
		
		// Explode raw content captured.
		$split = explode( "\x0a", $content );
		$split = $split[0];
		
		foreach( $template As $i => $val )
		{
			if( strpos( $val, $split ) )
			{
				return( $i +1 );
			}
		}
		return( False );
	}
	
	/*
	 * Return previous matched line.
	 *
	 * @access Public
	 *
	 * @return 
	 */
	public function getPreviousLine(): Int
	{
		return( $this )->match->previous->line;
	}
	
	/*
	 * Return previous match type.
	 *
	 * @access Public
	 *
	 * @return 
	 */
	public function getPreviousType(): Int
	{
		return( $this )->match->previous->type;
	}
	
	/*
	 * Return previous view.
	 *
	 * @access Public
	 *
	 * @return 
	 */
	public function getPreviousView(): Data\DataInterface
	{
		return( $this )->match->previous->view;
	}
	
	/*
	 * Return previous view raw.
	 *
	 * @access Public
	 *
	 * @return 
	 */
	public function getPreviousViewRaw(): ? String
	{
		return( $this )->match->previous->view->raw;
	}
	
	/*
	 * Return previous view name.
	 *
	 * @access Public
	 *
	 * @return 
	 */
	public function getPreviousViewName(): ? String
	{
		return( $this )->match->previous->view->name;
	}
	
	/*
	 * Return previous view split.
	 *
	 * @access Public
	 *
	 * @return 
	 */
	public function getPreviousViewSplit(): ? Data\DataInterface
	{
		return( $this )->match->previous->view->split;
	}
	
	/*
	 * Return previous view split length.
	 *
	 * @access Public
	 *
	 * @return 
	 */
	public function getPreviousViewLength(): Int
	{
		return( $this )->match->previous->view->length;
	}
	
	/*
	 * Get splited template by line.
	 *
	 * @access Public
	 *
	 * @params String $view
	 * @params Int $line
	 *
	 * @return String
	 *
	 * @throws Yume\Fure\Error\ValueError
	 */
	public function getSLine( String $view, Int $line ): String
	{
		// If line less than one.
		if( $line < 1 ) throw new Error\ValueError( f( "Value of argument \$line for {} must be more than one", __METHOD__ ) );
		
		$stack = [];
		$split = match( True )
		{
			$this->match->current->view->name === $view => $this->match->current->view->split,
			$this->match->previous->view->name === $view => $this->match->previous->view->split,
			
			default => File\File::readline( $view )
		};
		
		foreach( $split As $i => $val )
		{
			if( $i +1 >= $line )
			{
				$stack[] = $val;
			}
		}
		return( implode( "\x0a", $stack ) );
	}
	
	/*
	 * Handle matched syntax.
	 *
	 * @access Public
	 *
	 * @params Yume\Fure\Support\Data\DataInterface $match
	 * @params String $view
	 * @params Array $split
	 * @params Int $length
	 *
	 * @return Yume\Fure\Support\Data\DataInterface
	 *
	 * @throws Yume\Fure\View\Template\TemplateSyntaxError
	 */
	public function handle( Data\DataInterface $match, String $view, Array $split, Int $length ): Data\DataInterface
	{
		// Verify Called class.
		$this->assertClass( get_called_class() );
		
		$self = $this;
		$indent = $this->resolveIndent( $match->match->indent ?? "" );
		$syntax = new Data\Data([
			"view" => [
				"name" => $view,
				"split" => $split,
				"length" => $length
			],
			"raw" => $match->match->matched,
			"type" => $match->token,
			"match" => $match->match,
			"indent" => [
				"value" => $indent,
				"length" => strlen( $indent )
			]
		]);
		
		// Check if syntax indentation is invalid.
		if( Util\Number::isOdd( $syntax->indent->length ) )
		{
			throw new TemplateIndentationError( "*", $syntax->view->name, $this->getLine( $syntax->view->name, $syntax->raw ) );
		}
		
		// Get result processed syntax.
		$syntax->result = $result = match( $match->token )
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
			 * @throws Yume\Fure\View\Template\TemplateSyntaxError
			 */
			self::TOKEN_SHORTLINE,
			self::TOKEN_MULTILINE => call_user_func( static function() use( &$syntax, $self ): Array | String
			{
				// Check if syntax has token.
				if( valueIsNotEmpty( $syntax->match->token ) )
				{
					// Set token name.
					$syntax->token = $syntax->match->token;
					
					// Set normalized token name.
					$syntax->tokenLower = strtolower( $syntax->match->token );
					$syntax->tokenUpper = strtolower( $syntax->match->token );
					
					$syntax->value = $syntax->match->value ?? Null;
					$syntax->inline = $syntax->match->inline ?? Null;
					$syntax->outline = $syntax->match->outline ?? Null;
					$syntax->multiline = False;
					
					// If multiline syntax captured.
					if( $self->isMultilineToken( $syntax->type ) )
					{
						return( $self )->handleMultiline( $syntax );
					}
					else {
						return( $self )->handleShortline( $syntax );
					}
				}
				throw new TemplateSyntaxError( $syntax->raw, $syntax->view->name, $self->getLine( $syntax->view->name, $syntax->raw ) );
			})
		};
		
		// If return is array.
		if( is_array( $result ) )
		{
			$syntax->raw = $result['raw'] ?? $result[0];
			$syntax->result = $result['result'] ?? $result[1];
		}
		
		// Add indentation into result.
		$syntax->result = sprintf( "%s%s", $syntax->indent->value, $syntax->result );
		
		return( $syntax );
	}
	
	/*
	 * Handle closing syntax.
	 *
	 * @access Private
	 *
	 * @params Yume\Fure\Support\Data\DataInterface $syntax
	 *
	 * @return Array|String
	 *
	 * @throws Yume\Fure\View\Template\TemplateClosingError
	 * @throws Yume\Fure\View\Template\TemplateSyntaxError
	 */
	private function handleClosing( Data\DataInterface $syntax ): Data\DataInterface
	{
		// Default result.
		$syntax->closing = [
			"syntax" => $syntax->closing,
			"valid" => False
		];
		
		// Check if closing is valid closing.
		if( $syntax->closing->syntax && preg_match( f( "/(?<match>^[\s]\{{},}(?<!\\\)(?:@)(?<token>[a-zA-Z0-9]*)(?<closing>(?<!\\\)(?<dollar>\\$+)|(?<slash>\/+)(?<pass>[a-zA-Z0-9]*))*(?<outline>[^\x0a]*))/", $syntax->indent->length ), $syntax->closing->syntax, $valid ) )
		{
			// Closing line number.
			$syntax->closing->line = $this->getLine( $syntax->view->name, $syntax->closing->syntax );
			
			// Check if closing is empty.
			if( valueIsEmpty( $valid['closing'] ) )
			{
				// Check if token is equal "pass"
				if( strtolower( $valid['token'] ) === "pass" )
				{
					// Check if outline syntax is not empty && syntax is not comment type.
					if( valueIsNotEmpty( $valid['outline'] ) && $valid['outline'] !== ";" && $this->isComment( $valid['outline'] ) === False ) throw new TemplateSyntaxError( $valid['outline'], $syntax->view->name, $syntax->closing->line );
					
					// Push token.
					$syntax->closing->token = "pass";
					
					// Set as valid for closing.
					$syntax->closing->valid = True;
				}
			}
			
			// Check if closing is Dollar type.
			else if( isset( $valid['dollar'] ) )
			{
				// Check if token is not equals.
				if( strtolower( $valid['token'] ) !== $syntax->tokenLower ) throw new TemplateSyntaxError( $valid['token'], $syntax->view->name, $syntax->closing->line );
				
				// Check if dollar is more than one.
				if( strlen( $valid['dollar'] ) > 1 ) throw new TemplateSyntaxError( $valid['dollar'], $syntax->view->name, $syntax->closing->line );
				
				// Check if outline syntax is not empty && syntax is not comment type.
				if( valueIsNotEmpty( $valid['outline'] ) && $valid['outline'] !== ";" && $this->isComment( $valid['outline'] ) === False ) throw new TemplateSyntaxError( $valid['outline'], $syntax->view->name, $syntax->closing->line );
				
				// Push token.
				$syntax->closing->token = "$";
				
				// Set as valid for closing.
				$syntax->closing->valid = True;
			}
			
			// Check if closing is Slash type.
			else if( isset( $valid['slash'] ) )
			{
				// Check if token is not equals.
				if( strtolower( $valid['token'] ) !== $syntax->tokenLower ) throw new TemplateSyntaxError( $valid['token'], $syntax->view->name, $syntax->closing->line );
				
				// Check if pass is empty.
				if( valueIsEmpty( $valid['pass'] ) ) throw new TemplateSyntaxError( $valid['closing'], $syntax->view->name, $syntax->closing->line );
				
				// Check if slash is more than one.
				if( strlen( $valid['slash'] ) > 1 ) throw new TemplateSyntaxError( $valid['slash'], $syntax->view->name, $syntax->closing->line );
				
				// Check if pass is not pass.
				if( strtolower( $valid['pass'] ) !== "pass" ) throw new TemplateSyntaxError( $valid['pass'], $syntax->view->name, $syntax->closing->line );
				
				// Push token.
				$syntax->closing->token = $valid['closing'];
				
				// Set as valid for closing.
				$syntax->closing->valid = True;
			}
		}
		
		// Check if syntax is not multiple line.
		// But closing syntax is matched.
		if( $syntax->semicolon && $syntax->closing->valid ) throw new TemplateClosingError( $syntax->closing->syntax, $syntax->view->name, $syntax->closing->line ?? $this->getLine( $syntax->view->name, $syntax->closing->syntax ) );
		
		// Return closing.
		return( $syntax->closing );
	}
	
	/*
	 * Handle multiline syntax.
	 *
	 * @access Private
	 *
	 * @params Yume\Fure\Support\Data\DataInterface $syntax
	 *
	 * @return Array|String
	 *
	 * @throws Yume\Fure\View\Template\TemplateSyntaxError
	 */
	private function handleMultiline( Data\DataInterface $syntax ): Array | String
	{
		$syntax->multiline = True;
		$syntax->symbol = $syntax->match->symbol;
		$syntax->colon = isset( $syntax->match->colon );
		$syntax->semicolon = isset( $syntax->match->semicolon );
		
		// Set line number of syntax.
		$syntax->line = $this->getLine(
			
			// Views name.
			$syntax->view->name,
			
			// Re-Build begin syntax.
			$syntax->begin = $this->reBuildSyntaxBegin( $syntax ),
		);
		
		// Set current line syntax matched.
		$this->match->current->line = $syntax->line;
		
		// Check if syntax is single line.
		if( $syntax->outline && $syntax->semicolon )
		{
			// Check if syntax has outline value.
			if( valueIsNotEmpty( $syntax->outline ) && $this->isComment( $syntax->outline ) === False )
			{
				throw new TemplateSyntaxError( $syntax->outline, $syntax->view->name, $syntax->line );
			}
		}
		
		// Capture deep and closing content.
		$this->matchIndented( $syntax );
		
		// Build full captured syntax.
		$syntax->raw = $this->reBuildSyntaxCapture( $syntax );
		
		return( $this )->process( $syntax );
	}
	
	/*
	 * Handle shortline syntax.
	 *
	 * @access Private
	 *
	 * @params Yume\Fure\Support\Data\DataInterface $syntax
	 *
	 * @return Array|String
	 */
	private function handleShortline( Data\DataInterface $syntax ): Array | String
	{
		$syntax->symbol = ";";
		$syntax->colon = False;
		$syntax->semicolon = True;
		$syntax->outline = Null;
		$syntax->children = $syntax->match->children ?? Null;
		
		/*
		 * Here to find out on which line the syntax is
		 * captured we use get position line, this is because
		 * a regular expression will not capture the entire
		 * content like multi-line syntax.
		 *
		 */
		$syntax->line = $this->getPLine( $syntax->view->name, $syntax->raw );
		
		// Manipulate closing syntax.
		$syntax->closing = [
			"syntax" => $syntax->match->closing,
			"valid" => False,
			"line" => $syntax->line
		];
		
		return( $this )->process( $syntax );
	}
	
	/*
	 * Increment match iteration.
	 *
	 * @access Private
	 *
	 * @return Int
	 */
	private function increment():Void
	{
		$this->match->iteration++;
	}
	
	/*
	 * Check if content is commented.
	 *
	 * @access Public
	 *
	 * @params String $content
	 *
	 * @return Bool
	 */
	public function isComment( String $content ): Bool
	{
		return( preg_match( "/\s*((?<!\\\)\#[^\x0a]*|\<\!(--(.*?--\>)*)|(\+\+(.*?\+\+\>)*))/ms", $content ) );
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
	 *
	 * @throws Yume\Fure\Util\RegExp\RegExpError
	 */
	private function match( String $template ): False | Data\DataInterface
	{
		// Mapping available patterns.
		foreach( $this->patterns->__toArray() As $type => $pattern )
		{
			// If there are captured syntax.
			if( preg_match( $pattern, $template, $match ) )
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
			
			// Check if an error occurred.
			if( $errno = RegExp\RegExp::errno() )
			{
				throw new RegExp\RegExpError( RegExp\RegExp::error(), $errno );
			}
		}
		return( False );
	}
	
	/*
	 * Capture content indented.
	 *
	 * @access Private
	 *
	 * @params Yume\Fure\Support\Data\DataInterface $syntax
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\View\Template\TemplateIndentationError
	 */
	private function matchIndented( Data\DataInterface $syntax ): Void
	{
		$once = False;
		
		// Deep content stack.
		$syntax->children = [];
		
		// Closing content.
		$syntax->closing = Null;
		
		// Split syntax with newline.
		$msplit = explode( "\x0a", $syntax->begin );
		
		// Get splited syntax length.
		$msplitl = count( $msplit );
		
		// Get default indentation.
		$indent = $this->indentLength;
		
		// Check if syntax has indentation.
		if( $syntax->indent->length !== 0 )
		{
			// Get indentation length.
			$indent = $syntax->indent->length;
			
			// Check if indentation is is valid.
			if( Util\Number::isEven( $indent ) )
			{
				$indent += 4;
			}
			else {
				throw new TemplateIndentationError( "*", $syntax->view->name, $this->getLine( $syntax->view->name, $msplit[0] ) );
			}
		}
		
		// Looping splited template from current line.
		for( $i = $syntax->line + $msplitl; $i < $syntax->view->length +1; $i++ )
		{
			// Check if line is not exists.
			if( Null === $line = $syntax->view->split[( $i -1 )] )
			{
				var_dump( $syntax->view->split[( $i -1 )] );break;
			}
			
			// Check if content is not empty value.
			if( $line !== "" )
			{
				// Check if indentation is valid.
				if( preg_match( f( "/^[\s]\{{},\}/", $indent ), $line, $valid ) )
				{
					// Get indent length.
					$validIndentLength = strlen( $this->resolveIndent( $valid[0] ) );
					
					// Check if indentation level is invalid.
					if( Util\Number::isOdd( $validIndentLength ) )
					{
						throw new TemplateIndentationError( "*", $syntax->view->name, $i );
					}
					
					// Check if symbol is semicolon.
					if( $syntax->semicolon )
					{
						// Check if iteration is once.
						if( $once === True )
						{
							// Set closing syntax and break the loop.
							$syntax->closing = $line; break;
						}
						else {
							
							// Check if value is not empty.
							if( valueIsNotEmpty( $line ) && $this->isComment( $line ) === False )
							{
								throw new TemplateIndentationError( "*", $syntax->view->name, $i );
							}
						}
					}
					else {
						
						// Check if outline value is not empty
						// And outline is not comment syntax.
						if( valueIsNotEmpty( $syntax->outline ) && $this->isComment( $syntax->outline ) === False )
						{
							// Check if syntax has inner content.
							if( count( $syntax->children ) >= 1 ) throw new TemplateIndentationError( "*", $syntax->view->name, $i );
						}
					}
					$syntax->children[] = $line;
				}
				else {
					
					// Re-Match indentation.
					if( preg_match( "/(^[\s]*)([^\x0a]*)/", $line, $valid ) )
					{
						// Get indent length.
						$validIndentLength = strlen( $this->resolveIndent( $valid[1] ) );
						
						// Check if indentation level is invalid.
						if( Util\Number::isOdd( $validIndentLength ) )
						{
							throw new TemplateIndentationError( "*", $syntax->view->name, $i );
						}
						if( $validIndentLength < $indent || $valid[2] )
						{
							$syntax->closing = $line; break;
						}
						$content[] = $line;
					}
					else {
						
						// Set closing syntax and break the loop.
						$syntax->closing = $line; break;
					}
				}
				
				// Check if captured syntax use
				// colon symbol for closing outline.
				if( $syntax->colon )
				{
					// Check if outline value is not empty
					// And outline is not comment syntax.
					if( valueIsNotEmpty( $syntax->outline ) && $this->isComment( $syntax->outline ) === False )
					{
						// If syntax has inner content.
						if( count( $syntax->children ) >= 1 ) throw new TemplateIndentationError( "*", $syntax->view->name, $i );
					}
				}
			}
			else {
				
				// Empty content will be allowed.
				$syntax->children[] = "";
			}
			
			// Set once as True.
			$once = True;
		}
		
		// Handle matched closing syntax.
		$this->handleClosing( $syntax );
		
		// Check if content is not empty.
		if( count( $syntax->children ) !== 0 )
		{
			// Clear last line in content.
			$syntax->children = $this->removeLastLine( $syntax->children->__toArray(), $syntax->closing->valid );
			
			// Re-Check if content is not empty.
			if( count( $syntax->children ) !== 0 )
			{
				// Join newline into array contents.
				$syntax->children = $this->reBuildSyntaxChild( $syntax->children->__toArray() );
			}
			else {
				$syntax->children = Null;
			}
		}
		else {
			$syntax->children = Null;
		}
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
	
	/*
	 * Parse view contents.
	 *
	 * @access Public
	 *
	 * @params String $view
	 * @params String $template
	 *
	 * @return String
	 */
	public function parse( String $view, String $template ): String
	{
		// Normalize template.
		$template = 
		$templateRaw = 
		$templateLast = $this->normalize( $template );
		
		while( $match = $this->match( $template ) )
		{
			// Push match iteration.
			$this->increment();
			
			// Explode template with new line.
			$templateSplit = explode( "\x0a", $template );
			
			// Get template length.
			$templateLength = count( $templateSplit );
			
			$this->match->previous = $this->match->current;
			$this->match->current = [
				"line" => 0,
				"type" => $match->token,
				"match" => $match->match,
				"view" => [
					"raw" => $templateLast,
					"name" => $view,
					"split" => $templateSplit,
					"length" => $templateLength
				]
			];
			
			/*
			 * Handle captured syntax.
			 *
			 * Always use raw templates when dealing with captured syntax,
			 * in order to make it easier to search for lines.
			 *
			 */
			$syntax = $this->handle( $match, $view, $templateSplit, $templateLength );
			
			// If syntax has result.
			if( $syntax->result !== Null )
			{
				// Replacing captured syntax.
				$template = str_replace(
					$syntax->raw,
					$syntax->result,
					$template
				);
				
				/*
				 * Check if template is replaced.
				 *
				 * Note, if the captured syntax template is not
				 * overwritten with the processed syntax it can
				 * cause an endless loop.
				 *
				 */
				if( $template !== $templateLast )
				{
					// Copy template.
					$templateLast = $template;
					
					// Continue matching;
					continue;
				}
				echo "Unmatched!";
				exit;
			}
			break;
		}
		return( $template );
	}
	
	/*
	 * Processing captured syntax.
	 *
	 * @access Private
	 *
	 * @params Yume\Fure\Support\Data\DataInterface $syntax
	 *
	 * @return Array|String
	 *
	 * @throws Yume\Fure\Error\ClassImplementationError
	 * @throws Yume\Fure\View\Template\TemplateTokenError
	 */
	private function process( Data\DataInterface $syntax ): Array | String
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
					
					// Get configuration.
					$configs = config( "view.template" );
					$configs = $configs->configs[$class] ?? $configs->syntax[$class];
					
					// Create new Template Syntax instance.
					$class = $this->syntax[$group][$class] = $reflect->newInstance( $this, $configs );
					
					// Set name value with class name.
					$name = $class::class;
				}
				
				// Check if the syntax supports tokens.
				if( $class->isSupportedToken( $syntax->token ) )
				{
					// Check if syntax is skiped.
					if( $class->isSkip() ) break;
					
					// Return the result of the syntax that has been processed.
					return( $class->process( $syntax ) );
				}
			}
		}
		throw new TemplateTokenError( $syntax->token, $syntax->view->name, $syntax->line );
	}
	
	/*
	 * Processing captured comment.
	 *
	 * @access Private
	 *
	 * @params Yume\Fure\Support\Data\DataInterface $syntax
	 *
	 * @return Array<String<raw>,String<result>>
	 */
	private function processComment( Data\DataInterface $syntax ): Array
	{
		// Default replacement is blank for comment.
		$replace = "";
		
		/*
		 * Add backslash for sensitive characters.
		 * This is done to prevent regular expressions from
		 * capturing syntax inside comments, Because it will
		 * only burden the system.
		 *
		 */
		$syntax->match->text = $this->unclean( $syntax->match->text ?? "" );
		
		// Check if comment is taggar type.
		if( isset( $syntax->match->taggar ) )
		{
			// Create raw captured.
			$syntax->raw = $raw = f( "{}{}", $syntax->indent->value, $syntax->match->taggar );
			
			// Check if comment is html type.
			if( isset( $syntax->match->html ) )
			{
				// Change to html comment only.
				$replace = f( $this->commentRemove === False && $this->commentDisplay ? "{}<!--{}-->" : "", $syntax->indent->value, $syntax->match->text );
			}
			else {
				
				// Check if comment taggar is Doctument Type.
				if( preg_match( "/^(?:\!DOCTYPE(?:\s*)\/(?:\s*)(?<type>[a-zA-Z0-9_\-]+))(?:[\s\t]*)$/i", $syntax->match->text, $taggar ) )
				{
					$replace = f( "{}<!DOCTYPE {}>", $syntax->indent->value, $taggar['type'] );
				}
				else {
					
					// Change to html comment only.
					$replace = f( $this->commentRemove === False && $this->commentDisplay ? "{}<!--{}-->" : "", $syntax->indent->value, $syntax->match->text );
				}
			}
		}
		else {
			
			// Change to html comment only.
			$replace = f( $this->commentDisplay ? "<!--{}-->" : "", $syntax->match->text );
			
			// Create raw captured.
			$syntax->raw = $raw = f( "{}{}", $syntax->indent->value, $syntax->match->html );
		}
		return([
			"raw" => $raw,
			"result" => $replace
		]);
	}
	
	/*
	 * Processing captured output.
	 *
	 * @access Private
	 *
	 * @params Yume\Fure\Support\Data\DataInterface $syntax
	 *
	 * @return Array<String<raw>,String<result>>
	 *
	 * @throws Yume\Fure\View\Template\TemplateSyntaxError
	 */
	private function processOutput( Data\DataInterface $syntax ): Array
	{
		// Get raw matched syntax.
		$raw = $syntax->match->matched;
		
		// Get line by syntax position.
		$syntax->line = $this->getPLine( $syntax->view->name, $raw );
		
		if( valueIsNotEmpty( $syntax->match->value ) )
		{
			// Remove spaces in begining and ending syntax.
			$value = $this->clear( $syntax->match->value );
			
			// Check if syntax has operation symbol.
			if( preg_match( "/^(?<operator>(?(?=(?<assigment>\=)|(?<htmlabe>\:)|(?<increment>\+{1,2})|(?<increment_output>\+{1,2}\=)|(?<decrement>\-{1,2})|(?<decrement_output>\-{1,2}\=)|(?<null_coalesting>\?{2})|(?<non_associative>\?\:)))?(?<else>[^\s]+))\s*(?<value>[^\n]*)/ms", $value, $match ) )
			{
				//$match = RegExp\RegExp::clear( $match );
				
				if( $match['value'] )
				{
					// Get result match syntax.
					$result = match( True )
					{
						// Assigment/ define operator.
						$match['assigment'] && $match['assigment'] === $match['else'] => "<?php %s; ?>",
						
						// Output without htmlspecialchars function.
						$match['htmlabe'] && $match['htmlabe'] === $match['else'] => "<?= %s ?>",
						
						// Incrementation operator.
						$match['increment'] && $match['increment'] === $match['else'] => "<?php %s++; ?>",
						
						// Incrementation operator with output.
						$match['increment_output'] && $match['increment_output'] === $match['else'] => "<?= %s++ ?>",
						
						// Decrement operator.
						$match['decrement'] && $match['decrement'] === $match['else'] => "<?php %s--; ?>",
						
						// Decrement operator with output.
						$match['decrement_output'] && $match['decrement_output'] === $match['else'] => "<?= %s-- ?>",
						
						// Nullable/ Null coalesting.
						$match['null_coalesting'] && $match['null_coalesting'] === $match['else'] => "<?= %s ?? \"\" ?>",
						
						// Non associative.
						$match['non_associative'] && $match['non_associative'] === $match['else'] => "<?= %s ?: \"\" ?>",
						
						default => throw new TemplateSyntaxError( $match['operator'], $syntax->view->name, $syntax->line )
					};
					
					// Format result match syntax.
					$result = sprintf( $result, $match['value'] );
				}
				else {
					$result = sprintf( "<?= htmlspecialchars( %s ) ?>", $match['else'] );
				}
				return([
					"raw" => $raw,
					"result" => $result
				]);
			}
		}
		throw new TemplateSyntaxError( $syntax->match->matched, $syntax->view->name, $syntax->line );
	}
	
	/*
	 * Re-Build syntax begin.
	 *
	 * @access Public
	 *
	 * @params Yume\Fure\Support\Data\DataInterface $syntax
	 * @params Bool $outline
	 *
	 * @return String
	 */
	public function reBuildSyntaxBegin( Data\DataInterface $syntax, Bool $outline = True ): String
	{
		return( Util\Str::fmt( "{ indent }@{ inline }{ symbol }{ outline }", indent: $syntax->indent->value ?? "", inline: $syntax->inline ?? "", symbol: $syntax->symbol, outline: str_replace( "\x0a", "", $syntax->outline ?? "" ) ) );
	}
	
	/*
	 * Re-Build syntax capture.
	 *
	 * @access Public
	 *
	 * @params Yume\Fure\Support\Data\DataInterface $syntax
	 *
	 * @return String
	 */
	public function reBuildSyntaxCapture( Data\DataInterface $syntax ): String
	{
		// Check if closing syntax is allowed.
		if( $syntax->closing->valid )
		{
			// Check if captured has children.
			if( $syntax->children !== Null )
			{
				return( Util\Str::fmt( "{begin}\x0a{children}\x0a{closing}", begin: $syntax->begin, children: $syntax->children, closing: $syntax->closing->syntax ) );
			}
			return( Util\Str::fmt( "{begin}\x0a{closing}", begin: $syntax->begin, closing: $syntax->closing->syntax ) );
		}
		else {
			
			// Check if captured has children.
			if( $syntax->children !== Null )
			{
				return( Util\Str::fmt( "{begin}\x0a{children}", begin: $syntax->begin, children: $syntax->children ) );
			}
			return( $syntax->begin );
		}
	}
	
	/*
	 * Re-Build syntax children.
	 *
	 * @access Public
	 *
	 * @params Array $children
	 *
	 * @return String
	 */
	public function reBuildSyntaxChild( Array $children ): String
	{
		return( implode( "\x0a", $children ) );
	}
	
	/*
	 * Remove all first line.
	 *
	 * @access Public
	 *
	 * @params Array $content
	 *
	 * @return Array
	 */
	public function removeFirstLine( Array $content ): Array
	{
		if( isset( $content[0] ) )
		{
			// Get first content value.
			$first = $content[0];
			
			// Check if first deep content is empty
			if( $first === "" || $first === "\x0a" )
			{
				// Unset first content.
				unset( $content[0] );
				
				// Looping!
				$content = $this->removeFirstLine( $content );
			}
		}
		return( $content );
	}
	
	/*
	 * Remove all last line.
	 *
	 * @access Public
	 *
	 * @params Array $content
	 * @params Bool $closing
	 *
	 * @return Array
	 */
	public function removeLastLine( Array $content, Bool $closing ): Array
	{
		if( count( $content ) !== 0 )
		{
			// Get last content value.
			$last = end( $content );
			
			// Check if last deep content is empty
			// And syntax does not have closing.
			if( ( $last  === "" || $last === "\x0a" ) && $closing === False )
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
	 * Resolve indentation value.
	 *
	 * @access Public
	 *
	 * @params String $indent
	 *
	 * @return String
	 */
	public function resolveIndent( String $indent ): String
	{
		// Split indentation with new line.
		$split = explode( "\x0a", $indent );
		
		// Get last splited indentation.
		return( end( $split ) );
	}
	
	/*
	 * Add backslash for sensitive symbols.
	 *
	 * @access Public
	 *
	 * @params String $content
	 * @params Array $except
	 *
	 * @return String
	 */
	public function unclean( String $content, Array $except = [] ): String
	{
		// Default symbols will replace.
		$chars = [ "\\#", "\\:", "\\;", "\\\"", "\\'", "\\=", "\\@", "\\[", "\\]", "\\{", "\\}", "\\<", "\\>" ];
		
		// Mapping symbols.
		foreach( $except As $char )
		{
			// If symbols exists.
			if( False !== $idx = array_search( $char, $chars ) )
			{
				unset( $chars[$idx] );
			}
		}
		return( preg_replace_callback( f( "/(?<!\\\)({implode(+)})/", [ "|", $chars ] ), fn( Array $m ) => "\x5c{$m[0]}", $content ) );
	}
	
}

?>