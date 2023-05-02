<?php

namespace Yume\Fure\Util;

/*
 * Strings
 *
 * @package Yume\Fure\Util
 */
class Strings
{
	
	/*
	 * Letter Trait Utilities.
	 *
	 * @include CamelCase
	 * @include HungarianCase
	 * @include KebabCase
	 * @include PascalCase
	 * @include SnakeCase
	 * @include UpperCamelCase
	 * @include UpperCase
	 * @include VerbObjectCase
	 */
	use \Yume\Fure\Util\Letter\CamelCase;
	use \Yume\Fure\Util\Letter\HungarianCase;
	use \Yume\Fure\Util\Letter\KebabCase;
	use \Yume\Fure\Util\Letter\PascalCase;
	use \Yume\Fure\Util\Letter\SnakeCase;
	use \Yume\Fure\Util\Letter\SpaceCase;
	use \Yume\Fure\Util\Letter\UpperCamelCase;
	use \Yume\Fure\Util\Letter\UpperCase;
	use \Yume\Fure\Util\Letter\VerbObjectCase;
	
	/*
	* String formater.
	*
	* To reduce risk it is not recommended to manage
	* user input using this method and do not use it
	* to manage long strings that contain lots of
	* formatting stuff as this method relies heavily
	* on Regular Expressions which are long enough to
	* capture every supported syntax.
	*
	* @access Public Static
	*
	* @params String $format
	* @params Mixed ...$values
	*
	* @allows Iteration Replacement.
	* @syntax {}
	*
	* @allows Increment Replacement.
	* @syntax +
	* @syntax ++
	*
	* @allows Decrement Replacement.
	* @syntax -
	* @syntax --
	*
	* @allows Array Indexed Replacement.
	* @syntax [0-9]+
	*
	* @allows Array Asocciative Replacement.
	* @syntax [a-zA-Z0-9_]+
	*
	* @allows Callback Function Replacement.
	* @syntax [a-zA-Z0-9_](+|++|-|--|{\}|[key]+|[index]+)
	*
	* @allows Callback Static-Method Replacement.
	* @syntax [a-zA-Z0-9_]+::([a-zA-Z0-9_](+|++|-|--|{\}|[key]+|[index]+)
	*
	* @allows Method.
	* @syntax Increment|Decrement:[a-zA-Z0-9_]+
	* @syntax Array-Indexed|Asocciative:[a-zA-Z0-9_]+
	* @syntax Function|Static-Method:[a-zA-Z0-9_]+
	*
	* @return String
	*/
	public static function format( String $format, Mixed ...$value ): String
	{
		
	}
	
	/*
	 * Parses any data type to string.
	 *
	 * @access Public Static
	 *
	 * @params Mixed $args
	 *
	 * @return String
	 */
	public static function parse( Mixed $args ): String
	{
		return( match( True )
		{
			// If `args` value is Null type.
			$args === Null => "Null",
			
			// If `args` value is Boolean type.
			$args === True => "True",
			$args === False => "False",
			
			// If `args` value is Array type.
			is_array( $args ) => Json\Json::encode( $args, JSON_INVALID_UTF8_SUBSTITUTE | JSON_PRETTY_PRINT ),
			
			// If `args` value is Object type.
			is_object( $args ) => is_callable( $args ) ? self::parse( Reflect\ReflectFunction::invoke( $args ) ) : ( $args Instanceof Stringable ? $args->__toString() : $args::class ),
			
			// Auto convert.
			default => ( String ) $args
		});
	}
	
	/*
	 * Remove last string with separator.
	 *
	 * @access Public Static
	 *
	 * @params String $subject
	 * @params String $separator
	 * @params Bool $last
	 * @params Mixed &$ref
	 *
	 * @return String
	 */
	public static function pop( String $subject, String $separator, Bool $last = False, Mixed &$ref = Null ): String
	{
		if( count( $split = explode( $separator, $subject ) ) !== 0 )
		{
			$end = array_pop( $split );
			
			if( $last )
			{
				$ref = [
					$string = implode( $separator, $split ),
					$end
				];
				return( $end );
			}
		}
		$ref = [
			$string = implode( $separator, $split ),
			$end ?? Null
		];
		return( $string );
	}
	
	/*
	 * Remove first string with separator.
	 *
	 * @access Public Static
	 *
	 * @params String $subject
	 * @params String $separator
	 * @params Bool $shift
	 * @params Mixed &$ref
	 *
	 * @return String
	 */
	public static function shift( String $subject, String $separator, Bool $shift = False, Mixed &$ref = Null ): String
	{
		if( count( $split = explode( $separator, $subject ) ) !== 0 )
		{
			$first = array_shift( $split );
			
			if( $shift )
			{
				$ref = [
					$string = implode( $separator, $split ),
					$first
				];
				return( $first );
			}
		}
		$ref = [
			$string = implode( $separator, $split ),
			$first ?? Null
		];
		return( $string );
	}
	
}

?>