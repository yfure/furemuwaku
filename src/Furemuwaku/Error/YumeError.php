<?php

namespace Yume\Fure\Error;

use Error;
use Throwable;

use Yume\Fure;
use Yume\Fure\Locale;
use Yume\Fure\Support;
use Yume\Fure\Util;
use Yume\Fure\Util\Reflect;

/*
 * YumeError
 *
 * @extends Error
 *
 * @package Yume\Fure\Error
 */
class YumeError extends Error
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
	 * Tracking Error Source.
	 *
	 * @access Protected
	 *
	 * @values Array
	 */
	protected Array $track = [];
	
	/*
	 * Exception type thrown.
	 *
	 * @access Protected Readonly
	 *
	 * @values String
	 */
	protected Readonly String $type;
	
	/*
	 * Construct method of class YumeError
	 *
	 * @access Public Initialize
	 *
	 * @params Array|Int|String $message
	 * @params Int $code
	 * @params Throwable $previous
	 * @params String $file
	 * @params String $line
	 *
	 * @return Void
	 */
	public function __construct( Array | Int | String $message, Int $code = 0, ? Throwable $previous = Null, ? String $file = Null, ? Int $line = Null )
	{
		// Set error type.
		$this->setType( $code );
		
		$this->file = $file ?? $this->getFile();
		$this->line = $line ?? $this->getLine();

		// if( $this Instanceof IndexError )
		// {
		// 	echo $this->file;
		// 	echo $this->line;
		// 	var_dump( $message );
		// }
		
		// Check if error has type.
		if( $this->type !== "Unknown" )
		{
			// If translation is not available.
			if( Locale\Locale::getLanguage()->yume === Null ) 
			{
				Locale\Locale::setTranslation(
					Locale\Locale::getTranslation( "Furemu" )
				);
			}
			
			// Optional value when translation is unavailable.
			$optional = $this->flags[$this::class] ?? [];
			$optional = $optional[$code] ?? Null;
			
			// Create key name.
			$key = Support\Package::array( $this::class );
			
			$furemu = strpos( $this::class, Fure::class ) === 0;
			$translation = Locale\Locale::translate( $x = join( ".", $furemu ? [ /** "Furemu", */ $key, $this->type ] : [ $key, $this->type ] ), $optional, False );
			
			// If translation is available.
			if( $translation )
			{
				if( is_array( $message ) === False )
				{
					$message = [$message];
				}
				$message = Util\Strings::format( $translation, ...$message );
			}
		}
		foreach( $this->track As $group => $info )
		{
			// Make Pattern for match filename.
			$pattern = Util\Strings::format( "/{}\\/(?:{})\\.php$/i", str_replace( DIRECTORY_SEPARATOR, "\\" . DIRECTORY_SEPARATOR, Support\Package::path( $group ) ), join( "|", array_map( fn( String $class ) => Util\Strings::pop( $class, "\\", True ), $info['classes'] ) ) );
			
			// If current filename match with pattern.
			if( preg_match( $pattern, $this->getFile() ) )
			{
				// Mapping exception traces.
				foreach( $this->getTrace() As $i => $trace )
				{
					// Skip if class name does not exists.
					if( in_array( $trace['class'] ?? "", $info['classes'] ) === False ) continue;
					
					// Skip if file name has prefix filename from track.
					if( preg_match( $pattern, $trace['file'] ?? "" ) ) continue;
					
					// If keys is exists.
					if( isset( $trace['function'] ) &&
						isset( $trace['file'] ) &&
						isset( $trace['type'] ) &&
						isset( $trace['line'] ) )
					{
						// Change error filename and line number.
						$this->file = $file ?? $trace['file'];
						$this->line = $line ?? $trace['line'];
						break;
					}
				}
			}
		}
		parent::__construct( Util\Strings::parse( $message ), $code, $previous );
	}
	
	/*
	 * Parse class to string.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function __toString(): String
	{
		$error = $this;
		$stack = [
			$this->format( $this )
		];
		
		// When exception thrown has previous exception thrown.
		while( $error = $error->getPrevious() )
		{
			// Parse exception to string.
			$stack[] = $this->format( $error );
		}
		return( path( join( "\n", array_reverse( $stack ) ), True ) );
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
		if( $thrown Instanceof YumeError )
		{
			return( Util\Strings::format( "\n{class}: {message}\n{class}: File: {file}\n{class}: Line: {line}\n{class}: Type: {type}\n{class}: Code: {code}\n{class}: {trace}\n", ...$values ) );
		}
		return( Util\Strings::format( "\n{class}: {message}\n{class}: File: {file}\n{class}: Line: {line}\n{class}: Code: {code}\n{class}: {trace}\n", ...$values ) );
	}
	
	/*
	 * Return exception type.
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
	 * Set error type based on error contant name.
	 *
	 * @access Public
	 *
	 * @params Int $code
	 *
	 * @return String
	 */
	private function setType( Int $code ): Void
	{
		try
		{
			if( Reflect\ReflectProperty::isInitialized( $this, "type" ) === False )
			{
				$this->type = Util\Strings::fromKebabCaseToUpperCamelCase( is_string( $type = array_search( $code, Reflect\ReflectClass::getConstants( $this ) ) ) ? $type : "Unknown" );
			}
		}
		catch( Throwable )
		{}
	}
	
}

?>
