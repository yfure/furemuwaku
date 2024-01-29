<?php

namespace Yume\Fure\Util;

use ArrayAccess;
use Traversable;

use Yume\Fure\Error;
use Yume\Fure\Support;
use Yume\Fure\Util\Arr;

/*
 * Arrays
 *
 * @package Yume\Fure\Util
 */
final class Arrays {
	
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
	public static function ify( Array | String $refs, Array | ArrayAccess $data, Bool $throw = True ): Mixed {
		if( is_string( $refs ) ) {
			$refs = self::ifySplit( $refs );
		}
		if( count( $refs ) === 0 ) {
			return([]);
		}
		foreach( $refs As $index ) {
			if( isset( $stack ) ) {
				if( isset( $stack[$index] ) ) {
					$stack = $stack[$index];
				}
				else {
					if( $throw ) {
						throw static::throw( $index, is_string( $index ) );
					}
					return( Null );
				}
			}
			else {
				if( isset( $data[$index] ) ) {
					$stack = $data[$index];
				}
				else {
					if( $throw ) {
						throw static::throw( $index, is_string( $index ) );
					}
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
	public static function ifyJoin( Array $split ): String {
		$result = "";
		$length = count( $split );
		
		foreach( $split As $index => $value ) {
			
			/*
			 * Check if value is valid numeric character
			 * Or if character has period symbols the must
			 * Be enclose with square bracket.
			 *
			 */
			if( is_int( $value ) || is_int( strpos( $value, "." ) ) ) {
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
	public static function ifySplit( String $refer ): Array {
		if( preg_match_all( "/(?<!\\\)(?:(?:\\[(?:[^\\]\\\]|\\.)*\\])|(?:[^\\.\\[\\\]+))/", $refer, $matches ) ) {
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
	public static function isAssoc( Array $array, ? Bool $optional = Null ): Bool {
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
	public static function isList( Array $array, ? Bool $optional = Null ): Bool {
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
	public static function isMulti( Array $array, ? Bool $optional = Null ): Bool {
		return( $optional === Null ? count( array_filter( $array, "is_array" ) ) > 0 : self::isMulti( $array ) === $optional );
	}
	
	/*
	 * Array map.
	 *
	 * @access Public Static
	 *
	 * @params Array|String|Yume\Fure\Util\Arr\Arrayable $array
	 * @params Callable $callback
	 *
	 * @return Array|Yume\Fure\Util\Arr\Arrayable
	 */
	public static function map( Array | String | Arr\Arrayable $array, Callable $callback ): Array | Arr\Arrayable {
		if( $array Instanceof Arr\Arrayable ) {
			return( $array )->map( $callback );
		}
		else {
			if( is_string( $array ) ) {
				$array = split( $array );
			}
			$indexs = array_keys( $array );
			$stack = [];
			for( $i = 0; $i < count( $array ); $i++ ) {
				try {
					$stack[$indexs[$i]] = call_user_func( $callback, $i, $indexs[$i], $array[$indexs[$i]] );
				}
				catch( Support\Stoppable $stopped ) {
					$stack[$indexs[$i]] = $stopped;
				}
				if( $stack[$indexs[$i]] Instanceof Support\Stoppable ) {
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
	private static function throw( Int | String $index, Bool $isKey ): Error\LookupError {
		if( $isKey ) {
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
	public static function push( Array | Int | String $position, Array $array, Mixed $replace ): Array {
		$length = count( $array );
		if( $length === 0 ) {
			$array[] = $replace;
		}
		else if( is_int( $position ) && $length -1 <= $position ) {
			$array[] = $replace;
		}
		else if( is_string( $position ) ) {
			$array[$position] = $replace;
		}
		else {
			$i = 0;
			$stack = [];
			if( is_array( $position ) ) {
				unset( $array[$position[1]] );
			}
			foreach( $array As $index => $value ) {
				$i++;
				if( is_int( $position ) && $i -1 === $position || is_array( $position ) && $i -1 === $position[0] ) {
					$stack[( is_int( $position ) ? $i -1 : $position[1] )] = $replace;
					foreach( $array As $k => $v ) {
						$stack[( is_int( $k ) ? $k + 1 : $k )] = $v;
					}
					break;
				}
				else if( is_int( $position ) && $length < $position + 1 || is_array( $position ) && $length < $position[0] + 1 ) {
					$stack[( is_int( $position ) ? $i -1 : $position[1] )] = $replace;
					foreach( $array As $k => $v ) {
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
	 * Parse traversable into array.
	 * 
	 * @access Public Static
	 * 
	 * @params Traversable $value
	 * 
	 * @return Array
	 */
	public static function toArray( Traversable $value ): Array {
		if( $value Instanceof Arr\Arrayable ) {
			return( $value->__toArray() );
		}
		return( iterator_to_array( $value ) );
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
	public static function unset( Array | ArrayAccess &$array, Mixed ...$values ): Void {
		self::map( $values, function( $i, $index, $value ) use( &$array ) {
			self::map( $array, function( $i, $index, $target ) use( &$array, $value ) {
				if( $value === $target ) {
					unset( $array[$index] );
				}
			});
		});
	}
	
}

?>