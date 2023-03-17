<?php

namespace Yume\Fure\Support\Config;

use Yume\Fure\Support\Data;
use Yume\Fure\Util\Array;
use Yume\Fure\Util\Json;

/*
 * Config
 *
 * @extends Yume\Fure\Support\Data\Data
 *
 * @package Yume\Fure\Support\Config
 */
class Config extends Data\Data
{
	
	/*
	 * Other configuration instance.
	 *
	 * @access Private
	 *
	 * @values Array
	 */
	private Array $inherit;
	
	/*
	 * @inherit Yume\Fure\Support\Data\Data::__construct()
	 *
	 */
	final public function __construct( public Readonly String $name, Array $configs )
	{
		// Set inherit configuration.
		$this->inherit = [];
		
		// Check if config has inheritance value.
		if( isset( $configs['[inherit]'] ) )
		{
			$this->__inherit( $configs['[inherit]'] );
		}
		
		$data = [];
		
		// Mapping data.
		Array\Arr::map( $configs, function( Int $i, Int | String $key, Mixed $value ) use( $name, &$data ): Void
		{
			// If configuration value is not inheritable.
			if( $key !== "[inherit]" )
			{
				// If data value is Array type.
				if( is_array( $value ) )
				{
					$value = new Config( $name, $value );
				}
				$data[$key] = $value;
			}
		});
		parent::__construct( $data );
	}
	
	/*
	 * @inherit Yume\Fure\Support\Data\Data::__get()
	 *
	 */
	final public function __get( String $name ): Mixed
	{
		if( isset( $this->data[$name] ) )
		{
			return( $this->data[$name] );
		}
		foreach( $this->inherit As $inherit )
		{
			if( isset( $inherit[$name] ) )
			{
				return( $inherit[$name] );
			}
		}
		return( Null );
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
						$this->inherit[$name] = config( $name );
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
	 * @inherit Yume\Fure\Support\Data\Data::__isset()
	 *
	 */
	final public function __isset( String $name ): Bool
	{
		foreach( $this->inherit As $inherit )
		{
			if( isset( $inherit[$name] ) )
			{
				return( True );
			}
		}
		return( isset( $this->data[$name] ) );
	}
	
	/*
	 * @inherit Yume\Fure\Support\Data\Data::__set()
	 *
	 */
	final public function __set( String $name, Mixed $value ): Void
	{
		if( $name !== "[inherit]" )
		{
			if( is_array( $value ) )
			{
				$value = new Config( $this->name, $value );
			}
			foreach( $this->inherit As $inherit )
			{
				if( isset( $inherit[$name] ) )
				{
					$inherit[$name] = $value;
				}
			}
			$this->data[$name] = $value;
		}
		else {
			$this->__inherit( $value );
		}
	}
	
	/*
	 * @inherit Yume\Fure\Support\Data\Data::__toArray()
	 *
	 */
	final public function __toArray(): Array
	{
		$data = [];
		
		foreach( $this->inherit As $inherit )
		{
			$data = [
				...$data,
				...$inherit->__toArray()
			];
		}
		return([
			...$data,
			...parent::__toArray()
		]);
	}
	
	/*
	 * @inherit Yume\Fure\Support\Data\Data::__unset()
	 *
	 */
	final public function __unset( String $name ): Void
	{
		foreach( $this->inherit As $inherit )
		{
			unset( $inherit[$name] );
		}
		unset( $this->data[$name] );
	}
	
	/*
	 * Get config name.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	final public function getName(): String
	{
		return( $this->name );
	}
	
}

?>