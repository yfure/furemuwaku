<?php

namespace Yume\Fure\Util\Env;

use Yume\Fure\Util;
use Yume\Fure\Util\Json;
use Yume\Fure\Util\RegExp;

/*
 * EnvVariable
 *
 * @package Yume\Fure\Util\Env
 */
final class EnvVariable implements EnvVariableInterface {
	
	/*
	 * Construct method of class EnvVariable.
	 *
	 * @access Public Initialize
	 *
	 * @params String $name
	 *  Environment variable name.
	 * @params Mixed $value
	 *  Environment variable value.
	 * @params Yume\Fure\Util\Type $type
	 *  Environment variable value type.
	 * @params String $comment
	 *  Environment variable single comment (AFTER).
	 * @params Array $comments
	 *  Environment variable multiline comments (BEFORE).
	 *  Commented variables are not included.
	 * @params Bool $commented
	 *  Environment variable commented.
	 * @params Int $line
	 *  Environment variable line number.
	 * @params String $endline
	 *  Environment variable end of line.
	 * @params Bool $system
	 *  Environment variable is builtin system.
	 * @params Bool $quoted
	 *  Environment variable is quoted string.
	 * @params String $raw
	 *  Environment variable raw syntax.
	 *
	 * @return Void
	 */
	public function __construct(
		public Readonly String $name,
		protected Mixed $value = Null,
		protected Util\Type $type = Util\Type::Mixed,
		protected ? String $comment = Null,
		protected ? Array $comments = Null,
		protected Bool $commented = False,
		protected ? Int $line = Null,
		protected ? String $endline = Null,
		public Readonly Bool $system = False,
		protected ? String $quoted = Null,
		protected ? String $raw = Null
	) {}
	
