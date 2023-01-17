<?php

namespace Yume\Fure\Util\Env;

use Stringable;

use Yume\Fure\Support\Data;
use Yume\Fure\Util;
use Yume\Fure\Util\Json;
use Yume\Fure\Util\RegExp;

/*
 * EnvVariable
 *
 * @package Yume\Fure\Util\Env
 */
class EnvVariable
{
	
	/*
	 * ...
	 *
	 * @access Protected
	 *
	 * @values String
	 */
	protected ? String $comment;
	
	/*
	 * ...
	 *
	 * @access Protected
	 *
	 * @values Array
	 */
	protected ? Array $comments;
	
	/*
	 * ...
	 *
	 * @access Protected
	 *
	 * @values Bool
	 */
	protected Bool $commented;
	
	/*
	 * ...
	 *
	 * @access Protected
	 *
	 * @values Bool
	 */
	protected Bool $quoted;
	
	/*
	 * ....
	 *
	 * @access Protected
	 *
	 * @values String
	 */
	protected ? String $format;
	
	/*
	 * ...
	 *
	 * @access Protected
	 *
	 * @values String
	 */
	protected String $name;
	
	/*
	 * ...
	 *
	 * @access Protected
	 *
	 * @values Yume\Fure\Util\Env\EnvTypes
	 */
	protected EnvTypes $type;
	
	/*
	 * ...
	 *
	 * @access Protected
	 *
	 * @values String
	 */
	protected ? String $raw;
	
	/*
	 * ...
	 *
	 * @access Protected
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	protected Data\DataInterface $referenced;
	
	/*
	 * ...
	 *
	 * @access Protected
	 *
	 * @values Array|Bool|Int|String
	 */
	protected Array | Bool | Int | Null | String $value;
	
	/*
	 * ...
	 *
	 * @access Protected
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	protected Data\DataInterface $valueReference;
	
	protected Bool $unset;
	
	/*
	 * Construct method of class EnvVariable
	 *
	 * @access Public Instance
	 *
	 * @params String $name
	 * @params Array|Bool|Int|String $value
	 * @params Yume\Fure\Util\Env\EnvTypes $type
	 * @params String $comment
	 * @params Bool $commented
	 * @params Array $reference
	 * @params Array $valueReference
	 * @params String $raw
	 *
	 * @return Void
	 */
	public function __construct( String $name, Array | Bool | Int | Null | String $value, EnvTypes $type = EnvTypes::STRING, ? String $comment = Null, ? Array $comments = Null, Bool $commented = False, Bool $quoted = False, Array $reference = [], Array $valueReference = [], ? String $raw = Null )
	{
		$this->comment = $comment;
		$this->comments = $comments;
		$this->commented = $commented;
		$this->quoted = $quoted;
		$this->format = Null;
		$this->name = $name;
		$this->type = $type;
		$this->raw = $raw;
		$this->unset = False;
		$this->valueReference = new Data\Data( $valueReference );
		$this->referenced = new Data\Data( $reference );
		
		$this->setValue( $value );
	}
	
	/*
	 * Parse class into string.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function __toString(): String
	{
		return( $this->raw ?? $this->convert() );
	}
	
	/*
	 * Add referenced variable.
	 *
	 * In other words, the variable value has
	 * been used in the value of another variable.
	 *
	 * @access Public
	 *
	 * @params String $name
	 * @params Yume\Fure\Util\Env\EnvVariable
	 *
	 * @return Yume\Fure\Util\Env\EnvVariable
	 */
	public function addReferenced( String $name, EnvVariable $variable ): EnvVariable
	{
		return([ $this, $this->referenced[$name] = $variable ][0]);
	}
	
