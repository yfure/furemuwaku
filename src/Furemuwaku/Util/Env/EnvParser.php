<?php

namespace Yume\Fure\Util\Env;

use Yume\Fure\Util\Array;
use Yume\Fure\Util\File;
use Yume\Fure\Util\Json;
use Yume\Fure\Util\RegExp;
use Yume\Fure\Util\Timer;
use Yume\Fure\Util\Type;

/*
 * EnvParser
 *
 * @package Yume\Fure\Util\Env
 */
class EnvParser
{
	
	/*
	 * Instance of class Pattern.
	 *
	 * @access Protected Readonly
	 *
	 * @values Yume\Fure\Util\RegExp\Pattern
	 */
	protected Readonly RegExp\Pattern $pattern;
	
	/*
	 * Variable containers.
	 *
	 * @access Protected Readonly
	 *
	 * @values Yume\Fure\Util\Env\EnvVariables
	 */
	protected Readonly EnvVariables $vars;
	
	/*
	 * Raw contents from file.
	 *
	 * @access Protected
	 *
	 * @values String
	 */
	protected String $readed;
	
	/*
	 * Current raw captured variable.
	 *
	 * @access Private
	 *
	 * @values String
	 */
	private ? String $raw = Null;
	
	/*
	 * Construct method of class EnvParser
	 *
	 * @access Public Instance
	 *
	 * @params Public Readonly String $file
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\Support\File\FileNotFoundError
	 */
	public function __construct( public Readonly String $file )
	{
		// Check if file is not found.
		if( File\File::none( $file ) )
		{
			throw new File\FileNotFoundError( $file );
		}
		
		// Create new pattern instance.
		$this->pattern = new RegExp\Pattern( "^(?<raw>(?<comments>(^[\s\t]*\#[^\n]*\n){0,})(?<commented>^[\s\t]*\#[\s\t]*)*(?<name>[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)([\s\t]*(?<operator>[=]+)[\s\t]*)(?<value>.*?)(?<!\\\)\;(?<unknown>[^\n]*))", "ms" );
		
		// Create new Environment Collection.
		$this->vars = new EnvVariables();
	}
	
	/*
	 * Return filename.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getFile(): String
	{
		return( $this )->file;
	}
	
	/*
	 * Get all variables.
	 *
	 * @access Public
	 *
	 * @return Yume\Fure\Util\Env\EnvVariables
	 */
	public function getVars(): EnvVariables
	{
		return( $this )->vars;
	}
	
