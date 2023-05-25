<?php

namespace Yume\Fure\Util\Env;

use Generator;

use Yume\Fure\Error;
use Yume\Fure\IO\File;
use Yume\Fure\Support;
use Yume\Fure\Util;
use Yume\Fure\Util\Json;
use Yume\Fure\Util\RegExp;

/*
 * Env
 *
 * @extends Yume\Fure\Support\Singleton
 *
 * @package Yume\Fure\Util\Env
 */
class Env extends Support\Singleton
{
	
	/*
	 * Default source stored environment file.
	 *
	 * @access Public Static
	 *
	 * @values String
	 */
	public const DEFAULT = ".env";
	
	/*
	 * Current raw captured variable.
	 *
	 * @access Private
	 *
	 * @values String
	 */
	private ? String $raw = Null;
	
	/*
	 * Instance of class Pattern.
	 *
	 * @access Protected Readonly
	 *
	 * @values Yume\Fure\Util\RegExp\Pattern
	 */
	public Readonly RegExp\Pattern $pattern;
	
	/*
	 * Source stored environment file.
	 *
	 * @access Public Readonly
	 *
	 * @values String
	 */
	public Readonly String $source;
	
	/*
	 * All defined environment variable.
	 *
	 * @access Protected
	 *
	 * @values Array<Yume\Fure\Util\Env\EnvVariable>
	 */
	protected Array $vars = [];
	
	/*
	 * @inherit Yume\Fure\Support\Singleton::__construct
	 *
	 */
	protected function __construct( ? String $source = Null, public Readonly Bool $override = True )
	{
		// Check if application run on cli mode.
		if( YUME_CONTEXT_CLI )
		{
			// Throw if environment file does not exists.
			if( File\File::exists( $this->source = $source ??= self::DEFAULT, False ) ) throw new File\FileNotFoundError( $this->source );
		}
		
		// Register builtin environments.
		Util\Arrays::map( $_ENV ?? [], fn( Int | String $i, String $name, Mixed $value ) => $this->vars[$name] = new EnvVariable( $name, $value, system: True ) );
	}
	
	/*
	 * Convert the variable value to the given value type.
	 *
	 * @access Protected
	 *
	 * @params Mixed $value
	 * @params Yume\Fure\Util\Type $type
	 * @params String $raw
	 *
	 * @return Mixed
	 */
	protected function convert( Mixed $value, Util\Type $type, ? String $raw ): Mixed
	{
		if( $raw )
		{
			return( match( $type )
			{
				Util\Type::Array,
				Util\Type::Object => Json\Json::decode( $value, $type === Util\Type::Array ),
				Util\Type::Bool => Util\Boolean::parse( $value ),
				Util\Type::Float => ( Float ) $value,
				Util\Type::Int => ( Int ) $value,
				Util\Type::Json,
				Util\Type::String => ( String ) $value,
				Util\Type::None => Null,
				Util\Type::Raw => unserialize( $value ),
				Util\Type::Mixed => $value,
			});
		}
		return( Null );
	}
	
	/*
	 * Extract comments.
	 *
	 * @access Protected
	 *
	 * @params String $comments
	 *  Single or Multiline raw comments.
	 *
	 * @return Generator
	 */
	protected function extractComments( String $comments ): Generator
	{
		// Split comments with newline.
		foreach( split( $comments, "\n" ) As $comment )
		{
			// Check if comment is valid comment.
			if( $this->isComment( $comment, True, $matches ) )
			{
				yield trim( $matches['comment'] ?? "" );
			}
			break;
		}
	}
	
	/*
	 * Find line number by current raw matched.
	 *
	 * @access Protected
	 *
	 * @return Int
	 */
	protected function findLine(): Int
	{
		// Readline file contents.
		$line = File\File::readline( $this->source );
		
		// Split raw content with newline.
		$raw = split( $this->raw, "\n" );
		$raw = array_pop( $raw );
		
		return( array_search( $raw, $line ) +1 );
	}
	
	/*
	 * Return if variable set and not commented.
	 *
	 * @access Public Static
	 *
	 * @params String $name
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public static function has( String $name, ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? self::has( $name ) === $optional : isset( self::self()->vars[$name] ) && self::self()->vars[$name]->isCommented( False ) );
	}
	
	/*
	 * Get env values.
	 *
	 * @access Public Static
	 *
	 * @params String $env
	 * @params Mixed $optional
	 *
	 * @return Mixed
	 *
	 * @throws Yume\Fure\Util\Env\EnvError
	 */
	public static function get( String $name, Mixed $optional = Null ): Mixed
	{
		// If variable is sets.
		if( self::has( $name ) ) return( self::self() )->vars[$name]->getValue();
		
		// If optional value is available.
		if( $optional !== Null ) return( $optional );
		
		throw new EnvError( $name, Null, 0, EnvError::REFERENCE_ERROR );
	}
	