	/*
	 * Convert variable into raw string.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function convert(): String
	{
		$params = [
			"name" => $this->name,
			"value" => $this->value ?? "",
			"comment" => "",
			"comments" => "",
			"commented" => ""
		];
		
		// Check if variable has value.
		if( $this->hasValue() )
		{
			$params['value'] = match( $this->type )
			{
				EnvTypes::BOOLEAN => $this->value ? "True": "False",
				EnvTypes::DICT,
				EnvTypes::LIST => Json\Json::encode( $this->value, JSON_INVALID_UTF8_SUBSTITUTE ),
				EnvTypes::NONE => "",
				EnvTypes::NUMBER => ( String ) $this->value,
				EnvTypes::STRING => $this->value
			};
			
			// Check if environment value is String type.
			if( $this->type === EnvTypes::STRING )
			{
				// Add backslash.
				$params['value'] = RegExp\RegExp::replace( "/(\\$|\\#|\"|\'|\;|\\\)/ms", $params['value'], "\x5c\x5c\$1" );
				
				// If value has quoted.
				if( $this->isQuoted() )
				{
					$params['value'] = "\"{$params['value']}\"";
				}
			}
			
			// Mapping value reference from another variables.
			$this->valueReference->map( function( $i, $name, $var ) use( &$params )
			{
				$valueFromReference = match( $var->getType() )
				{
					EnvTypes::BOOLEAN => $var->getValue() ? "True": "False",
					EnvTypes::DICT,
					EnvTypes::LIST => Json\Json::encode( $var->getValue(), JSON_INVALID_UTF8_SUBSTITUTE ),
					EnvTypes::NONE => "",
					EnvTypes::NUMBER => ( String ) $var->getValue(),
					EnvTypes::STRING => $var->getValue()
				};
				$params['value'] = str_replace( RegExp\RegExp::replace( "/(\\$|\\#|\"|\'|\;|\\\)/ms", $valueFromReference, "\x5c\x5c\$1" ), Util\Str::fmt( "\${}", $var->getName() ), $params['value'] );
			});
		}
		
		// Check if variable has comment.
		if( $this->hasComment() )
		{
			$params['comment'] = Util\Str::fmt( " # {}", $this->comment );
		}
		
		// Check if variable has multiple comments.
		if( $this->hasMultipleComments() )
		{
			$params['comments'] = Util\Str::fmt( "# {}\n", implode( "\n# ", $this->comments ) );
		}
		
		// Check if variable has commented.
		if( $this->hasCommented() )
		{
			$params['commented'] = "# ";
		}
		
		// Return formated variable.
		return( $this->format = Util\Str::fmt( "{ comments }{ commented }{ name } = { value };{ comment }", $params ) );
	}
	
	/*
	 * Get variable comment.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getComment(): ? String
	{
		return( $this )->comment;
	}
	
	/*
	 * Get variable multiple comments.
	 *
	 * @access Public
	 *
	 * @return Array
	 */
	public function getMultipleComments(): ? Array
	{
		return( $this )->comments;
	}
	
	/*
	 * Get variable name.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getName(): String
	{
		return( $this )->name;
	}
	
	/*
	 * Get variable as raw.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getRaw(): ? String
	{
		return( $this )->raw;
	}
	
	/*
	 * Get variable referenced.
	 *
	 * @access Public
	 *
	 * @return Yume\Fure\Support\Data\DataInterface
	 */
	public function getReference(): Data\DataInterface
	{
		return( $this )->referenced;
	}
	
	/*
	 * Get variable type.
	 *
	 * @access Public
	 *
	 * @return Yume\Fure\Util\Env\EnvTypes
	 */
	public function getType(): EnvTypes
	{
		return( $this )->type;
	}
	
	/*
	 * Get variable type as Int.
	 *
	 * @access Public
	 *
	 * @return Int
	 */
	public function getTypeAsInt(): Int
	{
		return( $this )->type->value;
	}
	
	/*
	 * Get variable type as String.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getTypeAsString(): String
	{
		return( match( $this->type )
		{
			EnvTypes::BOOLEAN => "Bool",
			EnvTypes::DICT => "Array<Associative>",
			EnvTypes::LIST => "Array<Indexed>",
			EnvTypes::NUMBER => "Int<Int|Integer>",
			EnvTypes::NONE => "Null",
			EnvTypes::STRING => "String"
		});
	}
	
	/*
	 * Get variable value.
	 *
	 * @access Public
	 *
	 * @return Mixed
	 */
	public function getValue(): Mixed
	{
		return( $this )->value;
	}
	
