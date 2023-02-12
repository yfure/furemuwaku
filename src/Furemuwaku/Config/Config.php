<?php

namespace Yume\Fure\Config;

use Yume\Fure\Support\Data;
use Yume\Fure\Util;

/*
 * Config
 *
 * @extends Yume\Fure\Support\Data\Data
 *
 * @package Yume\Fure\Config
 */
class Config extends Data\Data
{
	
	/*
	 * @inherit Yume\Fure\Support\Data\Data
	 *
	 */
	final public function __construct( public Readonly String $name, Array | Data\DataInterface $configs )
	{
		// Mapping data.
		Util\Arr::map( $configs, function( $i, $key, $value ) use( $name )
		{
			// If data value is Array type.
			if( is_array( $value ) )
			{
				$value = new Config( $name, $value );
			}
			$this->data[$key] = $value;
		});
	}
	
	/*
	 * @inherit Yume\Fure\Support\Data\Data
	 *
	 */
	final public function __set( String $name, Mixed $value ): Void
	{
		$this->data[$name] = is_array( $value ) ? new Config( $value ) : $value;
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