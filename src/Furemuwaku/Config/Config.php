<?php

namespace Yume\Fure\Config;

use Yume\Fure\Support;
use Yume\Fure\Util;
use Yume\Fure\Util\Arr;

/*
 * Config
 *
 * @extends Yume\Fure\Support\Data
 *
 * @package Yume\Fure\Config
 */
class Config extends Support\Data {
	
	/*
	 * Other configuration instance.
	 *
	 * @access Private
	 *
	 * @values Array
	 */
	private Array $inherit = [];
	
	/*
	 * Construct method of class Config.
	 *
	 * @access Public Initialize
	 *
	 * @params String $name
	 *  Configuration name.
	 * @params Bool $shared
	 *  Allow configuration for reuse.
	 * @params Array $configs
	 *  Configuration settings.
	 *
	 * @return Void
	 */
	final public function __construct( public Readonly String $name, public Readonly Bool $shared, Array $configs ) {
		if( isset( $configs['[inherit]'] ) ) {
			$this->__inherit( $configs['[inherit]'] );
		}
		Util\Arrays::map( $configs, static function( Int $i, Mixed $key, Mixed $value ) use( $name, $shared, &$configs ): Void {
			if( $key === "[inherit]" ) {
				unset( $configs[$key] );
			}
			else {
				if( is_array( $value ) ) {
					$configs[$key] = new Config( $name, $shared, $value );
				}
			}
		});
		parent::__construct( $configs );
	}
	
	/*
	 * Inject other configuration.
	 *
	 * @access Private
	 *
	 * @params Mixed $inherits
	 *
	 * @return Void
	 */
	private function __inherit( Mixed $inherits ): Void {
		if( is_array( $inherits ) ) {
			foreach( $inherits As $name => $value ) {
				if( is_string( $value ) ) {
					$name = strtolower( $value );
					if( $name !== strtolower( $this->name ) ) {
						$this->inherit[$name] = config( $name, new Config( $this->name, $this->shared, [] ) );
					}
				}
				else if( $value Instanceof Config ) {
					$this->inherit[strtolower( $value->name )] = $value;
				}
			}
		}
		if( is_string( $name = $inherits ) ) {
			$name = strtolower( $name );
			if( $name !== strtolower( $this->name ) ) {
				$this->inherit[$name] = config( $name );
			}
		}
	}
	
	/*
	 * @inherit Yume\Fure\Util\Arr\Arrayable::__toArray
	 *
	 */
	final function __toArray(): Array {
		foreach( $this->inherit As $inherit ) {
			$data ??= [];
			$data = [ ...$data, ...$inherit->__toArray() ];
		}
		return array_replace_recursive( $data ?? [], parent::__toArray() );
	}
	
	/*
	 * Parse class to string.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	final public function __toString(): String {
		return Util\Strings::format( "{}<{}>({})", $this::class, $this->name, $this->__toArray() );
	}

	/*
	 * @inherit Yume\Fure\Suport\Data::copy
	 */
	public function copy(): Static {
		return new Config( $this->name, $this->shared, $this->__toArray() );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Arr\Arrayable::map
	 *
	 */
	final public function map( Callable $callback ): Static {
		$stack = [];
		$keys = $this->keys();
		$vals = $this->values();
		for( $i = 0; $i < $this->count(); $i++ ) {
			if( isset( $keys[$i] ) ) {
				try {
					$stack[$keys[$i]] = call_user_func( $callback, $i, $keys[$i], $vals[$i] );
				}
				catch( Support\Stoppable $stopped ) {
					$stack[$keys[$i]] = $stopped;
				}
				if( $stack[$keys[$i]] Instanceof Support\Stoppable ) {
					$stack[$keys[$i]] = $stack[$keys[$i]]->value;
					break;
				}
			}
		}
		return new Static( $this->name, $this->shared, $stack );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Arr\Arrayable::offsetExists
	 *
	 */
	public function offsetExists( Mixed $offset ): Bool {
		return isset( $this->data[( $this->keys[( is_numeric( $idx = array_search( $offset, $this->keys ) ) ? $idx : Null )] ?? Null )] ) || isset( $this->inherit[$offset] );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Arr\Arrayable::offsetGet
	 *
	 */
	public function offsetGet( Mixed $offset ): Mixed {
		return $this->data[( $this->keys[is_numeric( $idx = array_search( $offset, $this->keys ) ) ? $idx : Null ] ?? Null )] ?? $this->inherit[$offset] ?? Null;
	}
	
	/*
	 * @inherit Yume\Fure\Util\Arr\Arrayable::offsetGet
	 *
	 */
	public function offsetSet( Mixed $offset, Mixed $value ): Void {
		if( $offset === "[inherit]" ) {
			$this->__inherit( $value );
		}
		else {
			if( is_array( $value ) ) {
				$value = new Static( $this->name, $this->shared, $value );
			}
			parent::offsetSet( $offset, $value );
			$offset ??= end( $this->keys );
			foreach( $this->inherit As $inherit ) {
				if( isset( $inherit[$offset] ) ) {
					$inherit[$offset] = $value;
				}
			}
		}
	}
	
	/*
	 * @inherit Yume\Fure\Util\Arr\Arrayable::offsetUnset
	 *
	 */
	final public function offsetUnset( Mixed $offset ): Void {
		foreach( $this->inherit As $inherit ) {
			unset( $inherit[$offset] );
		}
		parent::offsetUnset( $offset );
	}
	
	/*
	 * Return if config is allowed for reuse.
	 *
	 * @access Public
	 *
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	final public function isSharedable( ? Bool $optional = Null ): Bool {
		return $optional !== Null ? $optional === $this->shared : $this->shared;
	}
	
	/*
	 * @inherit Yume\Fure\Util\Arr\Arrayable::replace
	 *
	 */
	final public function replace( Array | Arr\Arrayable $array, Bool $recursive = False ): Static {
		foreach( $array As $offset => $value ) {
			if( is_array( $value ) ) {

				// Skip create new Static Instance if recursion is
				// allowed and if previous element value is Instanceof Arrayable.
				if( $recursive && $this->__get( $offset ) Instanceof Arr\Arrayable ) {
					continue;
				}
				$array[$offset] = new Static( $this->name, $this->shared, $value );
			}
		}
		return parent::replace( $array, $recursive );
	}
	
}

?>