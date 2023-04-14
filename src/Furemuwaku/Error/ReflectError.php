<?php

namespace Yume\Fure\Error;

use Throwable;

use Yume\Fure\Util\Reflect;

/*
 * ReflectError
 *
 * @package Yume\Fure\Error
 *
 * @extends Yume\Fure\Error\TypeError
 */
class ReflectError extends TypeError
{
	
	/*
	 * @inherit Yume\Fure\Error\TypeError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = 0, ? Throwable $previous = Null )
	{
		// Check if exception thrown in Reflect Namespace.
		if( preg_match( $regex = "/\/Reflect\/Reflect(Attribute|Class|Constant|Decorator|Enum|EnumBacked|EnumUnit|Extension|Fiber|Function|Generator|Method|Parameter|Property|Type)\.php$/i", $this->getFile() ) )
		{
			// Mapping exception traces.
			foreach( $this->getTrace() As $i => $trace )
			{
				// Skip if class name has no Reflect Namespace.
				if( strpos( $trace['class'] ?? "", Reflect\Reflect::class ) === False ) continue;
				
				// Skip if file name has Reflect/Reflect
				if( preg_match( $regex, $trace['file'] ?? "" ) ) continue;
				
				// If keys is exists.
				if( isset( $trace['function'] ) &&
					isset( $trace['file'] ) &&
					isset( $trace['type'] ) &&
					isset( $trace['line'] ) )
				{
					$this->file = $trace['file'];
					$this->line = $trace['line']; break;
				}
			}
		}
		parent::__construct( $message, $code, $previous );
	}
	
}

?>