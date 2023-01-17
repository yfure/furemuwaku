<?php

namespace Yume\Fure\Config;

use Yume\Fure\Support\Data;

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