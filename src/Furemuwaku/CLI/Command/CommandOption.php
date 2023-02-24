<?php

namespace Yume\Fure\CLI\Command;

use Yume\Fure\Util;

/*
 * CommandOption
 *
 * @package Yume\Fure\CLI\Command
 */
class CommandOption
{
	
	public function __construct(
		public Readonly String $name,
		public Readonly Mixed $type,
		public Readonly Bool $long,
		public Readonly Bool $required,
		public Readonly Array $requiredWith
	)
	{
		puts( "{}\n", Util\Types::CHAR->allow( "*" ) );
	}
	
}

?>