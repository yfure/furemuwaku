<?php

namespace Yume\Fure\Util\Json;

use Yume\Fure\Error;

/*
 * JsonError
 *
 * @package Yume\Fure\Util\Json
 *
 * @extends Yume\Fure\Error\ValueError
 */
class JsonError extends Error\ValueError
{
	
	/*
	 * The maximum stack depth has been exceeded.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const DEPTH_ERROR = JSON_ERROR_DEPTH;
	
	/*
	 * Occurs with underflow or with the modes mismatch.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const STATE_MISMATCH_ERROR = JSON_ERROR_STATE_MISMATCH;
	
	/*
	 * Control character error, possibly incorrectly encoded.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const CTRL_CHAR_ERROR = JSON_ERROR_CTRL_CHAR;
	
	/*
	 * Syntax error.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const SYNTAX_ERROR = JSON_ERROR_SYNTAX;
	
	/*
	 * Malformed UTF-8 characters, possibly incorrectly encoded.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const UTF8_ERROR = JSON_ERROR_UTF8;
	
	/*
	 * Single unpaired UTF-16 surrogate in unicode escape contained in the JSON string passed to json_decode().
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const UTF16_ERROR = JSON_ERROR_UTF16;
	
	/*
	 * The object or array passed to json_encode() include recursive references and cannot be encoded.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const RECURSION_ERROR = JSON_ERROR_RECURSION;
	
	/*
	 * The value passed to json_encode() includes either NAN or INF.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const INF_OR_NAN_ERROR = JSON_ERROR_INF_OR_NAN;
	
	/*
	 * A key starting with \u0000 character was in the string passed to json_decode() when decoding a JSON object into a PHP object.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const INVALID_PROPERTY_NAME_ERROR = JSON_ERROR_INVALID_PROPERTY_NAME;
	
	/*
	 * A value of an unsupported type was given to json_encode(), such as a resource.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const UNSUPPORTED_TYPE_ERROR = JSON_ERROR_UNSUPPORTED_TYPE;
	
}

?>