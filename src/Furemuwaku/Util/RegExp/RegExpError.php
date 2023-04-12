<?php

namespace Yume\Fure\Util\RegExp;

use Throwable;

use Yume\Fure\Error;

/*
 * RegExpError
 *
 * @package Yume\Fure\Util\RegExp
 *
 * @extends Yume\Fure\Error\TypeError
 */
class RegExpError extends Error\TypeError
{
	
	/*
	 * If there was an internal PCRE error.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const INTERNAL_ERROR = PREG_INTERNAL_ERROR;
	
	/*
	 * If backtrack limit was exhausted.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const BACKTRACK_LIMIT_ERROR = PREG_BACKTRACK_LIMIT_ERROR;
	
	/*
	 * If recursion limit was exhausted.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const RECURSION_LIMIT_ERROR = PREG_RECURSION_LIMIT_ERROR;
	
	/*
	 * If the last error was caused by malformed UTF-8 data (only when running a regex in UTF-8 mode).
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const BAD_UTF8_ERROR = PREG_BAD_UTF8_ERROR;
	
	/*
	 * If the offset didn't correspond to the begin of a valid UTF-8 code point (only when running a regex in UTF-8 mode).
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const BAD_UTF8_OFFSET_ERROR = PREG_BAD_UTF8_OFFSET_ERROR;
	
	/*
	 * If the last PCRE function failed due to limited JIT stack space.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const JIT_STACKLIMIT_ERROR = PREG_JIT_STACKLIMIT_ERROR;
	
	/*
	 * Error constant for invalid modifier.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const MODIFIER_ERROR = 12816;
	public const MODIFIER_DUPLICATE_ERROR = 12982;
	
	/*
	 * @inherit Yume\Fure\Error\TypeError
	 *
	 */
	protected Array $flags = [
		self::MODIFIER_ERROR => "Regular Expression unknown modifier \"{}\", pattern \"{}\"",
		self::MODIFIER_DUPLICATE_ERROR => "Regular expressions cannot have the same multiple modifiers, flag \"{}\", pattern \"{}\""
	];
	
	/*
	 * @inherit Yume\Fure\Error\TypeError
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = 0, ? Throwable $previous = Null )
	{
		// Check if exception thrown in RegExp Namespace.
		if( preg_match( $regex = "/\/RegExp\/(Pattern|RegExp)\.php$/i", $this->getFile() ) )
		{
			// Mapping exception traces.
			foreach( $this->getTrace() As $i => $trace )
			{
				// Skip if class name has no Reflect Namespace.
				if( strpos( $trace['class'] ?? "", RegExp\RegExp::class ) === False ) continue;
				
				// Skip if file name has RegExp\RegExp.
				if( preg_match( $regex, $trace['file'] ?? "" ) ) continue;
				
				// If keys is exists.
				if( isset( $trace['function'] ) &&
					isset( $trace['file'] ) &&
					isset( $trace['type'] ) &&
					isset( $trace['line'] ) )
				{
					$this->file = $trace['file'];
					$this->line = $trace['line']; break;
				}
			}
		}
		parent::__construct( $message, $code, $previous );
	}
	
}

?>