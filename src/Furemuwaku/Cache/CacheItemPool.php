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
	 * Cache item lists.
	 *
	 * @access Private
	 *
	 * @values Array
	 */
	private Array $items = [];
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function clear(): Bool
	{
		$this->items = [];
		return( True );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function commit(): Bool
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function deleteItem( String $key ): Bool
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function deleteItems( Array $keys ): Bool
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function getItem( String $key ): CacheItemInterface
	{
		// Check if cache key is exists.
		if( isset( $this->items[$key] ) === False )
		{
			return( new CacheItem( $key ) );
		}
		return( $this->items )[$key];
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function getItems( Array $keys = [] ): Data\DataInterface
	{
		// Cache stacks.
		$items = new Data\Data([]);
		
		// Mapping cache keys.
		Util\Arr::map( $keys, fn( $i, $idx, $key ) => $items[] = $this->getItem( $key ) );
		
		// Return items.
		return( $items );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function hasItem( String $key ): Bool
	{
		return( isset( $this->items[$key] ) );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function save( CacheItemInterface $item ): Bool
	{
		// ...
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function saveDeferred( CacheItemInterface $item ): Bool
	{
		// ...
	}
	
}

?>