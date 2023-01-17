<?php

namespace Yume\Fure\Util\Env;

/*
 * EnvTypes
 *
 * @package Yume\Fure\Util\Env
 */
enum EnvTypes: Int
{
	
	/*
	 * Constant for Environment value Boolean type.
	 *
	 * You may overwrite or replace the value of another type but
	 * I hope you don't override or override the value for type
	 * Boolean this is my memory to keep me in mind.
	 *
	 * @values Int
	 */
	case BOOLEAN = 2375;
	
	/*
	 * Constant for Environment value Array<Associative|Multidimension> type.
	 *
	 * @values Int
	 */
	case DICT = 24734;
	
	/*
	 * Constant for Environment value Array<Indexed> type.
	 *
	 * @values Int
	 */
	case LIST = 24857;
	
	/*
	 * Constant for Environment value Number type.
	 *
	 * @values Int
	 */
	case NUMBER = 25358;
	
	/*
	 * Constant for Environment value None type.
	 *
	 * @values Int
	 */
	case NONE = 26572;
	
	/*
	 * Constant for Environment value String type.
	 *
	 * @values Int
	 */
	case STRING = 27826;
	
}

?>