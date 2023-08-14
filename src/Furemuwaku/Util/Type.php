<?php

namespace Yume\Fure\Util;

/*
 * Type
 *
 * @package Yume\Fure\Util
 */
enum Type: Int
{
	
	use \Yume\Fure\Util\Backed;
	
	case Array = 162928;
	case Bool = 178755;
	case Double = 278672;
	case Float = 289644;
	case Int = 345778;
	case Integer = 353245;
	case Json = 498544;
	case Mixed = 565456;
	case None = 656557;
	case Object = 757644;
	case Raw = 877546;
	case String = 964346;
	
}

?>