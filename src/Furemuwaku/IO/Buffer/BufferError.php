<?php

namespace Yume\Fure\IO\Buffer;

use Yume\Fure\Error;

/*
 * BufferError
 *
 * @extends Yume\Fure\Error\IOError
 *
 * @package Yume\Fure\IO\Buffer
 */
class BufferError extends Error\IOError
{
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		BufferError::class => [
		]
	];
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $track = [
		__NAMESPACE__ => [
			"classes" => [
				Buffer::class
			],
			"function" => [
				"ob_clean",
				"ob_end_clean",
				"ob_end_flush",
				"ob_flush",
				"ob_get_contents",
				"ob_get_flush",
				"ob_get_length",
				"ob_get_level",
				"ob_get_status",
				"ob_gzhandler"
			]
		]
	];
	
}

?>