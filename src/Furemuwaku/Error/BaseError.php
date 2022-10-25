<?php

namespace Yume\Fure\Error;

use Error;
use Throwable;

use Yume\Fure\Support;
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
	 * Value of flag (e.g...[ self::NAME_ERROR => "Name for {} is undefined" ])
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
		// If the error thrown has a flag.
		if( count( $this->flags ) > 0 && $code !== 0 )
		{
			// If the flag is available.
			if( isset( $this->flags[$code] ) )
			{
				if( is_array( $message ) )
				{
					$message = Util\Str::fmt( $this->flags[$code], ...$message );
				}
				else {
					$message = Util\Str::fmt( $this->flags[$code], $message );
				}
			}
			else {
				$message = Util\Str::parse( $message );
			}
		}
		else {
			$message = Util\Str::parse( $message );
		}
		
		// Get constant name.
		if( $type = array_search( $code, Support\Reflect\ReflectClass::getConstants( $this ) ) )
		{
			// Set error type based on constant name.
			$this->type = $type;
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
		return( 
			path( 
				remove: True, 
				path: f( "{}: {} on file {} line {} code {}.\n{}", ...[
					$this::class, 
					$this->getMessage(), 
					$this->getFile(), 
					$this->getLine(), 
					$this->getCode(), 
					$this->getTrace()
				]) 
			) 
		);
	}
	
}

?>