<?php

namespace Yume\Fure\Cache;

use Yume\Fure\Support\Data;
use Yume\Fure\Util;

/*
 * CacheItemPool
 *
 * @package Yume\Fure\Cache
 */
class CacheItemPool implements CacheItemPoolInterface
{
	
	/*
	 * Instance of class Cache Item Pool Driver.
	 *
	 * @access Private
	 *
	 * @values Yume\Fure\Cache\CacheItemPoolInterface
	 */
	private CacheItemPoolInterface $drivers;
	
	/*
	 * Construct method of class CacheItem
	 *
	 * @access Public Instance
	 *
	 * @return Void
	 */
	public function __construct()
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function clear(): Bool
	{
		return( $this )->driver->clear();
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function commit(): Bool
	{
		return( $this )->driver->commit();
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function deleteItem( String $key ): Bool
	{
		return( $this )->driver->deleteItem( $key );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function deleteItems( Array $keys ): Bool
	{
		return( $this )->driver->deleteItems( $keys );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function getItem( String $key ): CacheItemInterface
	{
		return( $this )->driver->getItem( $key );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function getItems( Array $keys = [] ): Data\DataInterface
	{
		return( $this )->driver->getItems( $keys );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function hasItem( String $key ): Bool
	{
		return( $this )->driver->hasItem( $key );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function save( CacheItemInterface $item ): Bool
	{
		return( $this )->driver->save( $item );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function saveDeferred( CacheItemInterface $item ): Bool
	{
		return( $this )->driver->saveDeferred( $item );
	}
	
}

?>