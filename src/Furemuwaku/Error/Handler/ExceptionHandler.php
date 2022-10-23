<?php

namespace Yume\Fure\Error\Handler;

use Throwable;

/*
 * ExceptionHandler
 *
 * @package Yume\Fure\Error\Handler
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
	public static function handle( Throwable $thrown ): Void
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
		
		// Create new Sutakku class instance.
		$sutakku = new Sutakku\Sutakku( $stack );
		
		echo "<pre>";
		var_dump( $sutakku );
	}
	
}

?>