	/*
	 * Get variable value reference.
	 *
	 * @access Public
	 *
	 * @return Yume\Fure\Support\Data\DataInterface
	 */
	public function getValueReference(): Data\DataInterface
	{
		return( $this )->valueReference;
	}
	
	/*
	 * Return if environment variable has single comment.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function hasComment(): Bool
	{
		return( $this->comment !== Null );
	}
	
	/*
	 * Return if environment variable has commented.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function hasCommented(): Bool
	{
		return( $this->commented );
	}
	
	/*
	 * Return if environment variable has multiple comments.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function hasMultipleComments(): Bool
	{
		return( $this->comments !== Null && count( $this->comments ) >= 1 );
	}
	
	/*
	 * Return if environment variable has raw.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function hasRaw(): Bool
	{
		return( $this->raw !== Null );
	}
	
	/*
	 * Return if environment variable has referenced.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function hasReferenced(): Bool
	{
		return( $this->referenced->count() >= 1 );
	}
	
	/*
	 * Return if environment variable has type.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function hasType(): Bool
	{
		return( $this->type !== EnvTypes::NONE );
	}
	
	/*
	 * Return if environment variable has value.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function hasValue(): Bool
	{
		return( $this->hasType() );
	}
	
	/*
	 * Return if environment variable has value reference.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function hasValueReference(): Bool
	{
		return( $this->valueReference->count() >= 1 );
	}
	
	/*
	 * Return if environment variable value has quoted.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function isQuoted(): Bool
	{
		return( $this )->quoted;
	}
	
	/*
	 * Return if environment variable is unset.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function isUnset(): Bool
	{
		return( $this )->unset;
	}
	
	public function setAsUnset(): Void
	{
		$this->unset = True;
	}
	
	/*
	 * Set environment variable .
	 *
	 * @access Public
	 *
	 * @params String $comment
	 *
	 * @return Yume\Fure\Util\Env\EnvVariable
	 */
	public function setComment( ? String $comment ): EnvVariable
	{
		return([ $this, $this->comment = $comment ][0]);
	}
	
	/*
	 * Set environment variable .
	 *
	 * @access Public
	 *
	 * @params Bool $commented
	 *
	 * @return Yume\Fure\Util\Env\EnvVariable
	 */
	public function setCommented( Bool $commented ): EnvVariable
	{
		return([ $this, $this->commented = $commented ][0]);
	}
	
	/*
	 * Set environment variable .
	 *
	 * @access Public
	 *
	 * @params Array $comments
	 *
	 * @return Yume\Fure\Util\Env\EnvVariable
	 */
	public function setMultipleComments( ? Array $comments ): EnvVariable
	{
		return([ $this, $this->comments = $comments ][0]);
	}
	
	/*
	 * Set environment variable value for escape quote.
	 *
	 * @access Public
	 *
	 * @params Array $comments
	 *
	 * @return Yume\Fure\Util\Env\EnvVariable
	 */
	public function setQuoted( Bool $quoted ): EnvVariable
	{
		return([ $this, $this->quoted = $quoted ][0]);
	}
	
	/*
	 * Set environment variable .
	 *
	 * @access Public
	 *
	 * @params Array|Bool|Int|String $value
	 *
	 * @return Yume\Fure\Util\Env\EnvVariable
	 */
	public function setValue( Array | Bool | Int | Null | String $value ): EnvVariable
	{
		$this->value = $value;
		$this->type = match( True )
		{
			is_array( $value ) => array_is_list( $value ) ? EnvTypes::LIST : EnvTypes::DICT,
			is_bool( $value ) => EnvTypes::BOOLEAN,
			is_int( $value ) => EnvTypes::NUMBER,
			is_null( $value ) => EnvTypes::NONE,
			is_string( $value ) => EnvTypes::STRING
		};
		
		return( $this );
	}
	
}

?>