	/*
	 * Get all defined environments.
	 *
	 * @access Public Static
	 *
	 * @return Array
	 */
	public static function getAll(): EnvVariables
	{
		return( self::self() )->vars;
	}
	
	
	/*
	 * Return if defined type does not match with value Type.
	 *
	 * @access Protected
	 *
	 * @params Yume\Fure\Util\Type $type
	 * @params Yume\Fure\Util\Type $typedef
	 *
	 * @return Bool
	 */
	protected function invalid( Util\Type $type, Util\Type $typedef ): Bool
	{
		return( $typedef !== $type && $type !== Util\Type::Mixed &&
			(
				(
					(
						$type === Util\Type::Array || 
						$type === Util\Type::Json ||
						$type === Util\Type::Object
					) &&
					$typedef !== Util\Type::Json
				) ||
				(
					(
						$type === Util\Type::Raw ||
						$type === Util\Type::String
					) &&
					$typedef !== Util\Type::String
				) ||
				(
					(
						$type === Util\Type::Float ||
						$type === Util\Type::Int
					) &&
					(
						$typedef !== Util\Type::Float ||
						$typedef !== Util\Type::Int
					)
				) ||
				(
					$type === Util\Type::None &&
					$typedef === Util\Type::None
				)
			)
		);
	}
	
	/*
	 * Return if raw contents is single line comment.
	 *
	 * @access Protected
	 *
	 * @params String $comment
	 * @params Bool $optional
	 * @params Mixed &$matches
	 *
	 * @return Bool
	 */
	protected function isComment( String $comment, ? Bool $optional = Null, Mixed &$matches = [] ): Bool
	{
		return( $optional !== Null ? $this->isComment( $comment, Null, $matches ) === $optional : preg_match( "/^[\r\n\s\t]*\#(?<comment>[^\n]*)$/", $comment, $matches ) );
	}
	
	/*
	 * Load and parse raw environment file.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function onload(): Void
	{
		// Check if application run on web server.
		if( YUME_CONTEXT_CLI_SERVER || YUME_CONTEXT_WEB )
		{
			// Check if package compiled environment module exists.
			if( Support\Package::has( self::MODULE ) )
			{
				// Import compiled environment.
				$this->vars = Support\Package::import( self::MODULE );
				return;
			}
		}
		
		// Throw if environment file does not exists.
		if( File\File::exists( $this->source, False ) ) throw new File\FileNotFoundError( $this->source );
		
		// Create new Pattern instance.
		$this->pattern = new RegExp\Pattern( "^(?<comments>(?:[\s\t]*\#[^\n]*\n){0,})(?<variable>(?:(?:[\s\t]*)(?<commented>\#))*(?:[\s\t]*)(?:(?<type>[a-zA-Z_\x80-\xff][a-zA-Z0-9-_\x80-\xff]*[a-zA-Z0-9_\x80-\xff]{0,1})?(?:[\s\r\n\t]+))?(?<name>[a-zA-Z_\x80-\xff][a-zA-Z0-9-_\x80-\xff]*[a-zA-Z0-9_\x80-\xff]{0,1})(?:[\s\r\n\t]*)(?:(?<operator>=)(?:[\s\r\n\t]*)(?<value>(?:[^\;])*))*(?<closing>\;))(?<comment>[^\n]*)", "ms" );
		
		// Parsing contents.
		$this->parse(
			
			// Read environment file contents.
			File\File::read( $this->source )
		);
	}
	
	/*
	 * Parse environment file contents.
	 *
	 * @access Protected
	 *
	 * @params String $contents
	 *
	 * @return Void
	 */
	protected function parse( String $contents ): Void
	{
		while( $match = $this->pattern->exec( $contents ) )
		{
			$this->process( $match );
		}
	}
	
