<?php

namespace Yume\Fure\CLI;

use Yume\Fure\Util;

/*
 * Console
 * 
 * @package Yume\Fure\CLI
 */
class Console {

	/*
	 * Print indicate error message.
	 * 
	 * @access Public Static
	 * 
	 * @params String $message
	 * @params Mixed ...$values
	 * 
	 * @return Void
	 */
	public static function error( String $string, Mixed ...$values ): Void {
		self::write( $string, Stdout::Error, ...$values );
	}

	public static function exit( Int $code, Stdout $level, String $string, Mixed ...$values ): Void {
		self::write( $string, $level, ...$values );
		exit( $code );
	}

	/*
	 * Print indicate info message.
	 * 
	 * @access Public Static
	 * 
	 * @params String $message
	 * @params Mixed ...$values
	 * 
	 * @return Void
	 */
	public static function info( String $string, Mixed ...$values ): Void {
		self::write( $string, Stdout::Info, ...$values );
	}

	/*
	 * Print indicate log message.
	 * 
	 * @access Public Static
	 * 
	 * @params String $message
	 * @params Mixed ...$values
	 * 
	 * @return Void
	 */
	public static function log( String $string, Mixed ...$values ): Void {
		self::write( $string, Stdout::Log, ...$values );
	}

	/*
	 * Print indicate success message.
	 * 
	 * @access Public Static
	 * 
	 * @params String $message
	 * @params Mixed ...$values
	 * 
	 * @return Void
	 */
	public static function success( String $string, Mixed ...$values ): Void {
		self::write( $string, Stdout::Success, ...$values );
	}

	/*
	 * Print indicate warning message.
	 * 
	 * @access Public Static
	 * 
	 * @params String $message
	 * @params Mixed ...$values
	 * 
	 * @return Void
	 */
	public static function warning( String $string, Mixed ...$values ): Void {
		self::write( $string, Stdout::Warning, ...$values );
	}

	/*
	 * Print message into terminal screen.
	 * 
	 * @access Public Static
	 * 
	 * @params String $message
	 * @params Mixed ...$values
	 * 
	 * @return Void
	 */
	public static function write( String $string, Mixed ...$values ): Void {
		// If Standard output level is available.
		if( isset( $values[0] ) && $values[0] Instanceof Stdout ) {
			// Change output level.
			$level = $values[0];

			// Unset level from values.
			unset( $values[0] );
		}

		$level ??= Stdout::Log;
		$format = "{color}{level:upper}\e[0m {string}{endline}";
		$params = [
			"endline" => PHP_EOL,
			"string" => $string,
			"color" => $level->value,
			"level" => $level->name
		];

		// If values is available.
		if( count( $values ) >= 1 ) {
			$stacks = array_map( fn( Mixed $value ) => Util\Strings::parse( $value ), $values );
			$stacks = join( ": ", $stacks );

			$format = "{color}{level:upper}\e[0m{endline}{stacks}: {string}{endline}";
			$params['stacks'] = $stacks;
		}
		echo( colorize( Util\Strings::format( $format, ...$params ) ) );
	}

}

?>
