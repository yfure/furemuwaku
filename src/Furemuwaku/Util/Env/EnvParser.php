<?php

namespace Yume\Fure\Util\Env;

use Generator;

use Yume\Fure\Error;
use Yume\Fure\Util;
use Yume\Fure\Util\Json;
use Yume\Fure\Util\RegExp;

/*
 * EnvParser
 *
 * @package Yume\Fure\Util\Env
 */
class EnvParser implements EnvParserInterface {
	
	/*
	 * Environment contents.
	 *
	 * @access Private
	 *
	 * @values String
	 */
	private ? String $contents = Null;
	
	/*
	 * Environment contents splited.
	 *
	 * @access Private
	 *
	 * @values Array
	 */
	private ? Array $contentsSplited = Null;
	
	/*
	 * Instance of class Pattern.
	 *
	 * @access Protected Readonly
	 *
	 * @values Yume\Fure\Util\RegExp\Pattern
	 */
	public Readonly RegExp\Pattern $pattern;
	
	/*
	 * Current raw captured variable.
	 *
	 * @access Private
	 *
	 * @values String
	 */
	private ? String $raw = Null;
	
	/*
	 * Construct method of class EnvParser.
	 *
	 * @access Public Initialize
	 *
	 * @return Void
	 */
	public function __construct() {
		$this->pattern = new RegExp\Pattern( "^(?<variable>(?:(?:[\s\t]*)(?<commented>\#))*(?:[\s\t]*)(?:(?<typedef>[a-zA-Z_\x80-\xff][a-zA-Z0-9-_\x80-\xff]*[a-zA-Z0-9_\x80-\xff]{0,1})?(?:[\s\r\n\t]+))?(?<name>[a-zA-Z_\x80-\xff][a-zA-Z0-9-_\x80-\xff]*[a-zA-Z0-9_\x80-\xff]{0,1})(?:[\s\r\n\t]*)(?:(?<operator>=)(?:[\s\r\n\t]*)(?<value>.*?))*(?<closing>(?<!\\\)\;))(?<endline>[^\n]*)", "ms" );
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
	protected function convert( Mixed $value, Util\Type $type, ? String $raw ): Mixed {
		if( valueIsNotEmpty( $raw ) ) {
			return( match( $type ) {
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
	 * Look for multi line comments before variable definition.
	 *
	 * @access Protected
	 *
	 * @params Int $line
	 *
	 * @return Array
	 *  Array of multi line comments.
	 */
	protected function findComments( ? Int $line = Null ): ? Array {
		$line ??= $this->findLine() -1;
		$comments = [];	
		while( $this->isComment( $this->contentsSplited[$line] ?? "", True, $matches ) ) {
			if( $this->pattern->match( $this->contentsSplited[$line] ) === Null ) {
				$comments = [ trim( $matches['comment'] ?? "" ), ...$comments ];
			}
			if( $this->pattern->match( $this->contentsSplited[( $line -1 )] ) ) {
				break;
			}
			$line--;
		}
		return( $comments );
	}
	
	/*
	 * Find line number by current raw matched.
	 *
	 * @access Public
	 *
	 * @params String $raw
	 *
	 * @return Int
	 */
	public function findLine( ? String $raw = Null ): Int {
		$raw = split( $raw ?? $this->raw, "\n" );
		$raw = array_pop( $raw );
		return( array_search( $raw, $this->contentsSplited ) +1 );
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
	protected function invalid( Util\Type $type, Util\Type $typedef ): Bool {
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
	 * @access Public
	 *
	 * @params String $comment
	 * @params Bool $optional
	 * @params Mixed &$matches
	 *
	 * @return Bool
	 */
	public function isComment( String $comment, ? Bool $optional = Null, Mixed &$matches = [] ): Bool {
		return( $optional !== Null ? $this->isComment( $comment, Null, $matches ) === $optional : preg_match( "/^[\r\n\s\t]*\#(?<comment>[^\n]*)$/", trim( $comment, "\n" ), $matches ) );
	}
	
	/*
	 * Parse environment contents.
	 *
	 * @access Public
	 *
	 * @return Generator
	 *  Generator result of process.
	 */
	public function parse(): Generator {
		while( $match = $this->pattern->exec( $this->contents ?? "" ) ) {
			yield $this->process( $match );
		}
		$this->raw = Null;
	}
	
	/*
	 * Processing variable.
	 *
	 * @access Protected
	 *
	 * @params Yume\Fure\Util\RegExp\Matches $match
	 *
	 * @return Yume\Fure\Util\Env\EnvVariableInterface
	 */
	private function process( RegExp\Matches $match ): EnvVariableInterface {
		$this->raw = trim( $match[0], "\n" );
		$groups = $match->groups;
		$var = [
			"raw" => $this->raw,
			"line" => $this->findLine(),
			"name" => $groups->name->value,
			"value" => $groups->value->value ?? Null,
			"typedef" => $groups->typedef->value ?? Null,
			"commented" => $groups->__isset( "commented" )
		];
		try {
			$var['type'] = $this->typedef( $var['typedef'] ? $var['typedef'] : ( $var['commented'] ? "None" : ( $var['value'] ? "Mixed" : "None" ) ) );
		}
		catch( EnvError $e ) {
			if( $var['commented'] ) {
				$var['type'] = Util\Type::Mixed;
			}
			else {
				throw $e;
			}
		}
		
		// Get multiline comments before variable definition.
		$var['comments'] = $this->findComments();
		
		// Check if variable has content in last defined variable.
		if( valueIsNotEmpty( $groups->endline->value ?? Null ) ) {
			if( $var['commented'] ) {

				/*
				 * Allowed passed, since the variable has been commented
				 * out this cannot be considered a syntax error but error
				 * will be thrown when environment will be regenerated or saved.
				 *
				 */
				$var['endline'] = trim( $groups->endline->value );
			}
			else {
				
				// Check if EOL if is not comment syntax.
				if( $this->isComment( $groups->endline->value, False, $matches ) ) {
					throw new EnvError( $groups->endline->value, EnvError::SYNTAX_ERROR, Null, Env::self()->source, $var['line'] );
				}
				$var['comment'] = trim( $matches['comment'] ?? "" );
			}
		}
		
		if( $var['commented'] === False ) {
			if( valueIsNotEmpty( $var['value'] ) ) {
				$var['value'] = trim( $var['value'] );
				if( Util\Strings::isQuoted( $var['value'], $result ) ) {
					$var['typedef'] = Util\Type::String;
					$var['quoted'] = $result['quote'];
					$var['value'] = $result['value'];
				}
				else {
					$var['value'] = RegExp\RegExp::replace( "/(?<backslash>\\\{1,})(?!(\;|\#))/ms", $var['value'], fn( Array $match ) => $match['backslash'] === "\x5c" ? "" : str_repeat( "\x5c", strlen( $match['backslash'] ) -1 ) );
					$var['value'] = RegExp\RegExp::replace( "/(?<nomatch>\\\{0,})(?<symbol>(?:(?<taggar>\#)|(?<semicolon>\;)){1,})/ms", $var['value'], function( Array $match ) use( $var ) {
						$length = strlen( $match['nomatch'] ?? "" );
						if( $length === 1 ) {
							return( $match['symbol'] ?? "" );
						}
						if( Util\Number::isOdd( $length ) ) {
							return( Util\Strings::format( "{}{}", str_repeat( "\x5c", $length -1 ), $match['symbol'] ?? "" ) );
						}
						if( $match['taggar'] ?? Null ) {
							throw new EnvError( $var['name'], EnvError::COMMENT_ERROR, Null, Env::self()->source, $var['line'] );
						}
						throw new EnvError( ";", EnvError::SYNTAX_ERROR, Null, Env::self()->source, $var['line'] );
					});
					if( $result = RegExp\RegExp::match( "/^(?<value>(?<curly>(?s)((?:\{(?:[^\{\}]++|(?1))*+\})))|(?<square>(?s)((?:\[(?:[^\[\]]++|(?1))*+\]))))$/ms", $var['value'] ) ) {
						$var['typedef'] = Util\Type::Json;
						$var['value'] = $result['value'];
					}
					else {
						if( $result = RegExp\RegExp::match( "/^(?:[\r\t\n\t\s]*)(?<value>True|False)\\1*$/msi", $var['value'] ) ) {
							$var['typedef'] = Util\Type::Bool;
							$var['value'] = $result['value'];
						}
						else if( $result = RegExp\RegExp::match( "/^([\r\t\n\t\s]*)(?<nullable>None|Null)\\1*$/msi", $var['value'] ) ) {
							$var['typedef'] = Util\Type::None;
							$var['value'] = $result['value'];
						}
						else {
							$var['typedef'] = Util\Type::String;
							if( Util\Number::isInteger( $var['value'] ) ||
								Util\Number::isNumeric( $var['value'] ) ) {
								if( Util\Number::isDouble( $var['value'] ) ||
									Util\Number::isExponentDouble( $var['value'] ) ||
									Util\Number::isFloat( $var['value'] ) ) {
									$var['typedef'] = Util\Type::Float;
								}
								else {
									$var['typedef'] = Util\Type::Int;
								}
							}
						}
					}
				}
				if( $var['typedef'] ) {
					if( $this->invalid( $var['type'], $var['typedef'] ) ) {
						throw new EnvError( [ $var['type']->name, $var['typedef']->name, $var['name'] ], EnvError::ASSIGNMENT_ERROR, Null, Env::self()->source, $var['line'] );
					}
				}
			}
		}
		
		try {
			$var['value'] = $this->convert( $var['value'], $var['type'], $groups->value );
		}
		catch( Error\ValueError $e ) {
			if( $var['commented'] ) {
				$var['value'] = Null;
			}
			else {
				throw new EnvError( [ Json\Json::error(), $var['name'] ], EnvError::JSON_ERROR, Null, Env::self()->source, $var['line'], $e );
			}
		}
		
		// Unset typedef from variable.
		unset( $var['typedef'] );
		
		// Normalize variable name.
		$var['name'] = str_replace( "\x2d", "\x5f", $var['name'] );
		
		return( new EnvVariable( ...$var ) );
	}
	
	/*
	 * Set parser contents.
	 *
	 * @access Public
	 *
	 * @params String $contents
	 *
	 * @return Yume\Fure\Util\Env\EnvParserInterface
	 */
	public function setContents( String $contents ): EnvParserInterface {
		$this->contents = trim( $contents );
		$this->contentsSplited = split( $this->contents, "\n" );
		
		return( $this );
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
	protected function typedef( String $type ): Util\Type {
		$type = ucfirst( Util\Strings::fromKebabCaseToCamelCase( $type ) );
		return(
			match( $type ) {
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
				
				default => throw new EnvError( [ $type, join( "|", Util\Type::names() ) ], EnvError::TYPEDEF_ERROR, Null, Env::self()->source, $this->findLine() )
			}
		);
	}
	
}

?>