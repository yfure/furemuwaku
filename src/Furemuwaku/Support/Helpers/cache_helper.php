<?php

/*
 * Yume Cache Helpers.
 *
 * @include cache
 */

use Yume\Fure\Cache;

if( function_exists( "cache" ) === False )
{
	/*
	 * Get cache item or cache item pool
	 *
	 * @params String $key
	 *
	 * @return Yume\Fure\Cache\CacheItemInterface|Yume\Fure\Cache\CacheItemPoolInterface|
	 */
	function cache( ? String $key = Null ): Cache\CacheItemInterface | Cache\CacheItemPoolInterface
	{
		if( valueIsNotEmpty( $key ) )
		{
			return( Cache\Cache::get( $key ) );
		}
		return( Cache\Cache::pool() );
	}
}

?>