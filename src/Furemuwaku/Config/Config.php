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
class Config extends Support\Data
{
	
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
	final public function __construct( public Readonly String $name, public Readonly Bool $shared, Array $configs )
	{
		// Check if config has inheritance value.
		if( isset( $configs['[inherit]'] ) ) $this->__inherit( $configs['[inherit]'] );
		
		// Mapping data.
		Util\Arrays::map( $configs, static function( Int $i, Mixed $key, Mixed $value ) use( $name, $shared, &$configs ): Void
		{
			// If configuration value is inheritable.
			if( $key === "[inherit]" )
			{
				unset( $configs[$key] );
			}
			else {
				
				// If data value is Array type.
				if( is_array( $value ) ) $configs[$key] = new Config( $name, $shared, $value );
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
	private function __inherit( Mixed $inherits ): Void
	{
		// If config is multi inheritance.
		if( is_array( $inherits ) )
		{
			foreach( $inherits As $name => $value )
			{
				// If parent is String type.
				if( is_string( $value ) )
				{
					// Normalize parent name.
					$name = strtolower( $value );
					
					// If parent name is not equals with current config.
					if( $name !== strtolower( $this->name ) )
					{
						$this->inherit[$name] = config( $name, new Config( $this->name, $this->shared, [] ) );
					}
				}
				
				// If parent is Config type.
				else if( $value Instanceof Config )
				{
					$this->inherit[strtolower( $value->name )] = $value;
				}
			}
		}
		
		// If config single inheritance.
		if( is_string( $name = $inherits ) )
		{
			// Normalize parent name.
			$name = strtolower( $name );
			
			// If parent name is not equals with current config.
			if( $name !== strtolower( $this->name ) )
			{
				$this->inherit[$name] = config( $name );
			}
		}
	}
	
	/*
	 * @inherit Yume\Fure\Util\Arr\Arrayable::__toArray
	 *
	 */
	final function __toArray(): Array
	{
		foreach( $this->inherit As $inherit )
		{
			$data ??= [];
			$data = [ ...$data, ...$inherit->__toArray() ];
		}
		return( array_replace_recursive( $data ?? [], parent::__toArray() ) );
	}
	
	/*
	 * Parse class to string.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	final public function __toString(): String
	{
		return( Util\Strings::format( "{}<{}>({})", $this::class, $this->name, $this->__toArray() ) );
	}

	/*
	 * @inherit Yume\Fure\Suport\Data::copy
	 */
	public function copy(): Static
	{
		return( new Config( $this->name, $this->shared, $this->__toArray() ) );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Arr\Arrayable::map
	 *
	 */
	final public function map( Callable $callback ): Static
	{
		// Data stack.
		$stack = [];
		
		// Get data keys.
		$keys = $this->keys();
		$vals = $this->values();
		
		// Mapping data.
		for( $i = 0; $i < $this->count(); $i++ )
		{
			// Check if element is exists.
			if( isset( $keys[$i] ) )
			{
				try
				{
					// Get callback return value.
					$stack[$keys[$i]] = call_user_func(
						
						// Callback handler.
						$callback,
						
						// Index iteration.
						$i,
						
						// Array index.
						$keys[$i],
						
						// Array value.
						$vals[$i]
					);
				}
				catch( Support\Stoppable $stopped )
				{
					$stack[$keys[$i]] = $stopped;
				}
				
				// Checks if further execution is terminated.
				if( $stack[$keys[$i]] Instanceof Support\Stoppable )
				{
					$stack[$keys[$i]] = $stack[$keys[$i]]->value;
					break;
				}
			}
		}
		return( new Static( $this->name, $this->shared, $stack ) );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Arr\Arrayable::offsetExists
	 *
	 */
	public function offsetExists( Mixed $offset ): Bool
	{
		return( isset( $this->data[( $this->keys[( is_numeric( $idx = array_search( $offset, $this->keys ) ) ? $idx : Null )] ?? Null )] ) || isset( $this->inherit[$offset] ) );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Arr\Arrayable::offsetGet
	 *
	 */
	public function offsetGet( Mixed $offset ): Mixed
	{
		return( $this->data[( $this->keys[is_numeric( $idx = array_search( $offset, $this->keys ) ) ? $idx : Null ] ?? Null )] ?? $this->inherit[$offset] ?? Null );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Arr\Arrayable::offsetGet
	 *
	 */
	public function offsetSet( Mixed $offset, Mixed $value ): Void
	{
		// If offset is inherintance syntax.
		if( $offset === "[inherit]" )
		{
			$this->__inherit( $value );
		}
		else {
			
			// Check if value is array.
			if( is_array( $value ) ) $value = new Static( $this->name, $this->shared, $value );
			
			// Set with parent.
			parent::offsetSet( $offset, $value );
			
			// Get offset.
			$offset ??= end( $this->keys );
			
			// Mapping inherit configurations.
			foreach( $this->inherit As $inherit )
			{
				// Check if inherit configuration has offset.
				if( isset( $inherit[$offset] ) )
				{
					// Update inherit configuration.
					$inherit[$offset] = $value;
				}
			}
		}
	}
	
	/*
	 * @inherit Yume\Fure\Util\Arr\Arrayable::offsetUnset
	 *
	 */
	final public function offsetUnset( Mixed $offset ): Void
	{
		// Also unset from inherit.
		foreach( $this->inherit As $inherit ) unset( $inherit[$offset] );
		
		// Unset from parent.
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
	final public function isSharedable( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $optional === $this->shared : $this->shared );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Arr\Arrayable::replace
	 *
	 */
	final public function replace( Array | Arr\Arrayable $array, Bool $recursive = False ): Static
	{
		foreach( $array As $offset => $value )
		{
			// Check if value of element is Array.
			if( is_array( $value ) )
			{
				// Skip create new Static Instance if recursion is
				// allowed and if previous element value is Instanceof Arrayable.
				if( $recursive && $this->__get( $offset ) Instanceof Arr\Arrayable )
				{
					continue;
				}
				$array[$offset] = new Static( $this->name, $this->shared, $value );
			}
		}
		return( parent::replace( $array, $recursive ) );
	}
	
}

?>