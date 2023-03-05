<?php

namespace Yume\Fure\HTTP\IP;

/*
 * IP
 *
 * @package Yume\Fure\HTTP\IP
 */
final class IPAddress
{
	
	public static function isLocale( String $ip ): Bool
	{
		return( preg_match( "/^(?:127(?:\.[0-9]{1,3}){3}|(?:10|172\.(?:1[6-9]|2[0-9]|3[01])|192\.168)(?:\.[0-9]{1,3}){2}|(?:[fF][eE][89ab]|[fF][cCdD])(?::[0-9A-Fa-f]{1,4}){6})|(?:[fF][cCdD]00:(?::0){1,3}:|(?:[0-9A-Fa-f]{1,4}:){6}(?:[0-9A-Fa-f]{1,4}|(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)|\blocalhost\b))$/", $ip ) );
	}
	
	public static function isIPv4( String $ip ): Bool
	{
		return( preg_match( "/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9]{1,2})\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9]{1,2})$/", $ip ) );
	}
	
	public static function isIPv6( String $ip ): Bool
	{
		return( preg_match( "/^(?:(?:[0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4}|(?:[0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4}|(?:[0-9A-Fa-f]{1,4}:){5}(?::[0-9A-Fa-f]{1,4}){1,2}|(?:[0-9A-Fa-f]{1,4}:){4}(?::[0-9A-Fa-f]{1,4}){1,3}|(?:[0-9A-Fa-f]{1,4}:){3}(?::[0-9A-Fa-f]{1,4}){1,4}|(?:[0-9A-Fa-f]{1,4}:){2}(?::[0-9A-Fa-f]{1,4}){1,5}|[0-9A-Fa-f]{1,4}:(?::[0-9A-Fa-f]{1,4}){1,6}|:(?::[0-9A-Fa-f]{1,4}){1,7})$/", $ip ) );
	}
	
	public static function valid( String $ip ): Bool
	{
		return(
			self::isIPv4( $ip ) ||
			self::isIPv6( $ip ) ||
			self::isLocale( $ip )
		);
	}
	
}

?>