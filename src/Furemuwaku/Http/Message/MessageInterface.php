<?php

namespace Yume\Fure\Http\Message;

use Yume\Fure\Http\Header;
use Yume\Fure\IO\Stream;

interface MessageInterface {

	public function getProtocolVersion(): String;
	public function withProtocolVersion( String $version ): MessageInterface;
	public function getHeaders(): Header\Headers;
	public function hasHeader( String $name ): Bool;
	public function getHeader( String $name ): Array;
	public function getHeaderLine( String $name ): String;
	public function withHeader( String $name, Mixed $value ): MessageInterface;
	public function withAddedHeader( String $name, Mixed $value ): MessageInterface;
	public function withoutHeader( String $name ): MessageInterface;
	public function getBody(): Stream\StreamInterface;
	public function withBody( Stream\StreamInterface $body ): MessageInterface;


}

?>