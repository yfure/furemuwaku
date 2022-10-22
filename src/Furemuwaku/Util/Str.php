<?php

namespace Yume\Fure\Util;

use Stringable;

use Yume\Fure\Error;
use Yume\Fure\Support;

/*
 * Str<String>
 *
 * @package Yume\Fure\Util
 */
abstract class Str
{
	
	/*
	 * String formater.
	 *
	 * @access Public Static
	 *
	 * @params String $string
	 * @params Mixed $format
	 *
	 * @return String
	 */
	public static function fmt( String $string, Mixed ...$format ): String
	{
		return( Support\RegExp\RegExp::replace( "/(?:(?<format>(?<except>\\\{0,})(\{[\s\t]*((?<key>[a-zA-Z0-9_\x80-\xff]([a-zA-Z0-9_\.\x80-\xff]{0,}[a-zA-Z0-9_\x80-\xff]{1})*)|(?<index>[\d]+))*[\s\t]*\})))/i", $string, function( Array $match ) use( &$string, &$format )
		{
			// Statically variable.
			static $i = 0;
			
			// If backslash character exists.
			if( isset( $match['except'] ) && $match['except'] !== "" )
			{
				// If the number of backslashes is more than one.
				if( strlen( $match['except'] ) === 1 )
				{
					return( $match['format'] );
				}
				$match['except'] = str_repeat( "\\", strlen( $match['except'] ) -1 );
			}
			
			// If array key is matched.
			if( isset( $match['key'] ) )
			{
				return( self::parse( Arr::ify( $match['key'], $format ) ) );
			}
			
			// If array index is matched.
			if( isset( $match['index'] ) )
			{
				// Check if index is exists.
				if( isset( $format[$match['index']] ) )
				{
					return( self::parse( $match['except'] . $format[$match['index']] ) );
				}
				throw new Error\IndexError( $match['index'], Error\IndexError::RANGE_ERROR );
			}
			
			else {
				
				// Check if index iteration is exists.
				if( isset( $format[$i] ) )
				{
					return( self::parse( $match['except'] . $format[$i++] ) );
				}
				throw new Error\IndexError( $i++, Error\IndexError::RANGE_ERROR );
			}
			$i++;
		}));
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
		// If `args` value is null type.
		if( $args === Null ) return( "Null" );
		
		// If `args` value is boolean type.
		if( $args === True ) return( "True" );
		if( $args === False ) return( "False" );
		
		// If `args` value is array type.
		if( is_array( $args ) ) return( JSON::encode( $args, JSON_INVALID_UTF8_SUBSTITUTE | JSON_PRETTY_PRINT ) );
		
		// If `args` value is object type.
		if( is_object( $args ) )
		{
			// If `args` value is callable type.
			if( is_callable( $args ) )
			{
				return( self::parse( Support\Reflect\ReflectFunction::invoke( $data ) ) );
			}
			else {
				
				// Check if object is stringable.
				if( $args Instanceof Stringable )
				{
					// Parse object into string.
					return( $args->__toString() );
				}
				else {
					return( $args::class );
				}
			}
		}
		return( ( String ) $args );
	}
	
	public static function random( Int $length = 16 ): String
	{
		
	}
	
	public static function randomAlpha(): String
	{
		
	}
	
}

?>