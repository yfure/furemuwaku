<?php

namespace Yume\Fure\Util;

use ArrayAccess;

use Yume\Fure\Error;
use Yume\Fure\Support;
use Yume\Fure\Util\Array;

/*
 * Arrays
 *
 * @package Yume\Fure\Util
 */
final class Arrays
{
	
	/*
	 * Retrieve element values using dot as array separator.
	 *
	 * @access Public Static
	 *
	 * @params Array|String $refs
	 * @params Array|ArrayAccess $data
	 * @params Bool $throw
	 *  Allow throw exception when index or key not found.
	 *
	 * @return Mixed
	 *
	 * @throws Yume\Fure\Error\IndexError
	 *  Throw if index not found.
	 * @throws Yume\Fure\Error\KeyError
	 *  Throw if key not found.
	 */
	public static function ify( Array | String $refs, Array | ArrayAccess $data, Bool $throw = True ): Mixed
	{
		// If `refs` is string type.
		if( is_string( $refs ) ) $refs = self::ifySplit( $refs );
		
		// If `refs` length is zero or empty value.
		if( count( $refs ) === 0 ) return([]);
		
		foreach( $refs As $index )
		{
			if( isset( $stack ) )
			{
				// If key or index is exists.
				if( isset( $stack[$index] ) )
				{
					$stack = $stack[$index];
				}
				else {
					
					// Throw error if throw is allowed.
					if( $throw ) throw static::throw( $index, is_string( $index ) );
					
					// Just return null.
					return( Null );
				}
			}
			else {
				
				// If key or index is exists.
				if( isset( $data[$index] ) )
				{
					$stack = $data[$index];
				}
				else {
					
					// Throw error if throw is allowed.
					if( $throw ) throw static::throw( $index, is_string( $index ) );
					
					// Just return null.
					return( Null );
				}
			}
		}
		return( $stack ?? $data );
	}
	
	/*
	 * Join array elements with a period.
	 *
	 * @access Public Static
	 *
	 * @params Array $split
	 *
	 * @return String
	 */
	public static function ifyJoin( Array $split ): String
	{
		$result = "";
		$length = count( $split );
		
		foreach( $split As $index => $value )
		{
			/*
			 * Check if value is valid numeric character
			 * Or if character has period symbols the must
			 * Be enclose with square bracket.
			 *
			 */
			if( is_int( $value ) || is_int( strpos( $value, "." ) ) )
			{
				$result .= "[$value]";
			}
			else {
				$result .= $result === "" && $length -1 === $index ? $value : ( $result === "" ? $value : ".$value" );
			}
		}
		return( $result );
	}
	
	/*
	 * Split string with period.
	 *
	 * @access Public Static
	 *
	 * @params String $refer
	 *
	 * @return Array
	 */
	public static function ifySplit( String $refer ): Array
	{
		if( preg_match_all( "/(?<!\\\)(?:(?:\\[(?:[^\\]\\\]|\\.)*\\])|(?:[^\\.\\[\\\]+))/", $refer, $matches ) )
		{
			return( array_map( fn( String $value ) => is_numeric( $value = str_replace( [ "[", "]" ], "", $value ) ) ? ( Int ) $value : $value, $matches[0] ) );
		}
		return([]);
	}
	
	/*
	 * Return if array is Associative.
	 *
	 * @access Public Static
	 *
	 * @params Array $array
	 *
	 * @return Bool
	 */
	public static function isAssoc( Array $array, ? Bool $optional = Null ): Bool
	{
		return( $optional === Null ? self::isList( $array, False ) : self::isList( $array, False ) === $optional );
	}
	
	/*
	 * Return if array is List.
	 *
	 * @access Public Static
	 *
	 * @params Array $array
	 *
	 * @return Bool
	 */
	public static function isList( Array $array, ? Bool $optional = Null ): Bool
	{
		return( $optional === Null ? array_is_list( $array ) : array_is_list( $array ) === $optional );
	}
	
	/*
	 * Return if array is Multidimension.
	 *
	 * @access Public Static
	 *
	 * @params Array $array
	 *
	 * @return Bool
	 */
	public static function isMulti( Array $array, ? Bool $optional = Null ): Bool
	{
		return( $optional === Null ? count( array_filter( $array, "is_array" ) ) > 0 : self::isMulti( $array ) === $optional );
	}
	
