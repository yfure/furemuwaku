<?php

namespace Yume\Fure\Util\Env;

use Throwable;

use Yume\Fure\Error;
use Yume\Fure\Util;
use Yume\Fure\Util\RegExp;

/*
 * EnvError
 *
 * @package Yume\Fure\Util\Env
 *
 * @extends Yume\Fure\Error\TypeError
 */
class EnvError extends Error\TypeError
{
	
	/*
	 * Error constant for invalid assigment symbol.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const ASSIGMENT_ERROR = 10238;
	
	/*
	 * Error constant for invalid comment on value.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const COMMENT_ERROR = 11428;
	
	/*
	 * Error constant for invalid json strings.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const JSON_ERROR = 11725;
	
	/*
	 * Error constant for undefined or unknown variable reference.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const REFERENCE_ERROR = 13467;
	
	/*
	 * Error constant for invalid syntax.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const SYNTAX_ERROR = 14829;
	
	/*
	 * @inherit Yume\Fure\Error\TypeError
	 *
	 */
	public function __construct( Array | Int | String $message, ? String $file = Null, Int $line = 0, Int $code = 0, ? Throwable $previous = Null )
	{
		// Set environment file name.
		$this->file = $file ?? $this->file;
		
		// Set environment line number.
		$this->line = $line ?: $this->line;
		
		// Call parent constructor.
		parent::__construct( code: $code, previous: $previous, message: match( $code )
		{
			self::ASSIGMENT_ERROR => $this->format( "Invalid operator {} for assigment value to variable", $message ),
			self::COMMENT_ERROR => $this->format( "Value can't have {} comment syntax", $message ),
			self::JSON_ERROR => $this->format( "Invalid json string value in variable {}", $message ),
			self::REFERENCE_ERROR => $this->format( "Undefined environment variable {}", $message ),
			self::SYNTAX_ERROR => $this->format( "Invalid syntax {}", $message ),
			default => Util\Str::parse( $message )
		});
	}
	
	/*
	 * Get formated error message.
	 *
	 * @access Private
	 *
	 * @params String $format
	 * @params Array|Int|String $message
	 *
	 * @return String
	 */
	private function format( String $format, Array | Int | String $message ): String
	{
		// Check if message is Array type.
		if( is_array( $message ) )
		{
			// Mapping messages.
			$values = Util\Arr::map( $message, function( Int $i, $idx, $value )
			{
				// Check if message is String type.
				if( is_string( $value ) )
				{
					return( RegExp\RegExp::replace( "/^[\s\t]*/", $value, "" ) );
				}
				return( $value );
			});
		}
		else {
			// Check if message is String type.
			if( is_string( $message ) )
			{
				$message = RegExp\RegExp::replace( "/^[\s\t]*/", $message, "" );
			}
			$values = [ $message ];
		}
		
		// Return formated message.
		return( Util\Str::fmt( $format, ...$values ) );
	}
	
}

?>