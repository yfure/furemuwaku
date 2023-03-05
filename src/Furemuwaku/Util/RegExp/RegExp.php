<?php

namespace Yume\Fure\Util\RegExp;

/*
 * RegExp
 *
 * @package Yume\Fure\Util\RegExp
 */
final class RegExp
{
	
	/*
	 * @inherit https://www.php.net/manual/en/function.preg-last-error.php
	 *
	 */
	public static function errno(): Int
	{
		return( preg_last_error() );
	}
	
	/*
	 * @inherit https://www.php.net/manual/en/function.preg-last-error-msg.php
	 *
	 */
	public static function error(): String
	{
		return( preg_last_error_msg() );
	}
	
	/*
	 * Get last match error type.
	 *
	 * @access Public Static
	 *
	 * @return String
	 */
	public static function etype(): String
	{
		return( match( self::errno() )
		{
			0 => "PREG_NO_ERROR",
			1 => "PREG_INTERNAL_ERROR",
			2 => "PREG_BACKTRACK_LIMIT_ERROR",
			3 => "PREG_RECURSION_LIMIT_ERROR",
			4 => "PREG_BAD_UTF8_ERROR",
			5 => "PREG_BAD_UTF8_OFFSET_ERROR",
			6 => "PREG_JIT_STACKLIMIT_ERROR"
		});
	}
	
	/*
	 * Clear match results, only keep result with key and value.
	 * In there indexed array elements will be remove from array.
	 *
	 * @access Public Static
	 *
	 * @params Array $matchs
	 * @params Bool $capture
	 *
	 * @return Array
	 */
	public static function clear( Array $matchs, Bool $capture = False ): Array
	{
		// Mapping all matched results.
		foreach( $matchs As $i => $match )
		{
			// Check if key is Int type.
			if( is_int( $i ) )
			{
				// Skip if all captured will be keep.
				if( $i === 0 && $capture === False ) continue;
				
				// Unset captured match result.
				unset( $matchs[$i] );
			}
			else {
				
				// Check if match value is Array.
				if( is_array( $match ) )
				{
					// Mapping all result match values.
					array_map( fn( Int $i, Int $u, ? String $result ) => $matchs[$i][$u] = $result !== "" ? $result : Null, $match );
				}
				else {
					$matchs[$i] = $match !== "" ? $match : Null;
				}
			}
		}
		return( $matchs );
	}
	
	/*
	 * Return array entries that match the pattern.
	 *
	 * @access Public Static
	 *
	 * @params String $pattern
	 * @params Array $array
	 * @params Int $flags
	 *
	 * @return Array|False
	 */
	public static function grep( String $pattern, Array $array, Int $flags = 0 ): Array | False
	{
		// ...
	}
	
	/*
	 * Retrieves the result of matching a string against a regular expression.
	 *
	 * @access Public Static
	 *
	 * @params String $pattern
	 * @params String $subject
	 * @params Bool $clear
	 *
	 * @return Array|Bool
	 */
	public static function match( String $pattern, String $subject, Bool $clear = False ): Array | Bool
	{
		$matchs = [];
		
		// Check if match hash no errors.
		if( preg_match( $pattern, $subject, $matchs, PREG_UNMATCHED_AS_NULL ) )
		{
			return( $clear ? self::clear( $matchs ) : $matchs );
		}
		
		// Check if an error occurred.
		if( self::errno() )
		{
			throw new RegExpError( self::error(), self::errno() );
		}
		return( False );
	}
	
	/*
	 * Executes a search for a match in a specified string.
	 *
	 * @access Public Static
	 *
	 * @params String $pattern
	 * @params String $subject
	 * @params Bool $clear
	 *
	 * @return Array|Bool
	 */
	public static function matchs( String $pattern, String $subject, Bool $clear = False ): Array | Bool
	{
		// Match results.
		$matchs = [];
		
		// Check if match hash no errors.
		if( preg_match_all( $pattern, $subject, $matchs, PREG_SET_ORDER || PREG_UNMATCHED_AS_NULL ) )
		{
			return( $clear ? self::clear( $matchs ) : $matchs );
		}
		
		// Check if an error occurred.
		if( self::errno() )
		{
			throw new RegExpError( self::error(), self::errno() );
		}
		return( False );
	}
	
