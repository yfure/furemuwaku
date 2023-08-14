<?php

namespace Yume\Fure\Util\Env;

use Yume\Fure\Util;

/*
 * EnvVariable
 *
 * @package Yume\Fure\Util\Env
 */
final class EnvVariable implements EnvVariableInterface
{
	
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
	)
	{}
	
	/*
	 * Convert class to String.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function __toString(): String
	{
		return( $this )->makeRaw();
	}
	
	/*
	 * Return single line comment after defined variable.
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
	 * Return multiline comments before define variable.
	 *
	 * @access Public
	 *
	 * @return Array
	 */
	public function getComments(): ? Array
	{
		return( $this )->comments;
	}
	
	public function getEOL(): ? String
	{
		return( $this )->endline;
	}
	
	public function getLine(): ? Int
	{
		return( $this )->line;
	}
	
	public function getQuote(): ? String
	{
		return( $this )->quoted;
	}
	
	/*
	 * Return variable as raw.
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
	 * Return variable type.
	 *
	 * @access Public
	 *
	 * @return Yume\Fure\Util\Type
	 */
	public function getType(): Util\Type
	{
		return( $this )->type;
	}
	
	/*
	 * Return variable type as String.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getTypeAsString(): String
	{
		return( $this )->type->name;
	}
	
	/*
	 * Return value of variable.
	 *
	 * @access Public
	 *
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public function getValue(): Mixed
	{
		return( $this )->value;
	}
	
	/*
	 * Return if variable has single line comment.
	 *
	 * @access Public
	 *
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public function hasComment( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $optional === $this->hasComment() : valueIsNotEmpty( $this->comment ) );
	}
	
	/*
	 * Return if variable has multiline comments.
	 *
	 * @access Public
	 *
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public function hasComments( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $optional === $this->hasComments() : valueIsNotEmpty( $this->comments ) );
	}
	
	public function hasEOL( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $optional === $this->hasEOL() : valueIsNotEmpty( $this->endline ) );
	}
	
	public function hasLine( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $optional === $this->hasLine() : $this->line !== Null && $this->line >= 1 );
	}
	
	public function hasRaw( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $optional === $this->hasRaw() : valueIsNotEmpty( $this->raw ) );
	}
	
	public function hasValue( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $optional === $this->hasValue() : valueIsNotEmpty( $this->value ) );
	}
	
	/*
	 * Return if variable is commented.
	 *
	 * @access Public
	 *
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public function isCommented( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $optional === $this->commented : $this->commented );
	}
	
	/*
	 * Return if value of variable is quoted.
	 *
	 * @access Public
	 *
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public function isQuoted( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $optional === $this->isQuoted() : $this->quoted !== Null );
	}
	
	/*
	 * Return if variable is declared by system.
	 *
	 * @access Public
	 *
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public function isSystem( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $optional === $this->isSystem() : $this->system );
	}
	
	/*
	 * Returns a raw variable declaration.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function makeRaw(): String
	{
		throw new \Yume\Fure\Error\MethodError( "Not Implemented" );
	}
	
	/*
	 * Set variable value.
	 *
	 * @access Public
	 *
	 * @params Mixed $value
	 *
	 * @return Static
	 */
	public function setValue( Mixed $value ): Static
	{
		if( is_string( $value ) )
		{
			switch( True )
			{
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
			$this->type = match( True )
			{
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
		return( $this );
	}
	
}

?>