	/*
	 * Read Environment file.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function read(): String
	{
		return( $this->readed = File\File::read( $this->file ) );
	}
	
	/*
	 * Parse environment file.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function parse(): Void
	{
		$this->pattern->replace( $this->read(), fn( Array $match ) => $this->process( $match ) );
	}
	
	/*
	 * Process matched variable.
	 *
	 * @access Private
	 *
	 * @params Array $match
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\Util\Env\EnvError
	 * @throws Yume\Fure\Util\Json\JsonError
	 */
	private function process( Array $match ): Void
	{
		// Push current raw matched varaible.
		$this->raw = $match['raw'];
		
		// Defaults.
		$comment = Null;
		$comments = Null;
		$value = [
			"type" => EnvTypes::NONE,
			"value" => $match['value'],
			"quoted" => False,
			"reference" => []
		];
		
		// Check if variable has multiple comments.
		if( valueIsNotEmpty( $match['comments'] ) )
		{
			// Matching commented Environment variables.
			$comments = $this->pattern->replace( $match['comments'], fn( Array $match ) => $this->process( $match ) );
			
			// Split comments with newline.
			$comments = explode( "\n", $comments );
			
			// Mapping multiple comments.
			Array\Arr::map( $comments, function( $i ) use( &$comments )
			{
				// Check if comment is empty.
				if( valueIsNotEmpty( $comments[$i] ) )
				{
					// Remove (#) in prefix comment.
					$comments[$i] = RegExp\RegExp::replace( "/[\s\t]*\#[\s\t]*([^\n]*)/", $comments[$i], "$1" );
				}
				else {
					unset( $comments[$i] );
				}
			});
			
			// Replace current raw value.
			$this->raw = $match['raw'] = str_replace( $match['comments'], count( $comments ) > 0 ? sprintf( "\x23\x20\x25\x73\x0a", implode( "\x0a\x23\x20", $comments ) ) : "", $match['raw'] );
		}
		
		// If there is a character at the end of the variable definition.
		if( valueIsNotEmpty( $match['unknown'] ) )
		{
			// Check if variable has comment in last defined variable.
			if( $match2 = RegExp\RegExp::match( "/^([\s\t]*)(?<comment>\#(\\1*)(?<comment_text>[^\n]*))$/", $match['unknown'] ) )
			{
				// Get comment text.
				$comment = $match2['comment_text'] ?? "";
			}
			else {
				throw new EnvError( $match['unknown'], $this->file, $this->findLine(), EnvError::SYNTAX_ERROR );
			}
		}
		
		// Check if variable is commented.
		$commented = valueIsNotEmpty( $match['commented'] );
		
		try
		{
			// Check if variable is not commented.
			if( $commented === False )
			{
				// Check if variable has invalid assigment operator.
				if( strlen( $match['operator'] ) > 1 )
				{
					throw new EnvError( $match['operator'], $this->file, $this->findLine(), EnvError::ASSIGMENT_ERROR );
				}
				
				// Get variable values.
				$value = $this->valueProcess( $match['value'] );
			}
			
			// Push variable.
			$this->vars[$match['name']] = new EnvVariable(
				raw: $match['raw'],
				name: $match['name'],
				type: $value['type'],
				value: $value['value'],
				valueReference: $value['reference'],
				comment: $comment,
				comments: $comments,
				commented: $commented,
				quoted: $value['quoted']
			);
		}
		catch( Json\JsonError $e )
		{
			throw new EnvError( $match['name'], $this->file, $this->findLine(), EnvError::JSON_ERROR, $e );
		}
		
		// Mapping value reference.
		Array\Arr::map( $value['reference'], fn( Int $i, String $name, EnvVariable $var ) => $var->addReferenced( $match['name'], $this->vars[$match['name']] ) );
		
		// Set raw as null.
		$this->raw = Null;
	}
	
	/*
	 * Find line number by current raw matched.
	 *
	 * @access Private
	 *
	 * @return Int
	 */
	private function findLine(): Int
	{
		// Remove all comments syntax.
		$readed = RegExp\RegExp::replace( "/^\#[^\n]*/ms", $this->readed, "" );
		
		// Split readed file with newline.
		$lines = explode( "\n", $readed );
		
		// Split raw content with newline.
		$raw = explode( "\n", $this->raw );
		$raw = array_shift( $raw );
		
		// Mapping readed file.
		foreach( $lines As $line => $content )
		{
			// Check if current line content is same content.
			if( $content === $raw )
			{
				return( $line +1 );
			}
		}
		return( 0 );
	}
	
