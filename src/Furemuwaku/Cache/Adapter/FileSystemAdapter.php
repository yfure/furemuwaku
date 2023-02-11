<?php

namespace Yume\Fure\Cache\Adapter;

use Yume\Fure\Cache;
use Yume\Fure\Config;
use Yume\Fure\Locale;
use Yume\Fure\Locale\DateTime;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\File;
use Yume\Fure\Support\Path;

/*
 * FileSystemAdapter
 *
 * @package Yume\Fure\Cache\Adapter
 *
 * @extends Yume\Fure\Cache\Adapter\BaseAdapter
 */
class FileSystemAdapter extends BaseAdapter
{
	
	/*
	 * Cache stored pathname.
	 *
	 * @access Private
	 *
	 * @values String
	 */
	private String $path = Path\PathName::ASSET_CACHE->value;
	
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
	 * Construct method of class FileSystemAdapter
	 *
	 * @access Public Instance
	 *
	 * @params Yume\Fure\Config\Config $configs
	 *
	 * @return Void
	 */
	public function __construct( Config\Config $configs )
	{
		$this->path = $configs->path ?? $this->path;
		$this->extension = $configs->extension ?? $this->extension;
		$this->permission = $configs->permission ?? $this->permission;
		
		// Check if cache path doesn't exists.
		if( Path\Path::exists( $this->path ) === False )
		{
			Path\Path::mkdir( $this->path, $this->permission );
		}
		else {
			
			// Check if path is not writable.
			if( Path\Path::writable( $this->path ) === False )
			{
				throw new Path\PathError( $this->path, Path\PathError::READ_ERROR );
			}
		}
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function clear(): Bool
	{
		return( Path\Path::remove( $this->path, "/*", True ) );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function commit(): Bool
	{
		return( True );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function deleteItem( String $key ): Bool
	{
		return( File\File::unlink( $this->path( $key ) ) );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
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
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function getItem( String $key ): Cache\CacheItemInterface
	{
		// Get path with key name.
		$path = $this->path( $key );
		
		$cache = new Cache\CacheItem( $key, Null, False );
		
		// Check if cache is exists.
		if( self::hasItem( $key ) )
		{
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
				$time = Locale\Locale::getDateTime()->getTimestamp();
				
				// Check if cache has expired.
				if( $data['time'] + $data['live'] < $time )
				{
					// Remove expired cache.
					File\File::unlink( $path );
					
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
				$cache->expiresAt( ( new DateTime\DateTime )->setTimestamp( $time ) );
			}
		}
		return( $cache );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function getItems( Array $keys = [] ): Data\DataInterface
	{
		// Data Instance.
		$items = new Data\Data([]);
		
		// Mapping keys.
		array_map( fn( String $key ) => $items[$key] = $this->getItem( $key ), array_values( $keys ) );
		
		return( $items );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
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
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
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
			$time = Locale\Locale::getDateTime()->getTimestamp();
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
				if( File\File::chmod( $path, $this->permission ) )
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
	 * @inherit Yume\Fure\Cache\CacheItemPoolInterface
	 *
	 */
	public function saveDeferred( Cache\CacheItemInterface $item ): Bool
	{
		return( $this )->save( $item );
	}
	
}

?>