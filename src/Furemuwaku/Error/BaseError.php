<?php

namespace Yume\Fure\Error;

use Error;
use Throwable;

use Yume\Fure\Locale;
use Yume\Fure\Support\Package;
use Yume\Fure\Support\Reflect;
use Yume\Fure\Util;

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
	protected String $type = "NONE";
	
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
		
		// Get flags index.
		$index = array_search( $code, $this->flags[$this::class] ?? [] );
		
		// If the flag is available.
		if( $type !== False && $index !== False )
		{
			// Set error type based on error contant name.
			$this->type = $type;
			
			// Create ify for translation string.
			$ify = Util\Str::fmt( "{}.{}", Package\Package::array( $this::class ), $type );
			
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
			$message = Util\Str::parse( $message );
		}
		
		// Call parent constructor.
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
		return( $this->type );
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
	private function format( ? Throwable $thrown )
	{
		if( $thrown Instanceof BaseError )
		{
			$format = "\n{class}: {type}: {message} on file {file} line {line} code {code}.\n{class}{trace}\n";
		}
		else {
			$format = "\n{class}: {message} on file {file} line {line} code {code}.\n{class}{trace}\n";
		}
		return( f( $format, class: $thrown::class, message: $thrown->getMessage(), file: $thrown->getFile(), line: $thrown->getLine(), code: $thrown->getCode(), type: $thrown->type ?? "None", trace: $thrown->getTrace() ) );
	}
	
}

?>