<?php

namespace Yume\Fure\Support;

use Closure;
use Traversable;

use Yume\Fure\Error;
use Yume\Fure\Util;
use Yume\Fure\Util\Arr;

/*
 * Data
 *
 * @extends Yume\Fure\Util\Arr\Associative
 *
 * @package Yume\Fure\Support
 */
class Data extends Arr\Associative {
	
	/*
	 * Construct method of class Data.
	 *
	 * @access Public Initialize
	 *
	 * @params Array|Yume\Fure\Util\Arr\Arrayable $data
	 * @params Bool $insensitive
	 *
	 * @return Void
	 */
	public function __construct( Array | Arr\Arrayable | Traversable $data = [], Bool $insensitive = False ) {
		if( $data Instanceof Arr\Arrayable ) {
			$data = $data->__toArray();
		}
		if( $data Instanceof Traversable ) {
			$data = Util\Arrays::toArray( $data );
		}
		Util\Arrays::map( $data, static function( Int $i, Mixed $key, Mixed $value ) use( &$data ): Void {
			if( is_array( $value ) ) {
				$data[$key] = new Data( $value );
			}
		});
		parent::__construct( $data, $insensitive );
	}
	
	/*
	 * Triggered when invoking inaccessible methods in an object context.
	 *
	 * @access Public
	 *
	 * @params String $name
	 * @params Array $args
	 *
	 * @return Mixed
	 *
	 * @throws Yume\Fure\Eror\MethodError
	 */
	public function __call( String $name, Array $args ): Mixed {
		if( property_exists( $this, $name ) ) {
			if( $this->{ $name } Instanceof Closure ) {
				return( call_user_func_array( $this->{ $name }, $args ) );
			}
			throw new Error\MethodError( [ $name, __CLASS__ ], Error\MethodError::INVOKE_ERROR );
		}
		if( is_array( $this->data ) ) {
			if( isset( $this->data[$name] ) ) {
				if( $this->data[$name] Instanceof Closure ) {
					return( call_user_func( $this->data[$name], ...$args ) );
				}
				throw new Error\MethodError( [ $name, __CLASS__ ], Error\MethodError::INVOKE_ERROR );
			}
		}
		throw new Error\MethodError( [ $name, __CLASS__ ], Error\MethodError::NAME_ERROR );
	}
	
	/*
	 * Triggered when invoking inaccessible methods in a static context.
	 *
	 * @access Public Static
	 *
	 * @params String $name
	 * @params Array $args
	 *
	 * @return Mixed
	 *
	 * @throws Yume\Fure\Error\MethodError
	 */
	public static function __callStatic( String $name, Array $args ): Mixed {
		if( property_exists( __CLASS__, $name ) ) {
			return( call_user_func( self::${ $name }, ...$args ) );
		}
		throw new Error\MethodError( [ $name, __CLASS__ ], Error\MethodError::NAME_ERROR );
	}
	
	/*
	 * Copy data value.
	 *
	 * @access Public
	 *
	 * @return Static
	 */
	public function copy(): Static {
		return( new Data( $this->__toArray() ) );
	}
	
}

?>