	/*
	 * Process variable values.
	 *
	 * @access Private
	 *
	 * @params String $rawValue
	 *
	 * @return Array<<value Mixed>, <reference Yume\Fure\Support\Data\DataInterface>, <type Yume\Fure\Util\Env\EnvTypes>>
	 *
	 * @throws Yume\Fure\Util\Env\EnvError
	 */
	private function valueProcess( ? String $rawValue ): Array
	{
		// Default type & value is null.
		$type = EnvTypes::NONE;
		$value = [
			"reference" => [],
			"quoted" => False,
			"value" => Null
		];
		
		// Check if value is not empty.
		if( valueIsNotEmpty( $rawValue ) )
		{
			// Check if value is escaped with single or double quote.
			if( $match = RegExp\RegExp::match( "/^([\"\'])(?<value>(?:\\\\1|(?!\\\\1).)*)\\1/ms", $rawValue ) )
			{
				$type = EnvTypes::STRING;
				
				// Replace variable name with value.
				$value = $this->valueReplace( $match['value'] );
				$value['quoted'] = True;
			}
			else {
				
				// Matching invalid comment symbol on values.
				$rawValue = RegExp\RegExp::replace( "/(?<nomatch>\\\{0,})(?<symbol>\#{1,})/ms", $rawValue, function( Array $match )
				{
					// Get backslash lenght.
					$length = strlen( $match['nomatch'] ?? "" );
					
					// If the number of backslashes is one.
					if( $length === 1 ) return( "#" );
					
					// If number of backslash is odd.
					if( Type\Num::isOdd( $length ) ) return( Type\Str::fmt( "{}#", str_repeat( "\\", $length -1 ) ) );
					
					throw new EnvError( $match['symbol'], $this->file, $this->findLine(), EnvError::COMMENT_ERROR );
				});
				
				// Replace variable name with value.
				$value = $this->valueReplace( $rawValue );
				
				// If variable value is Array type.
				if( $match = RegExp\RegExp::match( "/^(?<value>(?<curly>(?s)((?:\{(?:[^\{\}]++|(?1))*+\})))|(?<square>(?s)((?:\[(?:[^\[\]]++|(?1))*+\]))))$/ms", $value['value'] ) )
				{
					// Default variable type.
					$type = EnvTypes::DICT;
					
					// If variable value is List.
					if( $match['square'] )
					{
						$type = EnvTypes::LIST;
					}
					
					// Decode json strings into array.
					$value['value'] = Json\Json::decode( $match['value'], True );
				}
				else {
					
					// If variable value is Boolean type.
					if( $match = RegExp\RegExp::match( "/^([\r\t\n\t\s]*)((?<true>True)|(?<false>False))\\1*$/msi", $value['value'] ) )
					{
						$type = EnvTypes::BOOLEAN;
						
						// Parse string to boolean type.
						$value['value'] = $match['true'] ? True : False;
					}
					
					// If variable value is Int type.
					else if( $match = RegExp\RegExp::match( "/^([\r\t\n\t\s]*)(?<number>[0-9_]+)\\1*$/ms", $value['value'] ) )
					{
						$type = EnvTypes::NUMBER;
						
						// Parse string to Number.
						$value['value'] = ( Int ) $match['number'];
					}
					
					// If variable value is None type.
					else if( $match = RegExp\RegExp::match( "/^([\r\t\n\t\s]*)(?<nullable>None|Null)\\1*$/msi", $value['value'] ) )
					{
						$value['value'] = Null;
					}
					
					// Default value type is String type.
					else {
						
						$type = EnvTypes::STRING;
					}
				}
			}
		}
		return([ "type" => $type, ...$value, "quoted" => $value['quoted'] ?? False ]);
	}
	
	/*
	 * Replace variable name on variable value with variable value.
	 *
	 * @access Private
	 *
	 * @params String $value
	 *
	 * @return Array<<value Mixed>, <reference Yume\Fure\Support\Data\DataInterface>
	 *
	 * @throws Yume\Fure\Util\Env\EnvError
	 */
	private function valueReplace( String $value ): Array
	{
		$refer = [];
		$value = RegExp\RegExp::replace( "/(?<nomatch>\\\{0,})\\$(?<name>[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)/", $value, function( Array $match ) use( &$refer )
		{
			// Get backslash lenght.
			$length = strlen( $match['nomatch'] );
			
			// If the number of backslashes is one.
			if( $length === 1 ) return( Type\Str::fmt( "\${}", $match['name'] ) );
			
			// If number of backslash is odd.
			if( Type\Num::isOdd( $length ) ) return( Type\Str::fmt( "{}\${}", str_repeat( "\\", $length -1 ), $match['name'] ) );
			
			// Check if variable is exists and not commented.
			if( isset( $this->vars[$match['name']] ) && $this->vars[$match['name']]->hasCommented() === False )
			{
				// Push value reference.
				$refer[$match['name']] = $this->vars[$match['name']];
				
				// Return reference value.
				return( Type\Str::parse( $this->vars[$match['name']]->getValue() ?? "" ) );
			}
			throw new EnvError( $match['name'], $this->file, $this->findLine(), EnvError::REFERENCE_ERROR );
		});
		
		// Find all and replace backslash.
		$value = RegExp\RegExp::replace( "/(\\\{1,})/ms", $value, fn( Array $match ) => $match[0] === "\x5c" ? "" : str_repeat( "\x5c", strlen( $match[0] ) -1 ) );
		
		return([
			"reference" => $refer,
			"value" => $value
		]);
	}
	
}

?>