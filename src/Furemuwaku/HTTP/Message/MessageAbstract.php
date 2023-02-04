<?php

namespace Yume\Fure\HTTP\Message;

use Yume\Fure\HTTP\Request;
use Yume\Fure\HTTP\Response;
use Yume\Fure\Util;
use Yume\Fure\Util\RegExp;

/*
 * MessageAbstract
 *
 * @package Yume\Fure\HTTP\Message
 */
abstract class Message
{
	
	/*
	 * Get a short summary of the message body.
	 *
	 * @access Public Static
	 *
	 * @params Yume\Fure\HTTP\Message\MessageInterface $message
	 * @params Int $truncateAt
	 *
	 * @return String
	 */
	public static function bodySummary( MessageInterface $message, Int $truncateAt = 120 ): ? String
	{
		// Get message body.
		$body = $message->getBody();
		
		// If body is nSeekable or Readable.
		if( $body->isSeekable() || $body->isReadable() )
		{
			// Get body size.
			$size = $body->getSize();
			
			// Check if size is not equals zero.
			if( $size !== 0 )
			{
				// Rewind body.
				$body->rewind();
				
				// Read body by truncate value.
				$summary = $body->read($truncateAt);
				
				// Re-rewind body.
				$body->rewind();
				
				// If size is smaller than truncate value.
				if( $size > $truncateAt ) $summary .= " (truncated...)";
				
				// Matches any printable character, including unicode characters:
				// letters, marks, numbers, punctuation, spacing, and separators.
				if( RegExp\RegExp::test( "/[^\pL\pM\pN\pP\pS\pZ\n\r\t]/u", $summary ) === False )
				{
					return( $summary );
				}
			}
		}
		return( Null );
	}
	
	/*
	 * Parse class implements MessageInterface into string.
	 *
	 * @access Public Static
	 *
	 * @params Yume\Fure\HTTP\Message\MessageInterface $message
	 *
	 * @return String
	 */
	public static function toString( MessageInterface $message ): String
	{
		// Parsing MessageInterface to string.
		$string = call_user_func( match( True )
		{
			// If message is instance of RequestInterface.
			$message Instanceof Request\RequestInterface => function( Request\RequestInterfce $request ): String
			{
				// ...
				$string = f( "{}\x20HTTP/{}", trim( f( "{}\x20{}", $request->getMethod(), $request->getRequestTarget() ) ), $request->getProtocolVersion() );
				
				// Check if request has no header Host.
				if( $request->hasHeder( "Host" ) === False )
				{
					// Add header Host into string.
					$string = f( "{}\r\nHost:\x20{}", $string, $request->getUri()->getHost() );
				}
				return( $string );
			},
			
			// If message is instance of RequestInterface.
			$message Instanceof Response\ResponseInterface => fn( Response\ResponseInterface $response ) => f( ...[
				"HTTP/{}\x20{}\x20{}",
				...[
					$response->getProtocolVersion(),
					$response->getStatusCode(),
					$response->getReasonPhrase()
				]
			]),
			
			// When invalid message type passed.
			default => throw new MessageError( $message::class, MessageError::TYPE_ERROR )
		});
		
		// Mapping headers.
		Util\Arr::map( $message->getHeaders(), function( Int $i, String $name, String $values ) use( &$string )
		{
			// Check if header name is Set-Cookie.
			if( strtolower( $name ) === "set-cookie" )
			{
				// Mapping cookies.
				Util\Arr::map( $values, fn( Int $i, $name, $value ) => $string = f( "{}\r\n{}:\x20{}", $string, $name, $value ) );
			}
			else {
				$string = f( "{}\r\n{}:\x20{implode(2)}", $string, $name, [ ", ", $values ] );
			}
		});
		
		// Return formated strings.
		return( f( "{}\r\n\r\n{}", $string, $message->getBody() ) );
	}
	
}

?>