<?php

namespace Yume\Fure\HTTP\Message;

use Yume\Fure\Error;
use Yume\Fure\HTTP\Stream;
use Yume\Fure\Util;
use Yume\Fure\Util\RegExp;

/*
 * MessageTrait
 *
 * @package Yume\Fure\HTTP\Message
 */
trait MessageTrait
{
	
	/*
	 * StreamInterface for Message Body.
	 *
	 * @access Protected
	 *
	 * @values Yume\Fure\HTTP\Stream\StreamInterface
	 */
	protected ? Stream\StreamInterface $body = Null;
	
	/*
	 * HTTP Request Headers.
	 *
	 * @access Protected
	 *
	 * @values Array
	 */
	protected Array $headers = [];
	
	/*
	 * HTTP Request Header Names.
	 *
	 * @access Protected
	 *
	 * @values Array
	 */
	protected Array $headerNames = [];
	
	/*
	 * Protocol Version.
	 *
	 * @access Protected
	 *
	 * @values String
	 */
	protected String $protocol = "1.0";
	
	/*
	 * Protocol Version Avaibles.
	 *
	 * @access Protected
	 *
	 * @values Array
	 */
	protected Array $protocolAvaibles = [
		"1.0",
		"1.1",
		"2.0",
		"3.0"
	];
	
