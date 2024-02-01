<?php

namespace Yume\Fure\Http\Uri;

use Stringable;

/*
 * UriInterface
 * 
 * @package Yume\Fure\Http\Uri
 */
interface UriInterface extends Stringable {

	/*
	 * Return the URI Scheme.
	 * 
	 * @access Public
	 * 
	 * @return String
	 */
	public function getScheme(): String;

	/*
	 * Return the URI Authority.
	 * 
	 * @access Public
	 * 
	 * @return String
	 */
	public function getAuthority(): String;

	/*
	 * Return the URI User Info.
	 * 
	 * @access Public
	 * 
	 * @return String
	 */
	public function getUserInfo(): String;

	/*
	 * Return the URI Hostname.
	 * 
	 * @access Public
	 * 
	 * @return String
	 */
	public function getHost(): String;

	/*
	 * Return the URI Port Number.
	 * 
	 * @access Public
	 * 
	 * @return Int
	 */
	public function getPort(): ?Int;

	/*
	 * Return the URI Pathname.
	 * 
	 * @access Public
	 * 
	 * @return String
	 */
	public function getPath(): String;

	/*
	 * Return the URI Query String.
	 * 
	 * @access Public
	 * 
	 * @return String
	 */
	public function getQuery(): String;

	/*
	 * Return the URI Fragment.
	 * 
	 * @access Public
	 * 
	 * @return String
	 */
	public function getFragment(): String;

	/*
	 * Return new URI Instance with Specific HTTP Scheme.
	 * 
	 * @access Public
	 * 
	 * @params String $scheme
	 * 
	 * @return Yume\Fure\Http\Uri\UriInterface
	 */
	public function withScheme( String $scheme ): UriInterface;

	/*
	 * Return new URI Instance with Specific User Info.
	 * 
	 * @access Public
	 * 
	 * @params String $username
	 * @params String $password
	 * 
	 * @return Yume\Fure\Http\Uri\UriInterface
	 */
	public function withUserInfo( String $username, ?String $password = Null ): UriInterface;

	/*
	 * Return new URI Instance with Specific Hostname.
	 * 
	 * @access Public
	 * 
	 * @params String $host
	 * 
	 * @return Yume\Fure\Http\Uri\UriInterface
	 */
	public function withHost( String $host ): UriInterface;

	/*
	 * Return new URI Instance with Specific Post NUmber.
	 * 
	 * @access Public
	 * 
	 * @params Int $port
	 * 
	 * @return Yume\Fure\Http\Uri\UriInterface
	 */
	public function withPort( ?Int $port ): UriInterface;

	/*
	 * Return new URI INstance with Specific Pathname.
	 * 
	 * @access Public
	 * 
	 * @params String $path
	 * 
	 * @return Yume\Fure\Http\Uri\UriInterface
	 */
	public function withPath( String $path ): UriInterface;

	/*
	 * Return new URI Instance with Specific Query String.
	 * 
	 * @access Public
	 * 
	 * @params String $query
	 * 
	 * @return Yume\Fure\Http\Uri\UriInterface
	 */
	public function withQuery( String $query ): UriInterface;

	/*
	 * Return new URI Instance with Specific Fragment.
	 * 
	 * @access Public
	 * 
	 * @params String $fragment
	 *  Fragment name.
	 * 
	 * @return Yume\Fure\Http\Uri\UriInterface
	 */
	public function withFragment( String $fragment ): UriInterface;

	/*
	 * Return the string of representation as a URI reference.
	 * 
	 * @access Public
	 * 
	 * @return String
	 */
	public function __toString(): string;

}

?>