	/*
	 * Array map.
	 *
	 * @access Public Static
	 *
	 * @params Array|String|Yume\Fure\Util\Array\Arrayable $array
	 * @params Callable $callback
	 *
	 * @return Array|Yume\Fure\Util\Array\Arrayable
	 */
	public static function map( Array | String | Array\Arrayable $array, Callable $callback ): Array | Array\Arrayable
	{
		// Call default map method if Array is Arrayable.
		if( $array Instanceof Array\Arrayable )
		{
			return( $array )->map( $callback );
		}
		else {
			
			// Split strings if value is string.
			if( is_string( $array ) )
			{
				$array = split( $array );
			}
			
			// Get array indexs.
			$indexs = array_keys( $array );
			$stack = [];
			
			// Mapping array.
			for( $i = 0; $i < count( $array ); $i++ )
			{
				try
				{
					// Get callback return value.
					$stack[$indexs[$i]] = call_user_func(
						
						// Callback handler.
						$callback,
						
						// Index iteration.
						$i,
						
						// Array key name.
						$indexs[$i],
						
						// Array value.
						$array[$indexs[$i]]
					);
				}
				catch( Support\Stoppable $stopped )
				{
					$stack[$indexs[$i]] = $stopped;
				}
				
				// Checks if further execution is terminated.
				if( $stack[$indexs[$i]] Instanceof Support\Stoppable )
				{
					$stack[$indexs[$i]] = $stack[$indexs[$i]]->value;
					break;
				}
			}
			return( $stack );
		}
	}
	
	/*
	 * Return error instance.
	 *
	 * @access Private Static
	 *
	 * @params Int|String $index
	 * @params Bool $isKey
	 *
	 * @return Yume\Fure\Error\LookupError
	 */
	private static function throw( Int | String $index, Bool $isKey ): Error\LookupError
	{
		if( $isKey )
		{
			return( new Error\KeyError( $index ) );
		}
		return( new Error\IndexError( $index ) );
	}
	
	/*
	 * Push array element any position.
	 *
	 * @access Public Static
	 *
	 * @params Array<Int|String>|Int|String $position
	 * @params Array $array
	 * @params Mixed $replace
	 *
	 * @return Array
	 */
	public static function push( Array | Int | String $position, Array $array, Mixed $replace ): Array
	{
		// Get array length.
		$length = count( $array );
		
		// If the array is empty with no contents.
		if( $length === 0 )
		{
			$array[] = $replace;
		}
		
		// If array length is smaller than position.
		else if( is_int( $position ) && $length -1 <= $position )
		{
			$array[] = $replace;
		}
		
		// If position string then this will overwrite the existing value.
		else if( is_string( $position ) )
		{
			$array[$position] = $replace;
		}
		else {
			
			// Looping iteration start.
			$i = 0;
			
			// Stack values.
			$stack = [];
			
			// To avoid stacking values, unset the array if it exists.
			if( is_array( $position ) )
			{
				unset( $array[$position[1]] );
			}
			
			// Mapping array.
			foreach( $array As $index => $value )
			{
				$i++;
				
				// If position is equal index iteration.
				if( is_int( $position ) && $i -1 === $position || is_array( $position ) && $i -1 === $position[0] )
				{
					// Set array element by position.
					$stack[( is_int( $position ) ? $i -1 : $position[1] )] = $replace;
					
					// Add next queue.
					foreach( $array As $k => $v )
					{
						$stack[( is_int( $k ) ? $k + 1 : $k )] = $v;
					}
					break;
				}
				
				// If position is more than number of array.
				else if( is_int( $position ) && $length < $position + 1 || is_array( $position ) && $length < $position[0] + 1 )
				{
					// Set array element by position.
					$stack[( is_int( $position ) ? $i -1 : $position[1] )] = $replace;
					
					// Add next queue.
					foreach( $array As $k => $v )
					{
						$stack[( is_int( $k ) ? $k + 1 : $k )] = $v;
					}
					break;
				}
				else {
					$stack[$index] = $value;
				}
				unset( $array[$index] );
			}
			return( $stack );
		}
		return( $array );
	}
	
	/*
	 * Unset multiple array elements based on array values.
	 *
	 * @access Public Static
	 *
	 * @params Array|ArrayAccess $array
	 * @params Mixed ...$values
	 *
	 * @return Void
	 */
	public static function unset( Array | ArrayAccess &$array, Mixed ...$values ): Void
	{
		// Mapping array values.
		self::map( $values, function( $i, $index, $value ) use( &$array )
		{
			// Mapping array data.
			self::map( $array, function( $i, $index, $target ) use( &$array, $value )
			{
				// If array value is equal target.
				if( $value === $target )
				{
					unset( $array[$index] );
				}
			});
		});
	}
	
}

?>