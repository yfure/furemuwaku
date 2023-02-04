<?php

namespace Yume\Fure\CLI;

/*
 * CLIArgumentParser
 *
 * @package Yume\Fure\CLI
 */
abstract class CLIArgumentParser
{
	
	/*
	 * Argument values parsed.
	 *
	 * @access Static Private
	 *
	 * @values Array
	 */
	static private Array $args = [];
	
	/*
	 * Get value.
	 *
	 * @access Public Static
	 *
	 * @params String $arg
	 * @params Mixed $default
	 *
	 * @return Mixed
	 */
	public static function get( String $arg, Mixed $default = Null ): Mixed
	{
		return( static::$args[$arg] ?? $default );
	}
	
	/*
	 * Has argument.
	 *
	 * @access Public Static
	 *
	 * @params String $arg
	 *
	 * @return Bool
	 */
	public static function has( String $arg ): Bool
	{
		return( isset( static::$args[$arg] ) );
	}
	
	/*
	 * Parse command line argument values.
	 *
	 * @access Public Static
	 *
	 * @return Void
	 */
	public static function parse()
	{
		// Get argument values.
		$argv = $_SERVER['argv'] ?? [];
		
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
				if( $eqPos === false)
				{
					$key = substr( $arg,2 );
					static::$args[$key] = isset( static::$args[$key] ) ? static::$args[$key] : True;
				}
				else {
					$key = substr( $arg, 2, $eqPos -2 );
					static::$args[$key] = substr( $arg, $eqPos +1 );
				}
			}
			
			// If argument value is short option.
			else if( substr( $arg, 0, 1 ) == "-" )
			{
				// If argument has equal symbol.
				if( substr( $arg, 2, 1 ) == "=" )
				{
					$key = substr( $arg, 1, 1 );
					static::$args[$key] = substr( $arg, 3 );
				}
				else {
					$chars = str_split( substr( $arg, 1 ) );
					foreach( $chars as $char )
					{
						$key = $char;
						static::$args[$key] = isset( static::$args[$key] ) ? static::$args[$key] : True;
					}
				}
			}
			else {
				static::$args[] = $arg;
			}
		}
	}
	
}

?>