	/*
	 * Replace string with regexp.
	 *
	 * @access Public Static
	 *
	 * @params Array<String>|String $pattern
	 * @params Array<String>|String $subject
	 * @params Array<Callable|String>|Callable|String $replace
	 *
	 * @return Array|String
	 */
	public static function replace( Array | String $pattern, Array | String $subject, Array | Callable | String $replace/*, Bool $clear = False */ ): Array | String
	{
		// Get types.
		$patType = gettype( $pattern );
		$subType = gettype( $subject );
		$repType = gettype( $replace );
		
		// Arguments.
		$args = [ $pattern, $subject, $replace ];
		
		// Match result.
		if( $subType === "\x61\x72\x72\x61\x79" )
		{
			$result = match( $patType )
			{
				"\x61\x72\x72\x61\x79" => $repType === "\x61\x72\x72\x61\x79" ? self::__replaceMultipleSubjectPatternAndReplacement( ...$args ) : self::__replaceMultipleSubjectAndPattern( ...$args ),
				"\x73\x74\x72\x69\x6e\x67" => $repType === "\x61\x72\x72\x61\x79" ? self::__replaceMultipleSubjectAndReplacement( ...$args ) : self::__replaceMultipleSubject( ...$args )
			};
		}
		else {
			$result = match( True )
			{
				$patType === "\x73\x74\x72\x69\x6e\x67" && ( $repType === "\x73\x74\x72\x69\x6e\x67" || $repType === "\x6f\x62\x6a\x65\x63\x74" ) => self::__replaceSingle( ...$args ),
				$patType === "\x61\x72\x72\x61\x79" && $repType === "\x61\x72\x72\x61\x79" => self::__replaceMultiplePatternAndReplacement( ...$args ),
				$patType === "\x61\x72\x72\x61\x79" => self::__replaceMultiplePattern( ...$args ),
				$repType === "\x61\x72\x72\x61\x79" => self::__replaceMultipleReplacement( ...$args )
			};
		}
		
		// Check if an error occurred.
		if( self::errno() )
		{
			throw new RegExpError( self::error(), self::errno() );
		}
		
		// Return replace results.
		return( $result );
	}
	
	/*
	 * Perform a regular expression match.
	 *
	 * @access Public Static
	 *
	 * @params String $pattern
	 * @params String $subject
	 *
	 * @return Bool
	 */
	public static function test( String $pattern, String $subject ): Int | Bool
	{
		// Parse preg-match return into boolean type.
		$match = ( ( Bool ) preg_match( $pattern, $subject ) );
		
		// Check if an error occurred.
		if( self::errno() )
		{
			throw new RegExpError( self::error(), self::errno() );
		}
		
		return( $match );
	}
	
	/*
	 * Replace multiples.
	 *
	 * @access Private Static
	 *
	 * @params Array<String> $pattern
	 * @params Array<String> $subject
	 * @params Array<Callable|String> $replace
	 *
	 * @return Array
	 */
	private static function __replaceMultipleSubjectPatternAndReplacement( Array $pattern, Array $subject, Array $replace ): Array
	{
		// Mapping subjects.
		for( $i = 0; $i < count( $subject ); $i++ )
		{
			$subject[$i] = self::__replaceSingle( $pattern[$i], $subject[$i], $replace[$i] );
		}
		return( $subject );
	}
	
