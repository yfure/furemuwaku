<?php

namespace Yume\Fure\Cache;

use Yume\Fure\Support\Data;

/*
 * CacheItemPoolInterface
 *
 * @package Yume\Fure\Cache
 */
interface CacheItemPoolInterface
{
	
	/*
	 * Deletes all items in the pool.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function clear(): Bool;
	
	/*
	 * Persists any deferred cache items.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function commit(): Bool;
	
	/*
	 * Removes the item from the pool.
	 *
	 * @access Public
	 *
	 * @params String $key
	 *
	 * @return Bool
	 */
	public function deleteItem( String $key ): Bool;
	
	/*
	 * Removes multiple items from the pool.
	 *
	 * @access Public
	 *
	 * @params Array $keys
	 *
	 * @return Bool
	 */
	public function deleteItems( Array $keys ): Bool;
	
	/*
	 * Returns a Cache Item representing the specified key.
	 *
	 * @access Public
	 *
	 * @params String $key
	 *
	 * @return Yume\Fure\Cache\CacheItemInterface $item
	 */
	public function getItem( String $key ): CacheItemInterface;
	
	/*
	 * Returns a traversable set of cache items.
	 *
	 * @access Public
	 *
	 * @params Array $keys
	 *
	 * @return Yume\Fure\Support\Data\DataInterface
	 */
	public function getItems( Array $keys = [] ): Data\DataInterface;
	
	/*
	 * Confirms if the cache contains specified cache item.
	 *
	 * @access Public
	 *
	 * @params String $key
	 *
	 * @return Bool
	 */
	public function hasItem( String $key ): Bool;
	
	/*
	 * Persists a cache item immediately.
	 *
	 * @access Public
	 *
	 * @params Yume\Fure\Cache\CacheItemInterface $item
	 *
	 * @return Bool
	 */
	public function save( CacheItemInterface $item ): Bool;
	
	/*
	 * Sets a cache item to be persisted later.
	 *
	 * @access Public
	 *
	 * @params Yume\Fure\Cache\CacheItemInterface $item
	 *
	 * @return Bool
	 */
	public function saveDeferred( CacheItemInterface $item ): Bool;
	
}

?>