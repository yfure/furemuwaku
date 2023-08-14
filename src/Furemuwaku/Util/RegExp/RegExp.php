<?php

namespace Yume\Fure\Util\RegExp;

/*
 * RegExp
 *
 * @package Yume\Fure\Util\RegExp
 */
class RegExp
{
	
	/*
	 * Supported Regular Expression flags in PHP.
	 *
	 * @access Public Static
	 *
	 * @values Array
	 */
	public const FLAGS = [
		"i", // PCRE_CASELESS
		"m", // PCRE_MULTILINE
		"s", // PCRE_DOTALL
		"x", // PCRE_EXTENDED
		"A", // PCRE_ANCHORED
		"D", // PCRE_DOLLAR_ENDONLY
		"S",
		"U", // PCRE_UNGREEDY
		"X", // PCRE_EXTRA
		"J", // PCRE_INFO_JCHANGED
		"u"  // PCRE_UTF8
	];
	
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
		if( valueIsNotEmpty( $matches = @preg_grep( $pattern, $array, $flags ) ) )
		{
			return( $matches );
		}
		return( self::throws( False ) );	
	}
	
	/*
	 * Return if given flags is supported by PHP.
	 *
	 * @access Public Static
	 *
	 * @params String $flag
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public static function isFlag( String $flag, ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? self::isFlag( $flag ) === $optional : in_array( $flag, self::FLAGS ) );
	}
	
	/*
	 * Retrieves the result of matching a string against a regular expression.
	 *
	 * @access Public Static
	 *
	 * @params String $pattern
	 * @params String $subject
	 * @params Int $flags
	 *
	 * @return Array|Bool
	 */
	public static function match( String $pattern, String $subject, Int $flags = 0 ): Array | Bool
	{
		// Check if match hash no errors.
		if( @preg_match( $pattern, $subject, $matches, PREG_UNMATCHED_AS_NULL | $flags ) )
		{
			return( $matches );
		}
		return( self::throws( False ) );
	}
	
	/*
	 * Executes a search for a match in a specified string.
	 *
	 * @access Public Static
	 *
	 * @params String $pattern
	 * @params String $subject
	 * @params Int $flags
	 *
	 * @return Array|Bool
	 */
	public static function matchs( String $pattern, String $subject, Int $flags = PREG_SET_ORDER ): Array | Bool
	{
		// Check if match hash no errors.
		if( @preg_match_all( $pattern, $subject, $matches, PREG_UNMATCHED_AS_NULL | $flags ) )
		{
			return( $matches );
		}
		return( self::throws( False ) );
	}
	
	/*
	 * Replace string with regexp.
	 *
	 * @access Public Static
	 *
	 * @params Array<String>|String $pattern
	 * @params Array<String>|String $subject
	 * @params Array<Callable|String>|Callable|String $replace
	 * @params Int $limit
	 * @parans Int &$count
	 * @params Int $flags
	 *
	 * @return Array|String
	 */
	public static function replace( Array | String $pattern, Array | String $subject, Array | Callable | String $replace, Int $limit = -1, Int &$count = Null, Int $flags = 0 ): Array | String
	{
		// Get types.
		$patType = gettype( $pattern );
		$subType = gettype( $subject );
		$repType = gettype( $replace );
		
		// Arguments.
		$args = [ $pattern, $subject, $replace ];
		
		// Match result
		$result = match( True )
		{
			$subType === "\x61\x72\x72\x61\x79" => match( $patType )
			{
				"\x61\x72\x72\x61\x79" => $repType === "\x61\x72\x72\x61\x79" ? self::__replaceMultipleSubjectPatternAndReplacement( ...$args ) : self::__replaceMultipleSubjectAndPattern( ...$args ),
				"\x73\x74\x72\x69\x6e\x67" => $repType === "\x61\x72\x72\x61\x79" ? self::__replaceMultipleSubjectAndReplacement( ...$args ) : self::__replaceMultipleSubject( ...$args )
			},
			default => match( True )
			{
				$patType === "\x73\x74\x72\x69\x6e\x67" && ( $repType === "\x73\x74\x72\x69\x6e\x67" || $repType === "\x6f\x62\x6a\x65\x63\x74" ) => self::__replaceSingle( ...$args ),
				$patType === "\x61\x72\x72\x61\x79" && $repType === "\x61\x72\x72\x61\x79" => self::__replaceMultiplePatternAndReplacement( ...$args ),
				$patType === "\x61\x72\x72\x61\x79" => self::__replaceMultiplePattern( ...$args ),
				$repType === "\x61\x72\x72\x61\x79" => self::__replaceMultipleReplacement( ...$args )
			}
		};
		return( self::throws( $result ) );
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
		return( self::throws( ( Bool ) preg_match( $pattern, $subject ) ) );
	}
	
	/*
	 * Throws error when the something is wrong.
	 *
	 * @access Public Static
	 *
	 * @params Mixed $result
	 *
	 * @return Mixed
	 */
	final public static function throws( Mixed $result = Null ): Mixed
	{
		if( self::errno() )
		{
			throw new RegExpError( self::error(), self::errno() );
		}
		return( $result );
	}
	
	/*
	 * Replace multiples.
	 *
	 * @access Private Static
	 *
	 * @params Array<String> $pattern
	 * @params Array<String> $subject
	 * @params Array<Callable|String> $replace
	 * @params Mixed ...$options
	 *
	 * @return Array
	 */
	private static function __replaceMultipleSubjectPatternAndReplacement( Array $pattern, Array $subject, Array $replace, Mixed ...$options ): ? Array
	{
		// Mapping subjects.
		for( $i = 0; $i < count( $subject ); $i++ )
		{
			$subject[$i] = self::__replaceSingle( $pattern[$i], $subject[$i], $replace[$i], ...$options );
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
	 * @params Mixed ...$options
	 *
	 * @return Array
	 */
	private static function __replaceMultipleSubjectAndPattern( Array $pattern, Array $subject, Callable | String $replace, Mixed ...$options ): ? Array
	{
		// Mapping subjects.
		for( $i = 0; $i < count( $subject ); $i++ )
		{
			$subject[$i] = self::__replaceSingle( $pattern[$i], $subject[$i], $replace, ...$options );
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
	 * @params Mixed ...$options
	 *
	 * @return Array
	 */
	private static function __replaceMultipleSubjectAndReplacement( String $pattern, Array $subject, Array $replace, Mixed ...$options ): ? Array
	{
		// Mapping subjects.
		for( $i = 0; $i < count( $subject ); $i++ )
		{
			$subject[$i] = self::__replaceSingle( $pattern, $subject[$i], $replace[$i], ...$options );
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
	 * @params Mixed ...$options
	 *
	 * @return Array
	 */
	private static function __replaceMultipleSubject( String $pattern, Array $subject, Callable | String $replace, Mixed ...$options ): ? Array
	{
		// Mapping subjects.
		for( $i = 0; $i < count( $subject ); $i++ )
		{
			$subject[$i] = self::__replaceSingle( $pattern, $subject[$i], $replace, ...$options );
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
	 * @params Mixed ...$options
	 *
	 * @return String
	 */
	private static function __replaceMultiplePatternAndReplacement( Array $pattern, String $subject, Array $replace, Mixed ...$options ): ? String
	{
		// Mapping patterns.
		for( $i = 0; $i < count( $pattern ); $i++ )
		{
			$subject = self::__replaceSingle( $pattern[$i], $subject, $replace[$i], ...$options );
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
	 * @params Mixed ...$options
	 *
	 * @return String
	 */
	private static function __replaceMultiplePattern( Array $pattern, String $subject, Callable | String $replace, Mixed ...$options ): ? String
	{
		// Mapping patterns.
		for( $i = 0; $i < count( $pattern ); $i++ )
		{
			$subject = self::__replaceSingle( $pattern[$i], $subject, $replace, ...$options );
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
	 * @params Mixed ...$options
	 *
	 * @return String
	 */
	private static function __replaceMultipleReplacement( String $pattern, String $subject, Array $replace, Mixed ...$options ): ? String
	{
		// Mapping replaces.
		for( $i = 0; $i < count( $replace ); $i++ )
		{
			$subject = self::__replaceSingle( $pattern, $subject, $replace[$i], ...$options );
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
	 * @params Mixed ...$options
	 *
	 * @return String
	 */
	private static function __replaceSingle( String $pattern, String $subject, Callable | String $replace, Mixed ...$options ): ? String
	{
		// Check if replacement is callable.
		if( is_callable( $replace ) )
		{
			// Replace subject with replacement callback.
			return( preg_replace_callback( $pattern, $replace, $subject, ...$options ) );
		}
		
		// Replace subject with replacemenent.
		return( preg_replace( $pattern, $replace, $subject, ...$options ) );
	}
	
}

?>