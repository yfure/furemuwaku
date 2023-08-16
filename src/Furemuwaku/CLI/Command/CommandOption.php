<?php

namespace Yume\Fure\CLI\Command;

use Yume\Fure\Util;

/*
 * CommandOption
 * 
 * @package Yume\Fure\CLI\Command
 */
final class CommandOption
{

	/*
	 * If option anme is long option.
	 * 
	 * @access Public Readonly
	 * 
	 * @values Bool
	 */
	public Readonly Bool $long;

	/*
	 * Construct method of class CommandOption.
	 * 
	 * @access Public Initialize
	 * 
	 * @params String $name
	 *  Option name.
	 * @params String alias
	 *  Option alias name.
	 * @params Array $explain
	 *  Exaplain of option usage for.
	 * @params Array $example
	 *  Example usage of option.
	 * @params Bool $required
	 *  When the option is required.
	 * @params Arrays<String> $required
	 *  When the option is require anothe options.
	 * @params Mixed $default
	 *  Default value of option.
	 * @params Yume\Fure\Util\Type $type
	 *  Value type of option.
	 * 
	 * @return Void
	 */
	public function __construct(
		public Readonly String $name,
		public Readonly ? String $alias,
		public Readonly Array $explain,
		public Readonly Array $example,
		public Readonly Bool $required,
		public Readonly Array $requires,
		public Readonly Mixed $default,
		public Readonly Util\Type $type )
	{
		$this->long = strlen( $name ) >= 2;
	}

	/*
	 * Return if option has alias name.
	 * 
	 * @access Public
	 * 
	 * @params Bool $optional
	 * 
	 * @return Bool
	 */
	public function hasAlias( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $this->hasAlias() === $optional : valueIsNotEmpty( $this->alias ) );
	}

	/*
	 * Return if optiona has default value.
	 * 
	 * @access Public
	 * 
	 * @params Bool $optional
	 * 
	 * @return Bool
	 */
	public function hasDefaultValue( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $this->hasDefaultValue() === $optional : $this->default !== Null);
	}

	/*
	 * Return if option has specified value type.
	 * 
	 * @access Public
	 * 
	 * @params Bool $optional
	 * 
	 * @return Bool
	 */
	public function hasType( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $this->hasType() === $optional : $this->type !== Util\Type::None && $this->type !== Util\Type::Mixed );
	}

	/*
	 * Return if option is required.
	 * 
	 * @access Public
	 * 
	 * @params Bool $optional
	 * 
	 * @return Bool
	 */
	public function isRequired( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $this->isRequired() === $optional : $this->required === True );
	}

	/*
	 * Return if option is require another option.
	 * 
	 * @access Public
	 * 
	 * @params String|Yume\Fure\CLI\Command\CommandOption $option
	 * @params Bool $optional
	 * 
	 * @return Bool
	 */
	public function isRequiredWith( Null | String | CommandOption $option = Null, ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $this->isRequiredWith( $option ) === $optional : ( $option !== Null ? in_array( $option Instanceof CommandOption ? $option->name : $option, $this->requires ) >= 0 : count( $this->requires ) >= 1 ) );
	}

}

?>