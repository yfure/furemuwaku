<?php

namespace Yume\Fure\Util\RegExp;

use Stringable;

/*
 * Pattern
 *
 * @package Yume\Fure\Util\RegExp
 */
class Pattern implements Stringable
{
	
	/*
	 * Pattern of Regular Expression.
	 *
	 * @access Public Readonly
	 *
	 * @values String
	 */
	public Readonly String $pattern;
	
	/*
	 * Pattern Modifier of Regular Expression.
	 *
	 * @access Public Readonly
	 *
	 * @values String
	 */
	public Readonly ? String $flags;
	
	/*
	 * Construct method of class Pattern.
	 *
	 * @access Public Instance
	 *
	 * @params String $pattern
	 * @params String $flags
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\Util\RegExpError
	 */
	public function __construct( String $pattern, ? String $flags = Null )
	{
		// Check if pattern has flags.
		if( $flags )
		{
			// Valid flags.
			$valid = [
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
				"u" // PCRE_UTF8
			];
			
			// Split flags one caharacter.
			$splits = str_split( $flags, 1 );
			
			// Checked flags.
			$checked = [];
			
			// Mapping flags.
			foreach( $splits As $flag )
			{
				// Check if flags is available.
				if( in_array( $flag, $valid ) )
				{
					// Check if there are duplicate flag.
					if( in_array( $flag, $checked ) )
					{
						throw new RegExpError( [ $flag, $pattern ], RegExpError::MODIFIER_DUPLICATE_ERROR );
					}
					
					// Push checked flag.
					$checked[] = $flag;
				}
				else {
					throw new RegExpError( [ $flag, $pattern ], RegExpError::MODIFIER_ERROR );
				}
			}
		}
		
		$this->flags = $flags;
		$this->pattern = $pattern;
	}
	
	/*
	 * Parse class into string.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function __toString(): String
	{
		return( f( "/{}/{}", $this->pattern, $this->flags ?? "" ) );
	}
	
	/*
	 * Retrieves the result of matching a string against a regular expression.
	 *
	 * @access Public
	 *
	 * @params String $subject
	 *
	 * @return Array|Bool
	 */
	public function match( String $text ): Array | Bool
	{
		return( RegExp::match( $this->__toString(), $text ) );
	}
	
	/*
	 * Replace string with regexp.
	 *
	 * @access Public
	 *
	 * @params Array<String>|String $subject
	 * @params Array<Callable|String>|Callable|String $replace
	 *
	 * @return Array|String
	 */
	public function replace( Array | String $subject, Array | Callable | String $replace ): Array | String
	{
		return( RegExp::replace( $this->__toString(), $subject, $replace ) );
	}
	
	/*
	 * Perform a regular expression match.
	 *
	 * @access Public
	 *
	 * @params String $subject
	 *
	 * @return Bool
	 */
	public function test( String $text ): Bool
	{
		return( RegExp::test( $this->__toString(), $text ) );
	}
	
}

?>