	/*
	 * @inherit Yume\Fure\HTTP\Message\MessageInterface
	 *
	 */
	public function getHeader( String $name ): Array
	{
		// Convert header name to lower case.
		$name = strtolower( $name );
		
		// Check if header exists.
		if( isset( $this->headerNames[$name] ) )
		{
			return( $this )->headers[$this->headerNames[$name]];
		}
		return([]);
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Message\MessageInterface
	 *
	 */
	public function getHeaderLine( String $name ): String
	{
		return( implode( ", ", $this->getHeader( $name ) ) );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Message\MessageInterface
	 *
	 */
	public function getHeaders(): Array
	{
		return( $this )->headers;
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Message\MessageInterface
	 *
	 */
	public function getBody(): Stream\StreamInterface
	{
		// Check if body has no StreamInterface instance.
		if( $this->body === Null )
		{
			$this->body = Stream\StreamFactory::createStream();
		}
		return( $this )->stream;
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Message\MessageInterface
	 *
	 */
	public function getProtocolVersion(): String
	{
		return( $this )->protocol;
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Message\MessageInterface
	 *
	 */
	public function hasHeader( $name ): Bool
	{
		return( isset( $this->headerNames[strtolower( $name )] ) );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Message\MessageInterface
	 *
	 */
	public function withAddedHeader( String $name, Array | String $value ): MessageInterface
	{
		// Assert header name.
		$this->assertHeader( $name );
		
		// Normalization header values.
		$value = $this->normalizeHeaderValue($value);
		
		// Convert header name to lower case.
		$lname = strtolower( $name );
		
		// Copy current class instance.
		$copy = Clone $this;
		
		// Check if header is exists.
		if( $copy->hasHeader( $lname ) )
		{
			// Get header name.
			$name = $this->headerNames[$lname];
			
			// Merging array values.
			$copy->headers[$name] = array_merge( $this->headers[$name], $value );
		}
		else {
			$copy->headerNames[$lname] = $name;
			$copy->headers[$name] = $value;
		}
		return( $copy );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Message\MessageInterface
	 *
	 */
	public function withBody( Stream\StreamInterface $body ): MessageInterface
	{
		// Check if body value is not equals.
		if( $body !== $this->body )
		{
			// Copy current class instance.
			$copy = Clone $this;
			
			// Set body value.
			$copy->body = $body;
			
			// Return cloned instance.
			return( $copy );
		}
		return( $this );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Message\MessageInterface
	 *
	 */
	public function withHeader( String $name, Array | String $value ): MessageInterface
	{
		// Assert header name.
		$this->assertHeader( $name );
		
		// Normalization header values.
		$value = $this->normalizeHeaderValue($value);
		
		// Convert header name to lower case.
		$lname = strtolower( $name );
		
		// Copy current class instance.
		$copy = Clone $this;
		
		// Check if header is exists.
		if( $copy->hasHeader( $lname ) )
		{
			unset( $copy->headers[$copy->headerNames[$lname]] );
		}
		$copy->headerNames[$lname] = $name;
		$copy->headers[$name] = $value;
		
		// Return cloned instance.
		return( $copy );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Message\MessageInterface
	 *
	 */
	public function withProtocolVersion( String $version ): MessageInterface
	{
		// Check if current protocol version is not equals.
		if( $this->protocol !== $version )
		{
			// Copy current class instance.
			$copy = Clone $this;
			
			// Set protocol version.
			$copy->protocol = $version;
			
			// Return cloned instance.
			return( $copy );
		}
		return( $this );
	}
	
	/*
	 * @inherit Yume\Fure\HTTP\Message\MessageInterface
	 *
	 */
	public function withoutHeader( String $name ): MessageInterface
	{
		// Convert header name to lower case.
		$name = strtolower( $name );
		
		// Check if header exists.
		if( isset( $this->headerNames[$name] ) )
		{
			// Get header name.
			$real = $this->headerNames[$name];
			
			// Copy current class instance.
			$copy = Clone $this;
			
			// Unset header name and values.
			unset(
				$copy->headers[$real],
				$copy->headerNames[$name]
			);
			
			// Return cloned instance.
			return( $copy );
		}
		return( $this );
	}
	
	/*
	 * Set multiple headers.
	 *
	 * @access Private
	 *
	 * @params Array<String|Int[String|String[]]> $headers
	 *
	 * @return Void
	 */
	private function setHeaders( Array $headers ): Void
	{
		// Reset current header names and values.
		$this->headerNames = $this->headers = [];
		
		// Mapping headers.
		Util\Arr::map( $headers, function( Int $i, Int | String $header, Array | String $value )
		{
			// Numeric array keys are converted to int by PHP.
			$header = ( String ) $header;
			
			// Assert header name.
			$this->assertHeader($header);
			
			// Normalization header name.
			$value = $this->normalizeHeaderValue( $value );
			
			// Convert header name to lower case.
			$normalized = strtolower( $header );
			
			if( isset( $this->headerNames[$normalized] ) )
			{
				$header = $this->headerNames[$normalized];
				$this->headers[$header] = array_merge($this->headers[$header], $value);
			}
			else {
				$this->headerNames[$normalized] = $header;
				$this->headers[$header] = $value;
			}
		});
	}
	
	/*
	 * Normalization header value.
	 *
	 * @access Private
	 *
	 * @params Mixed $value
	 *
	 * @return Array<String>
	 *
	 * @throws Yume\Fure\HTTP\Message\MessageError
	 */
	private function normalizeHeaderValue( Mixed $value ): Array
	{
		// Check if value is Array type.
		if( is_array( $value ) )
		{
			// Check if array length is zero.
			if( count( $value ) === 0 )
			{
				throw new MessageError( "" );
			}
			return( $this )->trimAndValidateHeaderValues( $value );
		}
		return( $this )->trimAndValidateHeaderValues([ $value ]);
	}
	
	/*
	 * Trims whitespace from the header values.
	 * Spaces and tabs ought to be excluded by parsers
	 * when extracting the field value from a header field.
	 *
	 * @access Private
	 *
	 * @params Array<Mixed> $values
	 *
	 * @return Array<String>
	 *
	 * @throws Yume\Fure\HTTP\Message\MessageError
	 */
	private function trimAndValidateHeaderValues( Array $values ): Array
	{
		// Mapping values.
		return( Util\Arr::map( $values, function( Int $i, $idx, $value )
		{
			// Check if value is not Scalar type.
			// And value is not Null value.
			if( is_scalar( $value ) === False && $value !== Null )
			{
				throw new MessageError( f( "Header value must be Scalar or Null but {0:upper} provided", is_object( $value ) ? $value::class : gettype( $value ) ) );
			}
			
			// Value Assertion.
			$this->assertValue(
				$trimmed = trim( ( String ) $value, " \t" )
			);
			
			// Return trimmed strings.
			return( $trimmed );
		}));
	}
	
	/*
	 * Header name assertion.
	 *
	 * @access Private
	 *
	 * @params Mixed $header
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\HTTP\Header\HeaderError
	 */
	private function assertHeader( Mixed $header ): void
	{
		// Check if header name is String type.
		if( is_string( $header ) )
		{
			// Check if header name is valid name.
			if( RegExp\RegExp::test( "/^[a-zA-Z0-9\'`#$%&*+.^_|~!-]+$/" ) )
			{
				return;
			}
			throw new Header\HeaderError( $header, HeaderError::NAME_ERROR );
		}
		throw new Header\HeaderError( $header, HeaderError::NAME_TYPE_ERROR );
		
		if (!is_string($header)) {
			throw new \InvalidArgumentException(sprintf(
				'Header name must be a string but %s provided.',
				is_object($header) ? get_class($header) : gettype($header)
			));
		}
	
		if (! preg_match('/^[a-zA-Z0-9\'`#$%&*+.^_|~!-]+$/', $header)) {
			throw new \InvalidArgumentException(
				sprintf(
					'"%s" is not valid header name',
					$header
				)
			);
		}
	}
	
	/**
	 * @see https://tools.ietf.org/html/rfc7230#section-3.2
	 *
	 * field-value	= *( field-content / obs-fold )
	 * field-content  = field-vchar [ 1*( SP / HTAB ) field-vchar ]
	 * field-vchar	= VCHAR / obs-text
	 * VCHAR		  = %x21-7E
	 * obs-text	   = %x80-FF
	 * obs-fold	   = CRLF 1*( SP / HTAB )
	 */
	private function assertValue( String $value ): Void
	{
		/*
		 * The regular expression intentionally does not support the obs-fold production, because as
		 * per RFC 7230#3.2.4:
		 *
		 * A sender MUST NOT generate a message that includes
		 * line folding (i.e., that has any field-value that contains a match to
		 * the obs-fold rule) unless the message is intended for packaging
		 * within the message/http media type.
		 *
		 * Clients must not send a request with line folding and a server sending folded headers is
		 * likely very rare. Line folding is a fairly obscure feature of HTTP/1.1 and thus not accepting
		 * folding is not likely to break any legitimate use case.
		 *
		 */
		if (! preg_match('/^[\x20\x09\x21-\x7E\x80-\xFF]*$/', $value)) {
			throw new \InvalidArgumentException(sprintf('"%s" is not valid header value', $value));
		}
	}
	
}

?>