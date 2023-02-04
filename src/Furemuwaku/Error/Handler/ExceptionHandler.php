<?php

namespace Yume\Fure\Error\Handler;

use Throwable;

use Yume\Fure\Error\Handler\Sutakku;

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
	public static function handler( Throwable $thrown ): Void
	{
		// Default stack is exception thrown.
		$stack = $thrown;
		
		// If exception thrown has previous.
		if( $stack->getPrevious() !== Null )
		{
			// Exception class previous.
			$stack = [
				$stack::class => $stack
			];
			
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
		
		// Create new Suttaku instance.
		$sutakku = new Sutakku\Sutakku( $stack );
		
		// Building sutakku stack trace.
		$sutakku->build();
		
		// Get view instance for exception view.
		//$view = view( config( "error[exception.views]" ), $sutakku->getTrace() );
		
		// Rendering view.
		//$view->render();
		echo json_encode( $sutakku->getTrace(), JSON_PRETTY_PRINT );
	}
	
}

?>