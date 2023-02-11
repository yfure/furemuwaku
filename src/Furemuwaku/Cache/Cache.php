<?php

namespace Yume\Fure\Cache;

use Yume\Fure\Support\Design;

/*
 * Cache
 *
 * @package Yume\Fure\Cache
 *
 * @extends Yume\Fure\Support\Design\Singleton
 */
class Cache extends Design\Singleton
{
	
	/*
	 * Instanceof BaseAdapter cache class.
	 *
	 * @access Static Private
	 *
	 * @values Yume\Fure\Cache\CacheItemPoolInterface
	 */
	static private CacheItemPoolInterface $pool;
	
	/*
	 * @inherit Yume\Fure\Support\Design\Singleton
	 *
	 */
	protected function __construct()
	{
		static::$pool = new CacheItemPool( config( "cache" )->default );
	}
	
}

?>