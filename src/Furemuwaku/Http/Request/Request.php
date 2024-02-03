<?php

namespace Yume\Fure\Http\Request;

use Yume\Fure\Error;
use Yume\Fure\Http\Header;
use Yume\Fure\Http\Uri;
use Yume\Fure\IO\Stream;
use Yume\Fure\Util\RegExp;

/*
* Request
* 
* Http Request Implementation
* 
* @package Yume\Fure\Http\Request
*/
class Request implements RequestInterface {

	private String $method;
	private String $target;
	private Uri\UriInterface $uri;

	use \Yume\Fure\Http\Message\MessageTrait;

	/*
	 * Construct method of class Request.
	 * 
	 * @access Public Initialize
	 * 
	 * @params String $method
	 * @params Yume\Fure\Http\Uri\UriInterface $uri
	 * @params Yume\Fure\IO\Stream\StreamInterface $body
	 * @params Yume\Fure\Http\Header\Headers $headers
	 * @params String $protocol
	 * 
	 * @return Void
	 */
	public function __construct( String $method, ?Uri\UriInterface $uri = Null, ?Stream\StreamInterface $body = Null, ?Header\Headers $headers = Null, String $protocol = "1.1" ) {
		if( RequestMethod::method( $method ) === Null ) {
			throw new Error\HttpError( $method, Error\HttpError::METHOD_ERROR );
		}
		$this->uri = $uri;
		$this->body = $body ?? Stream\StreamFactory::create( "" );
		$this->headers = $headers ?? new Header\Headers;
		$this->method = RequestMethod::method( $method )->value;
		$this->protocol = $protocol;
		if( $this->hasHeader( "Host" ) === False ) {
			$this->updateHostnameByUri();
		}
	}

	/*
	 * @inherit Yume\Fure\Http\Request\RequestInterface->getRequestTarget
	 * 
	 */
	public function getRequestTarget(): String {
		$target = $this->target;
		if( valueIsEmpty( $target ) ) {
			$target = $this->uri->getPath();
			if( valueIsEmpty( $target ) ) {
				$target = "/";
			}
			$query = $this->uri->getQuery();
			if( valueIsNotEmpty( $query ) ) {
				$target = f( "{}?{}", $target, $query );
			}
		}
		return $target;
	}

	/*
	 * @inherit Yume\Fure\Http\Request\RequestInterface->withRequestTarget
	 * 
	 */
	public function withRequestTarget( String $requestTarget ): RequestInterface {
		if( RegExp\RegExp::test( "/\s/", $requestTarget ) ) {
			throw new Error\AssertionError( [ "pathname", "not contain white space", "path contain white space" ] );
		}
		$instance = clone $this;
		$instance->target = $requestTarget;
		return $instance;
	}

	/*
	 * @inherit Yume\Fure\Http\Request\RequestInterface->getMethod
	 * 
	 */
	public function getMethod(): String {
		return $this->method;
	}

	/*
	 * @inherit Yume\Fure\Http\Request\RequestInterface->withMethod
	 * 
	 */
	public function withMethod( String $method ): RequestInterface {
		if( $this->method !== $method ) {
			if( RequestMethod::method( $method ) === Null ) {
				throw new Error\HttpError( $method, Error\HttpError::METHOD_ERROR );
			}
			$instance = Clone $this;
			$instance->method = RequestMethod::method( $method )->value;
		}
		return $instance ?? $this;
	}

	/*
	 * @inherit Yume\Fure\Http\Request\RequestInterface->getUri
	 * 
	 */
	public function getUri(): Uri\UriInterface {
		return $this->uri;
	}

	/*
	 * @inherit Yume\Fure\Http\Request\RequestInterface->withUri
	 * 
	 */
	public function withUri( Uri\UriInterface $uri, Bool $preserveHost = False ): RequestInterface {
		if( $this->uri !== $uri ) {
			$instance = Clone $this;
			$instance->uri = $uri;
			if( $this->hasHeader( "host" ) === False || $preserveHost === False ) {
				$this->updateHostnameByUri();
			}
		}
		return $instance ?? $this;
	}

	private function updateHostnameByUri(): Void {
		$host = $this->uri->getHost();
		if( valueIsNotEmpty( $host ) ) {
			$port = $this->uri->getPort();
			if( valueIsNotEmpty( $port ) ) {
				$host = f( "{}:{}", $host, $port );
			}
			$this->headers['Host'] = $host;
		}
	}
	
}

?>