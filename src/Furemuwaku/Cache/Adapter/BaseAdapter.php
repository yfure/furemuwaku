<?php

namespace Yume\Fure\Cache\Adapter;

use Yume\Fure\Cache;

/*
 * BaseAdapter
 *
 * @package Yume\Fure\Cache\Adapter
 */
abstract class BaseAdapter implements Cache\CacheItemPoolInterface
{
	
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