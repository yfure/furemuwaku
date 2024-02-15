<?php

namespace Yume\Fure\Http\Uri;

/*
 * UriNormalize
 * 
 * @package Yume\Fure\Http\Uri
 */
enum UriNormalize: Int {

	/*
	 * Default normalizations option.
	 * 
	 * @access Public Static
	 * 
	 * @values Int
	 */
	case PRESERVING_NORMALIZATIONS =  self::CAPITALIZE_PERCENT_ENCODING->value | self::DECODE_UNRESERVED_CHARACTERS->value | self::CONVERT_EMPTY_PATH->value | self::REMOVE_DEFAULT_HOST->value | self::REMOVE_DEFAULT_PORT->value | self::REMOVE_DOT_SEGMENTS->value;

	/*
	 * All letters within a percent-encoding triplet.
	 *
	 * @access Public Static
	 * 
	 * @values Int
	 */
	case CAPITALIZE_PERCENT_ENCODING = 1;

	/*
	 * Decodes percent-encoded octets of unreserved characters.
	 *
	 * @access Public Static
	 * 
	 * @values Int
	 */
	case DECODE_UNRESERVED_CHARACTERS = 2;

	/*
	 * Converts the empty path to "/" for http and https URIs.
	 *
	 * @access Public Static
	 * 
	 * @values Int
	 */
	case CONVERT_EMPTY_PATH = 4;

	/*
	 * Removes the default host of the given URI scheme from the URI.
	 *
	 * @access Public Static
	 * 
	 * @values Int
	 */
	case REMOVE_DEFAULT_HOST = 8;

	/*
	 * Removes the default port of the given URI scheme from the URI.
	 *
	 * @access Public Static
	 * 
	 * @values Int
	 */
	case REMOVE_DEFAULT_PORT = 16;

	/*
	 * Removes unnecessary dot-segments.
	 *
	 * @access Public Static
	 * 
	 * @values Int
	 */
	case REMOVE_DOT_SEGMENTS = 32;

	/*
	 * Paths which include two or more adjacent slashes are converted to one.
	 *
	 * @access Public Static
	 * 
	 * @values Int
	 */
	case REMOVE_DUPLICATE_SLASHES = 64;

	/*
	 * Sort query parameters with their values in alphabetical order.
	 *
	 * @access Public Static
	 * 
	 * @values Int
	 */
	case SORT_QUERY_PARAMETERS = 128;

	use \Yume\Fure\Util\Backed;

}

?>