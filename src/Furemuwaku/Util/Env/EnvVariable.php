<?php

namespace Yume\Fure\Util\Env;

use Yume\Fure\Util;

/*
 * EnvVariable
 *
 * @package Yume\Fure\Util\Env
 */
class EnvVariable
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
		public Readonly Bool $system = False,
		protected ? String $quoted = Null,
		protected ? String $raw = Null
	)
	{}
	
	public function isCommented( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $optional === $this->commented : $this->commented );
	}
	
	public function isQuoted( ? Bool $optional = Null ): Bool
	{}
	
	public function getComment(): ? String
	{}
	
	public function getComments(): ? Array
	{}
	
	public function getValue(): Mixed
	{}
	
	public function hasComment( ? Bool $optional = Null ): Bool
	{}
	
	public function hasComments( ? Bool $optional = Null ): Bool
	{}
	
}

?>