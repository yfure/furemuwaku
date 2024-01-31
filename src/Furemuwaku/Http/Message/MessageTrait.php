<?php

namespace Yume\Fure\Http\Message;

use Yume\Fure\Http\Header;
use Yume\Fure\IO\Stream;
use Yume\Fure\Util\Arr;

/*
 * MessageTrait
 * 
 * Http Message Trait Completion
 * 
 * @package Yume\Fure\Http\Message
 */
trait MessageTrait {

	private ?Stream\StreamInterface $body = Null;

	/*
	 * Instance of class Headers.
	 * 
	 * The keyset of headers must be insensitive case
	 * 
	 * @access Private Instance
	 * 
	 * @values Yume\Fure\Http\Header\Headers
	 */
	private ? Header\Headers $headers;

	private String $protocol = "1.1";

	/*
	 * @inherit Yume\Fure\Http\Message\MessageInterface->getProtocolVersion
	 * 
	 */
	public function getProtocolVersion(): String {
		return $this->protocol;
	}

	/*
	 * @inherit Yume\Fure\Http\Message\MessageInterface->withProtocolVersion
	 * 
	 */
	public function withProtocolVersion( String $version ): MessageInterface {
		if( $version !== $this->protocol ) {
			$instance = Clone $this;
			$instance->protocol = $version;
		}
		return $instance ?? $this;
	}

	/*
	 * @inherit Yume\Fure\Http\Message\MessageInterface->getHeaders
	 * 
	 */
	public function getHeaders(): Header\Headers {
		return $this->headers ??= new Header\Headers;
	}

	/*
	 * @inherit Yume\Fure\Http\Message\MessageInterface->hasHeader
	 * 
	 */
	public function hasHeader( String $name ): Bool {
		return isset( $this->headers[$name] );
	}

	public function getHeader( String $name ): Array {
		if( $this->hasHeader( $name ) ) {
			return $this->headers[$name];
		}
		return [];
	}

	public function getHeaderLine( String $name ): String {
		return join( "\x2c\x20", $this->getHeader( $name ) );
	}

	public function withHeader( String $name, Mixed $value ): MessageInterface {
		$instance = Clone $this;
		$instance->headers = new Header\Headers( $instance->headers );
		$instance->headers[$name] = $value;
		return $instance;
	}

	public function withAddedHeader( String $name, Mixed $value ): MessageInterface {
		if( $this->hasHeader( $name ) ) {
			$value = Header\Header::normalizeValue( $name, $value );
			$instance = Clone $this;
			$instance->headers = new Header\Headers( $instance->headers );
			$instance->headers[$name] = array_merge( $this->headers[$name]->value, $value );
		}
		else {
			$instance = $this->withHeader( $name, $value );
		}
		return $instance;
	}

	public function withoutHeader( String $name ): MessageInterface {
		if( $this->hasHeader( $name ) ) {
			$instance = Clone $this;
			$instance->headers = new Header\Headers( $instance->headers );
			$instance->headers->offsetUnset( $name );
		}
		return $instance ?? $this;
	}

	/*
	 * @inherit Yume\Fure\Http\Message\MessageInterface->getBody
	 * 
	 */
	public function getBody(): Stream\StreamInterface {
		return $this->body ??= Stream\StreamFactory::create( "" );
	}

	/*
	 * @inherit Yume\Fure\Http\Message\MessageInterface->withBody
	 * 
	 */
	public function withBody( Stream\StreamInterface $body ): MessageInterface {
		if( $this->body !== $body ) {
			$instance = Clone $this;
			$instance->body = $body;
		}
		return $instance ?? $this;
	}

}

?>