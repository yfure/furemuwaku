<?php

namespace Yume\Fure\Http\Request;

use Yume\Fure\Http\Uri;
use Yume\Fure\Http\Message;

/*
 * RequestInterface
 * 
 * @package Yume\Fure\Http\Request
 */
interface RequestInterface extends Message\MessageInterface {

	public function getRequestTarget(): String;
	public function withRequestTarget( String $requestTarget ): RequestInterface;
	public function getMethod(): String;
	public function withMethod( String $method ): RequestInterface;
	public function getUri(): Uri\UriInterface;
	public function withUri( Uri\UriInterface $uri, Bool $preserveHost=False ): RequestInterface;
	
}

?>