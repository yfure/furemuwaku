<?php

namespace Yume\Fure\CLI;

use Yume\Fure\Util\Json;
use Yume\Fure\Util\RegExp;

/*
 * Argument
 *
 * @package Yume\Fure\CLI
 */
class Argument
{
	
	/*
	 * Argument values parsed.
	 *
	 * @access Private
	 *
	 * @values Array
	 */
	private Array $args = [];
	
	/*
	 * Construct method of class Argument.
	 *
	 * @access Public Instance
	 *
	 * @params Array $argv
	 *
	 * @return Void
	 */
	public function __construct( ? Array $argv = Null )
	{
		$this->parse();
	}
	
	/*
	 * Get value.
	 *
	 * @access Public
	 *
	 * @params String $arg
	 * @params Mixed $default
	 *
	 * @return Mixed
	 */
	public function get( String $arg, Mixed $default = Null ): Mixed
	{
		return( $this->args[$arg] ?? $default );
	}
	
	/*
	 * Has argument.
	 *
	 * @access Public
	 *
	 * @params String $arg
	 *
	 * @return Bool
	 */
	public function has( String $arg ): Bool
	{
		return( isset( $this->args[$arg] ) );
	}
	
	/*
	 * Parse command line argument values.
	 *
	 * @access Private
	 *
	 * @params Array $argv
	 *
	 * @return Void
	 */
	private function parse( ? Array $argv = Null )
	{
		// Get argument values.
		$argv = $argv ?? $_SERVER['argv'] ?? [];
		
		// Remove filename from argument values.
		array_shift( $argv );
		
		// Mapping argument values.
		foreach( $argv as $arg )
		{
			// If argument value is long option.
			if( substr( $arg, 0, 2 ) == "--" )
			{
				// If argument has equal symbol.
				$eqPos = strpos( $arg, "=" );
				
				// If argument has no equal symbol.
				if( $eqPos === False )
				{
					$key = substr( $arg,2 );
					$this->args[$key] = isset( $this->args[$key] ) ? $this->args[$key] : True;
				}
				else {
					$key = substr( $arg, 2, $eqPos -2 );
					$this->args[$key] = substr( $arg, $eqPos +1 );
				}
			}
			
			// If argument value is short option.
			else if( substr( $arg, 0, 1 ) == "-" )
			{
				// If argument has equal symbol.
				if( substr( $arg, 2, 1 ) == "=" )
				{
					$key = substr( $arg, 1, 1 );
					$this->args[$key] = substr( $arg, 3 );
				}
				else {
					
					$chars = str_split( substr( $arg, 1 ) );
					foreach( $chars As $char )
					{
						$key = $char;
						$this->args[$key] = isset( $this->args[$key] ) ? $this->args[$key] : True;
					}
				}
			}
			else {
				$this->args[$arg] = True;
			}
		}
	}
	
}

?>