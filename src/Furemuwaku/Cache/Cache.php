<?php

namespace Yume\Fure\Cache;

use Yume\Fure\Support\Design;
use Yume\Fure\Support\Services;

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
		// Check if cache has services.
		if( Services\Services::available( "cache" ) )
		{
			$pool = Services\Services::get( "cache" );
		}
		else {
			$pool = new CacheItemPool( config( "cache" )->default );
		}
		static::$pool = $pool;
	}
	
	public static function get( String $key ): CacheItemInterface
	{
		return( self::self() )->pool->getItem( $key );
	}
	
	public static function pool(): CacheItemPoolInterface
	{
		return( Clone self::self()->pool );
	}
	
	public static function save( CacheItemInterface $item ): Bool
	{
		return( self::self() )->pool->save( $item );
	}
	
}

?>