<?php

namespace Yume\Fure\Util\RegExp;

/*
 * RegExp
 *
 * @package Yume\Fure\Util\RegExp
 */
abstract class RegExp
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
	
	public static function clear( Array $matchs, Bool $capture = False ): Array
	{
		foreach( $matchs As $i => $match )
		{
			if( is_int( $i ) )
			{
				if( $i === 0 && $capture === False )
				{
					continue;
				}
				unset( $matchs[$i] );
			} else {
				if( is_array( $match ) )
				{
					foreach( $match As $u => $result )
					{
						$matchs[$i][$u] = $result !== "" ? $result : Null;
					}
				} else {
					$matchs[$i] = $match !== "" ? $match : Null;
				}
			}
		}
		return( $matchs );
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
		$patType = ucfirst( gettype( $pattern ) );
		$subType = ucfirst( gettype( $subject ) );
		$repType = ucfirst( gettype( $replace ) );
		
		// Arguments.
		$args = [ $pattern, $subject, $replace ];
		
		// Match result.
		$result = match( $subType )
		{
			"Array" => match( $patType )
			{
				"Array" => $repType === "Array" ? self::__replaceMultipleSubjectPatternAndReplacement( ...$args ) : self::__replaceMultipleSubjectAndPattern( ...$args ),
				"String" => $repType === "Array" ? self::__replaceMultipleSubjectAndReplacement( ...$args ) : self::__replaceMultipleSubject( ...$args )
			},
			"String" => match( True )
			{
				$patType === "String" && ( $repType === "String" || $repType === "Object" ) => self::__replaceSingle( ...$args ),
				$patType === "Array" && $repType === "Array" => self::__replaceMultiplePatternAndReplacement( ...$args ),
				$patType === "Array" => self::__replaceMultiplePattern( ...$args ),
				$repType === "Array" => self::__replaceMultipleReplacement( ...$args )
			}
		};
		
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