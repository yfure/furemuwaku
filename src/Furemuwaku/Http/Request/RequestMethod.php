<?php

namespace Yume\Fure\Http\Request;

/*
 * RequestMethod
 * 
 * HTTP Request Methods
 * 
 * @package Yume\Fure\Http\Request
 */
enum RequestMethod: String
{

	case HEAD    = "HEAD";
    case GET     = "GET";
    case POST    = "POST";
    case PUT     = "PUT";
    case PATCH   = "PATCH";
    case DELETE  = "DELETE";
    case PURGE   = "PURGE";
    case OPTIONS = "OPTIONS";
    case TRACE   = "TRACE";
    case CONNECT = "CONNECT";

	use \Yume\Fure\Util\Backed;

}

?>