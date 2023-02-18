<?php

namespace Yume\Fure\Util;

use Yume\Fure\Support\Data;
use Yume\Fure\Support\Design;

/*
 * Timer
 *
 * @package Yume\Fure\Util
 *
 * @extends Yume\Fure\Support\Design\Singleton
 */
final class Timer extends Design\Singleton
{
	
	/*
	 * Instance of class DataInterface.
	 *
	 * This is on for stored compilation time.
	 *
	 * @access Private Readonly
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	private Readonly Data\DataInterface $times;
	
	/*
	 * @inherit Yume\Fure\Support\Design\Singleton
	 *
	 */
	protected function __construct()
	{
		$this->times = new Data\Data;
	}
	
	/*
	 * Add execution time.
	 *
	 * @access Public Static
	 *
	 * @params String $name
	 * @params Array $result
	 *
	 * @return Void
	 */
	public static function add( String $name, Array $result ): Void
	{
		self::self()->times->__set( strtolower( $name ), $result );
	}
	
	/*
	 * Compare execution time.
	 *
	 * @access Public Static
	 *
	 * @params Float|String $start
	 * @params Float|String $finish
	 *
	 * @return String
	 */
	public static function compare( Float | String $start, Float | String $finish ): String
	{
		// Sum of all the values in the array.
		$start = array_sum( explode( "\x20", $start ) );
		$finish = array_sum( explode( "\x20", $finish ) );
		
		$time = $finish - $start;
		
		// Format a number with grouped thousands.
		$fmtTimeMS = number_format( $time * 1000, 4 );
		$fmtTimeM = number_format( $time / 60, 4 );
		$fmtTimeS = number_format( $time, 4 );
		
		return( match( True )
		{
			// If miliseconds is more than one hundred.
			$fmtTimeMS >= 1000 => f( "{}s", round( $fmtTimeMS / 1000, 2 ) ),
			
			// If senconds is more than sizten.
			$fmtTimeS >= 60 => f( "{}m", $fmtTimeM ),
			
			// Only miliseconds.
			default => f( "{}ms", $fmtTimeS )
			
		});
	}
	
	/*
	 * Calculate execution time callback function.
	 *
	 * @access Public Static
	 *
	 * @params String $name
	 * @params Callable $target
	 *
	 * @return String
	 */
	public static function calculate( String $name, Callable $target, Array $args = [] ): String
	{
		// Start time.
		$start = microtime( True );
		
		// Execute callback function.
		call_user_func( $target, ...$args );
		
		// Finish time.
		$finish = microtime( True );
		
		// Get comparation time.
		$compare = self::compare( $start, $finish );
		
		// Saving callback execution time.
		self::add( $name, [
			"start" => $start,
			"finish" => $finish,
			"compare" => $compare
		]);
		
		return( $compare );
	}
	
	/*
	 * Return copied times.
	 *
	 * @access Public Static
	 *
	 * @return Yume\Fure\Support\Data\DataInterface
	 */
	public static function get(): Data\DataInterface
	{
		return( self::self() )->times->copy();
	}
	
}

?>