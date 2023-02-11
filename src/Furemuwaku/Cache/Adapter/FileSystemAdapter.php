<?php

namespace Yume\Fure\Cache\Adapter;

use Yume\Fure\Cache;
use Yume\Fure\Config;
use Yume\Fure\Locale;
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
				
				// Check if cache has expired.
				if( $data['time'] + $data['live'] < Locale\Locale::getDateTime()->getTimestamp() )
				{
					// Remove expired cache.
					File\File::unlink( $path );
					
					// Remove data values.
					$data['value'] = Null;
					
					// Set cache as hit.
					$isHit = True;
				}
			}
			return( new Cache\CacheItem( $key, $data['value'], $isHit ) );
		}
		return( new Cache\CacheItem( $key, Null, False ) );
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
		
		// Serializing contents.
		$save = serialize([
			"now" => Locale\Locale::getDateTime()->getTimestamp(),
			"live" => config( "cache" )->time->live,
			"values" => $item->get()
		]);
		
		try
		{
			if( File\File::write( $path, $save ) )
			{
				return( File\File::chmod( $path, $this->permission ) );
			}
			else {
				logger( "debug", "Failed save cache {}", [ $item->getkey() ] );
			}
		}
		catch( Throwable $e )
		{
			logger( "debug", "Failed set permission for cache {}", [ $item->getkey() ] );
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