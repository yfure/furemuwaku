<?php

namespace Yume\Fure\Http\Response;

use Yume\Fure\Http\Message;

/*
 * ResponseInterface
 * 
 * @package Yume\Fure\Http\Response
 */
interface ResponseInterface extends Message\MessageInterface {

	/*
	 * Return the Http Response Status Code.
	 * 
	 * @access Public
	 * 
	 * @return Int
	 */
	public function getStatusCode(): int;

	/*
	 * Return the Reason Phrase associated with the Status Code.
	 * 
	 * @access Public
	 * 
	 * @return String
	 */
    public function getReasonPhrase(): String;
	
    /*
	 * Return new Response Instance with Specific Status Code and Reason Phrase.
	 * 
	 * @access Public
	 * 
	 * @params Int $code
	 *  Http Response Status Code
	 * @params String $reasonPhrase
	 *  Http Response Reason Phrase
	 * 
	 * @return Yume\Fure\Http\Response\ResponseInterface
	 * @throws Yume\Fure\Error\HttpError
	 *  When the value of status code is invalid
	 */
    public function withStatus( Int $code, String $reasonPhrase = "" ): ResponseInterface;

}

?>