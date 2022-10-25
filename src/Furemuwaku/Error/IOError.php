<?php

namespace Yume\Fure\Error;

/*
 * IOError
 *
 * @extends Yume\Fure\Error\BaseError
 *
 * @package Yume\Fure\Error
 */
class IOError extends BaseError
{
	
	/*
	 * If input error.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const INPUT_ERROR = 7643;
	
	/*
	 * If output error.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const OUTPUT_ERROR = 7648;
	
}

?>