	/*
	 * Processing variable.
	 *
	 * @access Protected
	 *
	 * @params Yume\Fure\Util\RegExp\Matches $match
	 *
	 * @return Void
	 */
	protected function process( RegExp\Matches $match ): Void
	{
		// Get captured groups.
		$groups = $match->groups;
		
		// Get variable name.
		$name = $groups->name->value;
		
		// Get variable value (If available).
		$value = $groups->commented ? Null : $groups->value->value ?? Null;
		
		try
		{
			// Get enum Type.
			$type = $this->typedef( $groups->type ? $groups->type : ( $groups->commented ? ( $groups->value ? "Mixed" : "None" ) : "None" ) );
		}
		catch( EnvError )
		{
			$type = Util\Type::Mixed;
		}
		
		$quoted = Null;
		$comment = Null;
		
		// When variable is commented.
		$commented = isset( $groups['commented'] );
		
		// If variable has multiple comments.
		if( $groups->comments )
		{
			// Re-match commented Environment variables.
			$comments = $this->pattern->replace( $groups->comments, fn( RegExp\Matches $matches ) => $this->process( $matches ) );
			
			// Extract comments.
			$comments = iterator_to_array( $this->extractComments( $comments ) );
		}
		
		// Set current captured syntax.
		$this->raw = $match[0];
		
		// Check if variable has content in last defined variable.
		if( valueIsNotEmpty( $groups->comment->value ?? Null ) )
		{
			// Check if in last defined variable is not comment syntax.
			if( $this->isComment( split( $groups->comment->value, "\n" )[0], False, $matches ) )
			{
				throw new EnvError( $groups->comment->value, $this->source, $this->findLine(), EnvError::SYNTAX_ERROR );
			}
			$comment = $matches['comment'] ?? "";
		}
		
		// If the variable is not commented out.
		if( $commented === False )
		{
			// If variable has value.
			if( valueIsNotEmpty( $value ) )
			{
				// Remove whitespace.
				$value = trim( $value );
				
				// Check if value is escaped with single or double quote.
				if( $result = RegExp\RegExp::match( "/^(?<quote>[\"\'])(?<value>(?:\\\\1|(?!\\\\1).)*)\\1/ms", $value ) )
				{
					$typedef = Util\Type::String;
					$quoted = $result['quote'];
					$value = $result['value'];
				}
				else {
					
					// Matching invalid comment symbol on values.
					$value = RegExp\RegExp::replace( "/(?<nomatch>\\\{0,})(?<symbol>\#{1,})/ms", $value, function( Array $match )
					{
						// Get backslash lenght.
						$length = strlen( $match['nomatch'] ?? "" );
						
						// If the number of backslashes is one.
						if( $length === 1 ) return( "#" );
						
						// If number of backslash is odd.
						if( Util\Number::isOdd( $length ) )
						{
							return( Util\Strings::fmt( "{}#", str_repeat( "\\", $length -1 ) ) );
						}
						throw new EnvError( $match['symbol'], $this->source, $this->findLine(), EnvError::COMMENT_ERROR );
					});
					
					// If variable value is Array type.
					if( $result = RegExp\RegExp::match( "/^(?<value>(?<curly>(?s)((?:\{(?:[^\{\}]++|(?1))*+\})))|(?<square>(?s)((?:\[(?:[^\[\]]++|(?1))*+\]))))$/ms", $value ) )
					{
						$typedef = Util\Type::Json;
						$value = $result['value'];
					}
					else {
						
						// If variable value is Boolean type.
						if( $result = RegExp\RegExp::match( "/^(?:[\r\t\n\t\s]*)(?<value>True|False)\\1*$/msi", $value ) )
						{
							$typedef = Util\Type::Bool;
							$value = $result['value'];
						}
						
						// If variable value is None type.
						else if( $result = RegExp\RegExp::match( "/^([\r\t\n\t\s]*)(?<nullable>None|Null)\\1*$/msi", $value ) )
						{
							$typedef = Util\Type::None;
							$value = $result['value'];
						}
						else {
							
							// String is a default value.
							$typedef = Util\Type::String;
							
							// If variable value is valid Integer or Numeric type.
							if( Util\Number::isInteger( $value ) ||
								Util\Number::isNumeric( $value ) )
							{
								// If variable is valid Double or Float number.
								if( Util\Number::isDouble( $value ) ||
									Util\Number::isExponentDouble( $value ) ||
									Util\Number::isFloat( $value ) )
								{
									$typedef = Util\Type::Float;
								}
								else {
									$typedef = Util\Type::Int;
								}
							}
						}
					}
				}
				
				// If variable has defined named Type.
				if( $groups->type )
				{
					// If given value does not match with defined Type.
					if( $this->invalid( $type, $typedef ) )
					{
						throw new EnvError();
					}
				}
			}
		}
		try
		{
			$value = $this->convert( $value, $type, $groups->value );
		}
		catch( Error\ValueError $e )
		{
			if( $commented )
			{
				$value = Null;
			}
			else {
				throw new EnvError();
			}
		}
		
		/*
		 * Check if variable is defined and
		 * If variable is not allowed for override.
		 *
		 */
		if( isset( $this->vars[$name] ) && $this->vars[$name]->isSystem( False ) && $this->override === False )
		{
			throw new EnvError();
		}
		$this->raw = Null;
		$this->vars[$name] = new EnvVariable(
			name: $name,
			value: $value,
			type: $type,
			comment: $comment,
			comments: $comments,
			commented: $commented,
			system: False,
			quoted: $quoted,
			raw: Null
		);
	}
	
	/*
	 * Return variable type.
	 *
	 * @access Protected
	 *
	 * @params String $type
	 *
	 * @return Yume\Fure\Util\Type
	 *
	 * @throws Yume\Fure\Util\Env\EnvError
	 */
	protected function typedef( String $type ): Util\Type
	{
		$type = ucfirst( Util\Strings::fromKebabCaseToCamelCase( $type ) );
		return(
			match( $type )
			{
				"Array" => Util\Type::Array,
				"Bool",
				"Boolean" => Util\Type::Bool,
				"Double",
				"Float" => Util\Type::Float,
				"Int",
				"Integer" => Util\Type::Int,
				"Json" => Util\Type::Json,
				"Mixed" => Util\Type::Mixed,
				"None" => Util\Type::None,
				"Object" => Util\Type::Object,
				"Raw" => Util\Type::Raw,
				"String" => Util\Type::String,
				
				default => throw new EnvError( $type, $this->source, $this->findLine(), EnvError::TYPEDEF_ERROR )
			}
		);
	}
	
}

?>