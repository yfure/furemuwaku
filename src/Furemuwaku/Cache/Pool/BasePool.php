<?php

namespace Yume\Fure\Cache\Pool;

use Yume\Fure\Cache;
use Yume\Fure\Config;

/*
 * BasePool
 * 
 * @package Yume\Fure\Cache\Pool
 */
abstract class BasePool implements Cache\CacheItemPoolInterface
{

	/*
	 * Construct method of class FileSystemAdapter
	 *
	 * @access Public Instance
	 *
	 * @params Yume\Fure\Config\Config $configs
	 *
	 * @return Void
	 */
	abstract function __construct( Config\Config $configs );

	/*
	 * Return Hashed key.
	 *
	 * @access Public
	 *
	 * @params String $key
	 *
	 * @return String
	 */
	protected function key( String $key ): String
	{
		return( hash( "sha512", $key ) );
	}

}

?>
