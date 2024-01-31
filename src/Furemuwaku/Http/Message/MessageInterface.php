<?php

namespace Yume\Fure\Http\Message;

use Yume\Fure\Http\Header;
use Yume\Fure\IO\Stream;

/*
 * MessageInterface
 * 
 * @package Yume\Fure\Http\Message
 */
interface MessageInterface {

	/*
	 * Return http protocol message version.
	 * 
	 * @access Public
	 * 
	 * @return String
	 */
	public function getProtocolVersion(): String;

	/*
	 * Return new Message Instance with Specific Protocol Version.
	 * 
	 * @access Public
	 * 
	 * @params String $version
	 * 
	 * @return Yume\Fure\Http\Message\MessageInterface
	 */
	public function withProtocolVersion( String $version ): MessageInterface;
	
	/*
	 * Return all message header values.
	 * 
	 * @access Public
	 * 
	 * @return Yume\Fure\Http\Header\Headers
	 */
	public function getHeaders(): Header\Headers;

	/*
	 * Return if header exists.
	 * 
	 * @access Public
	 * 
	 * @params String $name
	 * 
	 * @return Bool
	 */ 
	public function hasHeader( String $name ): Bool;
	
	/*
	 * Return header values by name.
	 * 
	 * @access Public
	 * 
	 * @params String $name
	 * 
	 * @return Array
	 */
	public function getHeader( String $name ): Array;

	/*
	 * Return comma sperarated header values.
	 * 
	 * @access Public
	 * 
	 * @return String
	 */
	public function getHeaderLine( String $name ): String;

	/*
	 * Return new Message Instance with Specific Header.
	 * 
	 * @access Public
	 * 
	 * @params String $name
	 * 
	 * @return Yume\Fure\Http\Message\MessageInterface
	 */
	public function withHeader( String $name, Mixed $value ): MessageInterface;
	
	/*
	 * Return new Message Instance with Specific Header appended with the given value.
	 * 
	 * @access Public
	 * 
	 * @params String $name
	 * 
	 * @return Yume\Fure\Http\Message\MessageInterface
	 */
	public function withAddedHeader( String $name, Mixed $value ): MessageInterface;

	/*
	 * Return new Message Instance with Removed Specific Header.
	 * 
	 * @access Public
	 * 
	 * @params String $name
	 * 
	 * @return Yume\Fure\Http\Message\MessageInterface
	 */
	public function withoutHeader( String $name ): MessageInterface;

	/*
	 * Return Stream of body message.
	 * 
	 * @access Public
	 * 
	 * @return Yume\Fure\IO\Stream\StreamInterface
	 */
	public function getBody(): Stream\StreamInterface;

	/*
	 * Return new Message Instance with Specific Message Body.
	 * 
	 * @access Public
	 * 
	 * @params Yume\Fure\IO\Stream\StreamInterface $body
	 * 
	 * @return Yume\Fure\Http\Message\MessageInterface
	 */
	public function withBody( Stream\StreamInterface $body ): MessageInterface;

}

?>