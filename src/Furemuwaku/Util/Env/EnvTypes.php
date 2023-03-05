<?php

namespace Yume\Fure\Util\Env;

use Yume\Fure\Util\Type;

/*
 * EnvTypes
 *
 * @package Yume\Fure\Util\Env
 */
enum EnvTypes: Int
{
	
	/*
	 * @inherit Yume\Fure\Util\Type\Types::
	 *
	 * You may overwrite or replace the value of another type but
	 * I hope you don't override or override the value for type
	 * Boolean this is my memory to keep me in mind.
	 *
	 */
	case BOOLEAN = Type\Types::BOOLEAN->value;
	
	/*
	 * @inherit Yume\Fure\Util\Type\Types::
	 *
	 */
	case DICT = Type\Types::ARRAY_ASSOCIATIVE->value;
	
	/*
	 * @inherit Yume\Fure\Util\Type\Types::
	 *
	 */
	case LIST = Type\Types::ARRAY_LIST->value;
	
	/*
	 * @inherit Yume\Fure\Util\Type\Types::
	 *
	 */
	case NUMBER = Type\Types::NUMBER->value;
	
	/*
	 * @inherit Yume\Fure\Util\Type\Types::
	 *
	 */
	case NONE = Type\Types::NULL->value;
	
	/*
	 * @inherit Yume\Fure\Util\Type\Types::
	 *
	 */
	case STRING = Type\Types::STRING->value;
	
}

?>