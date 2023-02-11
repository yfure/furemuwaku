<?php

namespace Yume\Fure\Cache;

use Yume\Fure\Cache\Adapter;
use Yume\Fure\Error;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\Reflect;
use Yume\Fure\Util;

/*
 * CacheItemPool
 *
 * @package Yume\Fure\Cache
 */
class CacheItemPool implements CacheItemPoolInterface
{
	
	/*
	 * Instance of class Cache Adapter.
	 *
	 * @access Private
	 *
	 * @values Yume\Fure\Cache\CacheItemPoolInterface
	 */
	private CacheItemPoolInterface $adapter;
	
	/*
	 * Construct method of class CacheItemPool
	 *
	 * @access Public Instance
	 *
	 * @params String|Yume\Fure\Cache\CacheItemPoolInterface $adapter
	 *
	 * @return Void
	 */
	public function __construct( Null | String | CacheItemPoolInterface $adapter = Null )
	{
		// If adapter is not available.
		if( $adapter === Null )
			
			/*
			 * Just only use default adapter in configuration set.
			 *
			 * @see system/configs/cache
			 */
			$adapter = config( "cache" )->default;
		
		// If adapter is String type.
		if( is_string( $adapter ) )
		{
			// Check if adapter class is implements CacheItemPoolInterface.
			if( Reflect\ReflectClass::isImplements( $adapter, CacheItemPoolInterface::class, $reflect ) )
			{
				$adapter = $reflect->newInstance( config( "cache" )->adapter[$adapter] );
			}
			else {
				throw new Error\ClassImplementationError([ $adapter, CacheItemPoolInterface::class ]);
			}
		}
		$this->adapter = $adapter;
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function clear(): Bool
	{
		return( $this )->adapter->clear();
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function commit(): Bool
	{
		return( $this )->adapter->commit();
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function deleteItem( String $key ): Bool
	{
		return( $this )->adapter->deleteItem( $key );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function deleteItems( Array $keys ): Bool
	{
		return( $this )->adapter->deleteItems( $keys );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function getItem( String $key ): CacheItemInterface
	{
		return( $this )->adapter->getItem( $key );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function getItems( Array $keys = [] ): Data\DataInterface
	{
		return( $this )->adapter->getItems( $keys );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function hasItem( String $key ): Bool
	{
		return( $this )->adapter->hasItem( $key );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function save( CacheItemInterface $item ): Bool
	{
		return( $this )->adapter->save( $item );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function saveDeferred( CacheItemInterface $item ): Bool
	{
		return( $this )->adapter->saveDeferred( $item );
	}
	
}

?>