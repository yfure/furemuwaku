<?php

namespace Yume\Fure\Http\Uri;

/*
 * UriPort
 * 
 * @package Yume\Fure\Http\Uri
 */
enum UriPort: Int {

	case HTTP = 80;
	case HTTPS = 443;
	case FTP = 21;
	case GOPHER = 70;
	case NNTP = 119;
	case NEWS = 119;
	case TELNET = 23;
	case TN3270 = 23;
	case IMAP = 143;
	case POP = 110;
	case LDAP = 389;

	use \Yume\Fure\Util\Backed;

}

?>