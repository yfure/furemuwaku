<?php

namespace Yume\Fure\Cache\Pool;

use Throwable;

use Yume\Fure\Cache;
use Yume\Fure\Config;
use Yume\Fure\IO\File;
use Yume\Fure\IO\Path;
use Yume\Fure\Util\Arr;

/*
 * FileSystemPool
 * 
 * @extends Yume\Fure\Cache\Pool\BasePool
 * 
 * @package Yume\Fure\Cache
 */
class FileSystemPool extends BasePool implements Cache\CacheItemPoolInterface
{
	
	/*
	 * Cache stored pathname.
	 *
	 * @access Private
	 *
	 * @values String
	 */
	private String $path = Path\Paths::StorageCache->value;
	
	/*
	 * Cache extension name.
	 *
	 * @access Private
	 *
	 * @values String
	 */
	private String $extension = "cache";
	
	/*
	 * Cache path permission.
	 *
	 * @access Private
	 *
	 * @values Int
	 */
	private Int $permission = 0777;
	
	/*
	 * @inherit Yume\Fure\Cache\Pool\BasPool::__construct
	 * 
	 */
	public function __construct( Config\Config $configs )
	{
		$this->path = $configs->path ?? $this->path;
		$this->extension = $configs->extension ?? $this->extension;
		$this->permission = $configs->permission ?? $this->permission;
		
		// Check if cache path doesn't exists.
		if( Path\Path::exists( $this->path ) === False )
		{
			Path\Path::make( $this->path, $this->permission );
		}
		else {
			
			// Check if path is not writable.
			if( is_writable( Path\Path::path( $this->path ) ) === False )
			{
				throw new Path\PathError( $this->path, Path\PathError::READ_ERROR );
			}
		}
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface::clear
	 *
	 */
	public function clear(): Bool
	{
		return( Path\Path::remove( $this->path, "/*", True ) );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface::commit
	 *
	 */
	public function commit(): Bool
	{
		return( True );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface::deleteItem
	 *
	 */
	public function deleteItem( String $key ): Bool
	{
		return( File\File::remove( $this->path( $key ) ) );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface::deleteItems
	 *
	 */
	public function deleteItems( Array $keys ): Bool
	{
		foreach( array_values( $keys ) As $key )
		{
			$this->deleteItem( $key );
		}
		return( True );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface::getItem
	 *
	 */
	public function getItem( String $key ): Cache\CacheItemInterface
	{
		$cache = new Cache\CacheItem( $key, Null, False );
		putln( $this->path( $key ) );
		// Check if cache is exists.
		if( self::hasItem( $key ) )
		{
			// Get path with key name.
			$path = $this->path( $key );
			
			// Unserializing cache contents.
			$data = unserialize( File\File::read( $path ), [ "max_depth" => 0 ]);
			$isHit = False;
			
			// If cache contents is valid.
			if( is_array( $data ) )
			{
				$data = [
					"time" => $data['time'] ?? 0,
					"live" => $data['live'] ?? 0,
					"value" => $data['value'] ?? Null
				];
				
				// Get current timestamp.
				$time = datetime()->getTimestamp();
				
				// Check if cache has expired.
				if( $data['time'] + $data['live'] < $time )
				{
					// Remove expired cache.
					File\File::remove( $path );
					
					// Remove data values.
					$data['value'] = Null;
					
					// Set cache as hit.
					$isHit = True;
				}
				else {
					$time = $data['time'] + $data['live'];
				}
				$cache->set( $data['value'] );
				$cache->setHit( $isHit );
				$cache->expiresAt( datetime()->setTimestamp( $time ) );
			}
		}
		return( $cache );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface::getItems
	 *
	 */
	public function getItems( Array $keys = [] ): Array | Arr\Associative
	{
		// Data Instance.
		$items = new Arr\Associative;
		
		// Mapping keys.
		array_map( fn( String $key ) => $items[$key] = $this->getItem( $key ), array_values( $keys ) );
		
		return( $items );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface::hasItem
	 *
	 */
	public function hasItem( String $key ): Bool
	{
		return( File\File::exists( $this->path( $key ) ) );
	}
	
	/*
	 * Return cache path with key.
	 *
	 * @access Public
	 *
	 * @params String $key
	 *
	 * @return String
	 */
	public function path( String $key ): String
	{
		return( sprintf( "%s/%s.%s", $this->path, $this->key( $key ), $this->extension ) );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface::save
	 *
	 */
	public function save( Cache\CacheItemInterface $item ): Bool
	{
		// Get path with key name.
		$path = $this->path( $item->getkey() );
		
		// Get cache expiration time.
		$time = $item->getExpires();
		
		// Get default cache live time.
		$live = config( "cache" )->time->live;
		
		// Check if cache is Hit.
		if( $item->isHit() )
		{
			// Current date timestamp.
			$time = datetime()->getTimestamp();
			$live = 0;
		}
		
		// Serializing contents.
		$save = serialize([
			"time" => $time,
			"live" => $live,
			"value" => $item->get()
		]);
		
		try
		{
			if( File\File::write( $path, $save ) )
			{
				if( chmod( Path\Path::path( $path ), $this->permission ) )
				{
					return( True );
				}
				logger( "debug", "Failed set permission for cache {}", [ $item->getkey() ] );
			}
			else {
				logger( "debug", "Failed save cache {}", [ $item->getkey() ] );
			}
		}
		catch( Throwable $e )
		{
			logger( "debug", "{}: {} when save cache {}", [ $e::class, $e->getMessage(), $item->getkey() ] );
		}
		return( False );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface::saveDeferred
	 *
	 */
	public function saveDeferred( Cache\CacheItemInterface $item ): Bool
	{
		return( $this )->save( $item );
	}
}
