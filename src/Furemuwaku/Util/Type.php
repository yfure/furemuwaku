<?php

namespace Yume\Fure\Util;

/*
 * Type
 *
 * @package Yume\Fure\Util
 */
enum Type: Int
{
	
	case Array = 0;
	case Bool = 1;
	case Float = 2;
	case Int = 3;
	case Json = 4;
	case Mixed = 5;
	case None = 6;
	case Object = 7;
	case Raw = 8;
	case String = 9;
	
}

?>