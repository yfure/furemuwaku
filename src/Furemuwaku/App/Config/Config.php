<?php

namespace Yume\Fure\App\Config;

use Yume\Fure\Support;

/*
 * Config
 *
 * @extends Yume\Fure\Support\Data\Data
 *
 * @package Yume\Fure\App\Config
 */
class Config extends Support\Data\Data
{
	
	/*
	 * @inherit Yume\Fure\Support\Data\Data
	 *
	 */
	final public function __construct( private String $name, Array | Support\Data\Data $configs )
	{
		parent::__construct( $configs );
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