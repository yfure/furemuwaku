<?php

namespace Yume\Fure\Http\Response;

use Yume\Fure\Error;
use Yume\Fure\Http;
use Yume\Fure\Http\Header;
use Yume\Fure\Http\HttpStatus;
use Yume\Fure\IO\Stream;

/*
* Response
* 
* Http Response
* 
* @package Yume\Fure\Http\Response
*/
class Response implements ResponseInterface {

	/*
	 * Http Status Code Response.
	 * 
	 * @access Private
	 * 
	 * @value Int
	 */
	private Int $code;

	/*
	 * Http Status Code Phrase.
	 * 
	 * @access Private
	 * 
	 * @values String
	 */
	private String $phrase;

	use \Yume\Fure\Http\Message\MessageTrait;

	/*
	 * Construct method of class Response.
	 * 
	 * @access Public Initialize
	 * 
	 * @params Yume\Fure\IO\Stream\StreamInterface $body
	 * @params Int $code
	 * @params Yume\Fure\Http\Header\Headers $headers
	 * @params String $protocol
	 * 
	 * @return Void
	 * @throws Yume\Fure\Error\HttpError
	 *  When the value of status code is invalid
	 */
	public function __construct( ?Stream\StreamInterface $body = Null, Int $code = 200, ?Header\Headers $headers = Null, String $protocol = "1.1" ) {
		$phrase = Http\HttpStatus::from( $code );
		if( $phrase !== Null ) {
			$this->phrase = $phrase->title;
		}
		else {
			throw new Error\HttpError( $code, Error\HttpError::STATUS_ERROR );
		}
		$this->body = $body ?? Stream\StreamFactory::create( "" );
		$this->code = $code;
		$this->headers = $headers ?? new Header\Headers;
		$this->protocol = $protocol;
	}

	/*
	 * @inherit Yume\Fure\Http\Response\ResponseInterface->getStatusCode
	 * 
	 */
	public function getStatusCode(): Int {
		return $this->code;
	}

	/*
	 * @inherit Yume\Fure\Http\Response\ResponseInterface->getReasonPhrase
	 * 
	 */
	public function getReasonPhrase(): String {
		return $this->phrase;
	}

	/*
	 * @inherit Yume\Fure\Http\Response\ResponseInterface->withStatus
	 * 
	 */
	public function withStatus( Int $code, $reasonPhrase = "" ): ResponseInterface {
		if( $this->code !== $code ) {
			$status = HttpStatus::from( $code );
			if( $status === Null ) {
				throw new Error\HttpError( $code, Error\HttpError::STATUS_ERROR );
			}
			if( valueIsEmpty( $reasonPhrase ) ) {
				$reasonPhrase = $status->title;
			}
			$instance = Clone $this;
			$instance->code = $code;
		}
		return $instance ?? $this;
	}
	
}

?>