	/*
	 * Convert class to String.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function __toString(): String {
		return $this->makeRaw();
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->getComment
	 * 
	 */
	public function getComment(): ? String {
		return $this->comment;
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->getComments
	 * 
	 */
	public function getComments(): ? Array {
		return $this->comments;
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->getEOL
	 * 
	 */
	public function getEOL(): ? String {
		return $this->endline;
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->getLine
	 * 
	 */
	public function getLine(): ? Int {
		return $this->line;
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->getQuote
	 * 
	 */
	public function getQuote(): ? String {
		return $this->quoted;
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->getRaw
	 * 
	 */
	public function getRaw(): ? String {
		return $this->raw;
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->getType
	 * 
	 */
	public function getType(): Util\Type {
		return $this->type;
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->getTypeAsString
	 * 
	 */
	public function getTypeAsString(): String {
		return $this->type->name;
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->getValue
	 * 
	 */
	public function getValue(): Mixed {
		return $this->value;
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->hasComment
	 * 
	 */
	public function hasComment( ? Bool $optional = Null ): Bool {
		return $optional !== Null ? $optional === $this->hasComment() : valueIsNotEmpty( $this->comment );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->hasComments
	 * 
	 */
	public function hasComments( ? Bool $optional = Null ): Bool {
		return $optional !== Null ? $optional === $this->hasComments() : valueIsNotEmpty( $this->comments );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->hasEOL
	 * 
	 */
	public function hasEOL( ? Bool $optional = Null ): Bool {
		return $optional !== Null ? $optional === $this->hasEOL() : valueIsNotEmpty( $this->endline );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->hasLine
	 * 
	 */
	public function hasLine( ? Bool $optional = Null ): Bool {
		return $optional !== Null ? $optional === $this->hasLine() : $this->line !== Null && $this->line >= 1;
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->hasRaw
	 * 
	 */
	public function hasRaw( ? Bool $optional = Null ): Bool {
		return $optional !== Null ? $optional === $this->hasRaw() : valueIsNotEmpty( $this->raw );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->hasValue
	 * 
	 */
	public function hasValue( ? Bool $optional = Null ): Bool {
		return $optional !== Null ? $optional === $this->hasValue() : valueIsNotEmpty( $this->value );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->isCommented
	 * 
	 */
	public function isCommented( ? Bool $optional = Null ): Bool {
		return $optional !== Null ? $optional === $this->commented : $this->commented;
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->isQuoted
	 * 
	 */
	public function isQuoted( ? Bool $optional = Null ): Bool {
		return $optional !== Null ? $optional === $this->isQuoted() : $this->quoted !== Null;
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->isSystem
	 * 
	 */
	public function isSystem( ? Bool $optional = Null ): Bool {
		return $optional !== Null ? $optional === $this->isSystem() : $this->system;
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->makeRaw
	 * 
	 */
	public function makeRaw(): String {
		$format = "{ comments }{ commented }{ type }{ name } = { value };{ comment }";
		$params = [
			"name" => $this->name,
			"type" => $this->type,
			"value" => $this->value ?? "",
			"comment" => "",
			"comments" => "",
			"commented" => ""
		];
		if( $this->hasValue() && $this->isCommented( False ) ) {
			$params['value'] = match( $this->type ) {
				Util\Type::Bool => $this->value ? "True": "False",
				Util\Type::Array => Json\Json::encode( $this->value, JSON_INVALID_UTF8_SUBSTITUTE ),
				Util\Type::None => "",
				Util\Type::Int => ( String ) $this->value,
				Util\Type::String => $this->value,
				default => $this->value
			};
			if( $this->type === Util\Type::String ) {
				$params['value'] = RegExp\RegExp::replace( "/(\\$|\\#|\"|\'|\;|\\\)/ms", $params['value'], "\x5c\x5c\$1" );
				if( $this->isQuoted() ) {
					$params['value'] = f( "{0}{1}{0}", $this->getQuote(), $params['value'] );
				}
			}
			// $this->valueReference->map( function( $i, $name, $var ) use( &$params ) {
			// 	$valueFromReference = match( $var->getType() ) {
			// 		Util\Type::Bool => $var->getValue() ? "True": "False",
			// 		Util\Type::Array => Json\Json::encode( $var->getValue(), JSON_INVALID_UTF8_SUBSTITUTE ),
			// 		Util\Type::None => "",
			// 		Util\Type::Int => ( String ) $var->getValue(),
			// 		Util\Type::String => $var->getValue()
			// 	};
			// 	$params['value'] = str_replace( RegExp\RegExp::replace( "/(\\$|\\#|\"|\'|\;|\\\)/ms", $valueFromReference, "\x5c\x5c\$1" ), Type\Str::fmt( "\${}", $var->getName() ), $params['value'] );
			// });
		}
		$params['type'] = $params['type'] !== Util\Type::None ? f( "{} ", $params['type']->name ) : "";
		if( $this->hasComment() ) {
			$params['comment'] = f( " # {}", $this->comment );
		}
		else {
			if( $this->name === "RESPONSE_CHARSET" ) {
				echo dump( $this, True );
			}
		}
		if( $this->hasComments() ) {
			$params['comments'] = f( "# {}\n", implode( "\n# ", $this->comments ) );
		}
		if( $this->isCommented() ) {
			$params['commented'] = "# ";
		}
		return f( $format, ...$params );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Env\EnvVariableInterface->setValue
	 * 
	 */
	public function setValue( Mixed $value ): Static {
		if( is_string( $value ) ) {
			switch( True ) {
				case Util\Strings::isSerialized( $value ):
					$type = Util\Type::Raw;
					break;
				case Util\Strings::isJson( $value ):
					$type = Util\Type::Json;
					break;
				case Util\Strings::isQuoted( $value, $match ):
					$quoted = $match['quote'];
					$value = $match['value'];
				default:
					$type = Util\Type::String;
					break;
			}
			$this->type = $type;
			$this->value = $value;
			$this->quoted = $quoted ?? Null;
		}
		else {
			$this->value = $value;
			$this->type = match( True ) {
				is_array( $value ) => Util\Type::Array,
				is_bool( $value ) => Util\Type::Bool,
				is_double( $value ),
				is_float( $value ) => Util\Type::Float,
				is_int( $value ) => Util\Type::Int,
				is_object( $value ) => Util\Type::Object,
				
				/*
				 * Since the null type cannot be defined,
				 * we replace it with Mixed.
				 *
				 */
				is_null( $value ) => Util\Type::Mixed,
				
				/** @default Mixed */
				default => Util\Type::Mixed
			};
		}
		return $this;
	}
	
}
