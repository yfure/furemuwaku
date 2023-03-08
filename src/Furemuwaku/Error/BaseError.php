<?php

namespace Yume\Fure\Error;

use Error;
use Throwable;

use Yume\Fure\Locale;
use Yume\Fure\Util\Package;
use Yume\Fure\Util\Reflect;
use Yume\Fure\Util\Type;

/*
 * BaseError
 *
 * @extends Error
 *
 * @package Yume\Fure\Error
 */
class BaseError extends Error
{
	
	/*
	 * Error constant for deprecated function, method, etc.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const DEPRECATED_ERROR = 17382;
	
	/*
	 * Error constant for Input/Output.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const IO_ERROR = 18271;
	
	/*
	 * Error constant for undefined reference.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const REFERENCE_ERROR = 27272;
	
	/*
	 * Error constant for invalid runtime.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const RUNTIME_ERROR = 29736;
	
	/*
	 * Error constant for invalid syntax.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const SYNTAX_ERROR = 30178;
	
	/*
	 * Error constant for emited error from trigger_error function.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const TRIGGER_ERROR = 34173;
	
	/*
	 * Error constant for any error types.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const TYPE_ERROR = 48627;
	
	/*
	 * Error constant for inavlid value passed.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const VALUE_ERROR = 49271;
	
	/*
	 * Value of flag.
	 *
	 * @access Protected
	 *
	 * @values Array
	 */
	protected Array $flags = [];
	
	/*
	 * Exception type thrown.
	 *
	 * @access Protected
	 *
	 * @values String
	 */
	protected String $type = "None";
	
	/*
	 * Construct method of class BaseError.
	 *
	 * @access Public Instance
	 *
	 * @params Array|Int|String $message
	 * @params Int $code
	 * @params Throwable $previous
	 *
	 * @return Void
	 */
	public function __construct( Array | Int | String $message, Int $code = 0, ? Throwable $previous = Null )
	{
		// Get constant name.
		$type = array_search( $code, Reflect\ReflectClass::getConstants( $this ) );
		
		// Set error type based on error contant name.
		$this->type = $type !== False ? $type : $this->type;
		
		// Get flags index.
		$index = array_search( $code, $this->flags[$this::class] ?? [] );
		
		// If the flag is available.
		if( $type !== False && $index !== False )
		{
			// Create ify for translation string.
			$ify = Type\Str::fmt( "{}.{}", Package\Package::array( $this::class ), $type );
			
			// Check if message is Array type.
			if( is_array( $message ) )
			{
				// Mapping for change array null value to empty string.
				// This is to avoid formatting errors.
				$message = lang( $ify, ...array_map( fn( Mixed $v ) => $v !== Null ? $v : "", $message ) ) ?? $ify;
			}
			else {
				$message = lang( $ify, $message ?? "" ) ?? $ify;
			}
		}
		else {
			$message = Type\Str::parse( $message );
		}
		parent::__construct( $message, $code, $previous );
	}
	
	/*
	 * Gets exception type.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getType(): String
	{
		return( $this )->type;
	}
	
	/*
	 * @inherit https://www.php.net/manual/en/language.oop5.magic.php#object.tostring
	 *
	 */
	public function __toString(): String
	{
		$error = $this;
		$stack = [
			$this->format( $this )
		];
		while( $error = $error->getPrevious() )
		{
			$stack[] = $this->format( $error );
		}
		return( path( implode( "\n", array_reverse( $stack ) ), True ) );
	}
	
	/*
	 * Return exception thrown for readable.
	 *
	 * @access Private
	 *
	 * @params Throwable $thrown
	 *
	 * @return String
	 */
	private function format( Throwable $thrown )
	{
		$values = [
			"class" => $thrown::class,
			"message" => $thrown->getMessage(),
			"file" => $thrown->getFile(),
			"line" => $thrown->getLine(),
			"code" => $thrown->getCode(),
			"type" => $thrown->type ?? "None",
			"trace" => $thrown->getTrace()
		];
		if( $thrown Instanceof BaseError )
		{
			return( f( "\n{class}: {type}: {message} on file {file} line {line} code {code}.\n{class}{trace}\n", ...$values ) );
		}
		return( f( "\n{class}: {message} on file {file} line {line} code {code}.\n{class}{trace}\n", ...$values ) );
	}
	
}

?>