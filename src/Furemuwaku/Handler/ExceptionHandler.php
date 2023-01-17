<?php

namespace Yume\Fure\Handler;

use Throwable;

/*
 * ExceptionHandler
 *
 * @package Yume\Fure\Handler
 */
abstract class ExceptionHandler
{
	
	/*
	 * Handle all uncaught exceptions.
	 *
	 * @access Public Static
	 *
	 * @params Throwable $thrown
	 *
	 * @return Void
	 */
	public static function handler( Throwable $thrown ): Void
	{
		// Default stack is exception thrown.
		$stack = $thrown;
		
		// If exception thrown has previous.
		if( $stack->getPrevious() !== Null )
		{
			// Exception class previous.
			$stack = [ $stack ];
			
			// Main exception class thrown.
			$prevs = $thrown;
			
			do
			{
				// Insert previous class.
				$stack[$prevs::class] = $prevs;
			}
			while( $prevs = $prevs->getPrevious() );
		}
		
		echo "<pre>";
		var_dump( $stack );
	}
	
}

?>