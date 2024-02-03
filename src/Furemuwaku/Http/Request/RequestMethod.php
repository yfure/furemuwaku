<?php

namespace Yume\Fure\Http\Request;

/*
 * RequestMethod
 * 
 * HTTP Request Methods
 * 
 * @package Yume\Fure\Http\Request
 */
enum RequestMethod: String {

	/** Http Request Method CONNECT */
	case CONNECT = "CONNECT";
	
	/** Http Request Method DELETE */
	case DELETE = "DELETE";

	/** Http Request Method GET */
	case GET = "GET";

	/** Http Request Method Head */
	case HEAD = "HEAD";

	/** Http Request Method OPTIONS */
	case OPTIONS = "OPTIONS";
	
	/** Http Request Method PURGE */
	case PURGE = "PURGE";

	/** Http Request Method PATCH */
	case PATCH = "PATCH";

	/** Http Request Method POSt */
	case POST = "POST";

	/** Http Request Method PUT */
	case PUT = "PUT";

	/** Http Request Method TRACE */
	case TRACE = "TRACE";

	use \Yume\Fure\Util\Backed;

	/*
	 * Get enum case by request method name
	 * 
	 * The request method name must be insensitive case.
	 * 
	 * @access Public Static
	 * 
	 * @params String $method
	 *  The request method name
	 * 
	 * @return Static
	 */
	public static function method( String $method ): ?Static {
		$method = strtolower( $method );
		foreach( self::names() As $name ) {
			if( strtolower( $name ) === $method ) {
				return self::from( $name );
			}
		}
		return Null;
	}

}

?>