	/*
	 * Replace mutiple subject and pattern.
	 *
	 * @access Private Static
	 *
	 * @params Array<String> $pattern
	 * @params Array<String> $subject
	 * @params Callable|String $replace
	 *
	 * @return Array
	 */
	private static function __replaceMultipleSubjectAndPattern( Array $pattern, Array $subject, Callable | String $replace ): Array
	{
		// Mapping subjects.
		for( $i = 0; $i < count( $subject ); $i++ )
		{
			$subject[$i] = self::__replaceSingle( $pattern[$i], $subject[$i], $replace );
		}
		return( $subject );
	}
	
	/*
	 * Replace multiple subject and replacement.
	 *
	 * @access Private Static
	 *
	 * @params String $pattern
	 * @params Array<String> $subject
	 * @params Array<Callable|String> $replace
	 *
	 * @return Array
	 */
	private static function __replaceMultipleSubjectAndReplacement( String $pattern, Array $subject, Array $replace ): Array
	{
		// Mapping subjects.
		for( $i = 0; $i < count( $subject ); $i++ )
		{
			$subject[$i] = self::__replaceSingle( $pattern, $subject[$i], $replace[$i] );
		}
		return( $subject );
	}
	
	/*
	 * Replace multiple subject.
	 *
	 * @access Private Static
	 *
	 * @params String $pattern
	 * @params Array<String> $subject
	 * @params Callable|String $replace
	 *
	 * @return Array
	 */
	private static function __replaceMultipleSubject( String $pattern, Array $subject, Callable | String $replace ): Array
	{
		// Mapping subjects.
		for( $i = 0; $i < count( $subject ); $i++ )
		{
			$subject[$i] = self::__replaceSingle( $pattern, $subject[$i], $replace );
		}
		return( $subject );
	}
	
	/*
	 * Replace multiple pattern and replacement.
	 *
	 * @access Private Static
	 *
	 * @params Array<String> $pattern
	 * @params String $subject
	 * @params Array<Callable|String> $replace
	 *
	 * @return String
	 */
	private static function __replaceMultiplePatternAndReplacement( Array $pattern, String $subject, Array $replace ): String
	{
		// Mapping patterns.
		for( $i = 0; $i < count( $pattern ); $i++ )
		{
			$subject = self::__replaceSingle( $pattern[$i], $subject, $replace[$i] );
		}
		return( $subject );
	}
	
	/*
	 * Replace multiple pattern.
	 *
	 * @access Private Static
	 *
	 * @params Array<String> $pattern
	 * @params String $subject
	 * @params Callable|String $replace
	 *
	 * @return String
	 */
	private static function __replaceMultiplePattern( Array $pattern, String $subject, Callable | String $replace ): String
	{
		// Mapping patterns.
		for( $i = 0; $i < count( $pattern ); $i++ )
		{
			$subject = self::__replaceSingle( $pattern[$i], $subject, $replace );
		}
		return( $subject );
	}
	
	/*
	 * Replace multiple replacement.
	 *
	 * @access Private Static
	 *
	 * @params String $pattern
	 * @params String $subject
	 * @params Array<Callable|String> $replace
	 *
	 * @return String
	 */
	private static function __replaceMultipleReplacement( String $pattern, String $subject, Array $replace ): String
	{
		// Mapping replaces.
		for( $i = 0; $i < count( $replace ); $i++ )
		{
			$subject = self::__replaceSingle( $pattern, $subject, $replace[$i] );
		}
		return( $subject );
	}
	
	/*
	 * Replace single.
	 *
	 * @access Private Static
	 *
	 * @params String $pattern
	 * @params String $subject
	 * @params Callable|String $replace
	 *
	 * @return String
	 */
	private static function __replaceSingle( String $pattern, String $subject, Callable | String $replace ): String
	{
		// Check if replacement is callable.
		if( is_callable( $replace ) )
		{
			// Replace subject with replacement callback.
			return( preg_replace_callback( $pattern, $replace, $subject ) );
		}
		
		// Replace subject with replacemenent.
		return( preg_replace( $pattern, $replace, $subject ) );
	}
	
}

?>