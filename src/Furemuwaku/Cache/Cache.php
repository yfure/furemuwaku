<?php

namespace Yume\Fure\Cache;

use Yume\Fure\Support;

/*
 * Cache
 * 
 * @extends Yume\Fure\Support\Singleton
 * 
 * @package Yume\Fure\Cache
 */
class Cache extends Support\Singleton {

	/*
	 * Instance of class CacheItemPoolInterface.
	 * 
	 * @access Static Private
	 * 
	 * @values Yume\Fure\Cache\CacheItemPoolInterface
	 */
	static private CacheItemPoolInterface $pool;

	/*
	 * @inherit Yume\Fure\Support\Singleton::__construct
	 * 
	 */
	protected function __construct() {
		static::$pool = new CacheItemPool();
	}

	/*
	 * Get cache item.
	 * 
	 * @access Public Static
	 * 
	 * @params String $key
	 * 
	 * @return CacheItemInterface
	 */
	public static function get( String $key ): CacheItemInterface {
		return( static::$pool )->getItem( $key );
	}

	/*
	 * Get cache item pool.
	 * 
	 * @access Public Static
	 * 
	 * @return CacheItemPoolInterface
	 */
	public static function pool(): CacheItemPoolInterface {
		return( Clone static::$pool );
	}

	/*
	 * Save cache item.
	 * 
	 * @access Public Static
	 * 
	 * @params Yume\Fure\Cache\CacheItemInterface $item
	 * 
	 * @return Bool
	 */
	public static function save( CacheItemInterface $item ): Bool {
		return( static::$pool )->save( $item );
	}

}
