<?php

namespace Yume\Fure\CLI\Command;

use Stringable;

use Yume\Fure\Util;

/*
 * CommandOption
 *
 * @package Yume\Fure\CLI\Command
 */
class CommandOption implements Stringable
{
	
	/*
	 * Construct method of class CommandOption.
	 *
	 * @access Public Instance
	 *
	 * @params Public Readonly String $name
	 * @params Public Readonly String $alias
	 * @params Public Readonly String $about
	 * @params Public Readonly Mixed $default
	 * @params Public Readonly Yume\Fure\CLI\Command\CommandInterface $command
	 * @params Public Readonly Yume\Fure\Util\Types $type
	 * @params Public Readonly Bool $long
	 * @params Public Readonly Bool $required
	 * @params Public Readonly Array<String> $requiredWith
	 *
	 * @return Void
	 */
	public function __construct(
		public Readonly String $name,
		public Readonly ? String $alias,
		public Readonly ? String $about,
		public Readonly Mixed $default,
		public Readonly CommandInterface $command,
		public Readonly Util\Types $type,
		public Readonly Bool $long,
		public Readonly Bool $required,
		public Readonly Array $requiredWith )
	{}
	
	/*
	 * Parse class into string.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function __toString(): String
	{
		// If command has alias name.
		if( $this->alias )
		{
			$format = "{}{} [{}] | {}{} [{2}]";
			$alias = $this->alias;
		}
		return( f( $format ?? "{}{} [{}]", $this->long ? "--" : "-", $this->name, $this->type->name, strlen( $alias ?? "" ) > 1 ? "--" : "-", $alias ?? "" ) );
	}
	
	/*
	 * Return if option has type.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function hasType(): Bool
	{
		return( $this->type !== Util\Types::MIXED && $this->type !== Util\Types::NULL );
	}
	
	/*
	 * Return if option has default value.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function isDefaultValueAvailable(): Bool
	{
		return( $this )->default !== Null;
	}
	
	/*
	 * Return if option long option.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function isLong(): Bool
	{
		return( $this )->long;
	}
	
	/*
	 * Return if option is required.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function isRequired(): Bool
	{
		return( $this )->required;
	}
	
	/*
	 * Return if option is required another option.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function isRequiredWith( Null | String | CommandOption $command = Null ): Bool
	{
		if( $command !== Null )
		{
			if( $command Instanceof CommandOption )
			{
				$command = $command->name;
			}
			return( isset( $this->requireWith[$command] ) );
		}
		return( count( $this->requiredWith ) > 0 );
	}
	
}

?>