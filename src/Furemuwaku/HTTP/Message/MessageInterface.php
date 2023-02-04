<?php

namespace Yume\Fure\HTTP\Message;

use Yume\Fure\HTTP\Stream;

/*
 * MessageInterface
 *
 * @package Yume\Fure\HTTP\Message
 */
interface MessageInterface
{
	
	/*
	 * Retrieves a message header value by the given case-insensitive name.
	 * Case-insensitive header field name.
	 * 
	 * @access Public
	 * 
	 * @params String $name
	 * 
	 * @return Array<String>
	 */
	public function getHeader( String $name ): Array;
	
	/*
	 * Retrieves a comma-separated string of the values for a single header.
	 * Case-insensitive header field name.
	 * 
	 * @access Public
	 * 
	 * @params String $name
	 * 
	 * @return String
	 */
	public function getHeaderLine( String $name ): String;
	
	/*
	 * Retrieves all message header values.
	 *
	 * @access Public
	 * 
	 * @return Array<String[String]>
	 */
	public function getHeaders(): Array;
	
	/*
	 * Gets the body of the message.
	 *
	 * @access Public
	 * 
	 * @return Yume\Fure\HTTP\Stream\StreamInterface
	 */
	public function getBody(): Stream\StreamInterface;
	
	/*
	 * Retrieves the HTTP protocol version as a string.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getProtocolVersion(): String;
	
	/*
	 * Checks if a header exists by the given case-insensitive name.
	 * Case-insensitive header field name.
	 *
	 * @access Public
	 * 
	 * @params String $name
	 * 
	 * @return Bool
	 */
	public function hasHeader( $name ): Bool;
	
	/*
	 * Return an instance with the specified header appended with the given value.
	 * Case-insensitive header field name to add.
	 *
	 * @access Public
	 * 
	 * @params String $name
	 * @params Array<String>|String $value
	 * 
	 * @return Yume\Fure\HTTP\Message\MessageInterface
	 * 
	 * @throws Yume\Fure\HTTP\Message\MessageError
	 */
	public function withAddedHeader( String $name, Array | String $value ): MessageInterface;
	
	/*
	 * Return an instance with the specified message body.
	 *
	 * @access Public
	 * 
	 * @params Yume\Fure\HTTP\Stream\StreamInterface $body
	 * 
	 * @return Yume\Fure\HTTP\Message\MessageInterface
	 * 
	 * @throws Yume\Fure\HTTP\Message\MessageError
	 */
	public function withBody( Stream\StreamInterface $body ): MessageInterface;
	
	/*
	 * Return an instance with the provided value replacing the specified header.
	 * Case-insensitive header field name.
	 * 
	 * @access Public
	 * 
	 * @params String $name
	 * @params Array<String>|String $value
	 * 
	 * @return Yume\Fure\HTTP\Message\MessageInterface
	 * 
	 * @throws Yume\Fure\HTTP\Message\MessageError
	 */
	public function withHeader( String $name, Array | String $value ): MessageInterface;
	
	/*
	 * Return an instance with the specified HTTP protocol version.
	 *
	 * @access Public
	 * 
	 * @params String $version
	 * 
	 * @return Yume\Fure\HTTP\Message\MessageInterface
	 */
	public function withProtocolVersion( String $version ): MessageInterface;
	
	/*
	 * Return an instance without the specified header.
	 * Case-insensitive header field name to remove.
	 * 
	 * @access Public
	 *
	 * @params String $name
	 * 
	 * @return Yume\Fure\HTTP\Message\MessageInterface
	 */
	public function withoutHeader( String $name ): MessageInterface;
	
}

?>