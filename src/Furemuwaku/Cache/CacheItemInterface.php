<?php

namespace Yume\Fure\Cache;

use DateInterval;
use DateTimeInterface;

/*
 * CacheItemInterface
 * 
 * @package Yume\Fure\Cache
 */
interface CacheItemInterface
{

	/*
	 * Sets the relative expiration time for this cache item.
	 * 
	 * @access Public
	 * 
	 * @params DateInterval|Int $time
	 *  The period of time from the present after which the item MUST be considered
	 *  expired. An integer parameter is understood to be the time in seconds until
	 *  expiration. If null is passed explicitly, a default value MAY be used.
	 *  If none is set, the value should be stored permanently or for as long as the
	 * implementation allows.
	 * 
	 * @return Yume\Fure\Cache\CacheItemInterface
	 */
	public function expiresAfter( DateInterval | Int | Null $time ): CacheItemInterface;

	/*
	 * Sets the absolute expiration time for this cache item.
	 * 
	 * @access Public
	 * 
	 * @params DateTimeInterface $expiration
	 *  The point in time after which the item MUST be considered expired.
	 *  If null is passed explicitly, a default value MAY be used. If none is set,
	 *  the value should be stored permanently or for as long as the
	 *  implementation allows.
	 * 
	 * @return Yume\Fure\Cache\CacheItemInterface
	 */
	public function expiresAt( ? DateTimeInterface $expiration ): CacheItemInterface;
	
	/*
	 * Get value of cache.
	 * 
	 * @access Public
	 * 
	 * @return Mixed
	 */
	public function get(): Mixed;

	/*
	 * Get cache expiration time.
	 *
	 * @access Public
	 *
	 * @return Int
	 */
	public function getExpires(): ? Int;

	/*
	 * Return key name of cache item.
	 * 
	 * @access Public
	 * 
	 * @Return String
	 */
	public function getKey(): String;
	
	/*
	 * Return if cache is hitted.
	 * 
	 * @access Public
	 * 
	 * @return Bool
	 */
	public function isHit(): Bool;

	/*
	 * Set cache value.
	 * 
	 * @access Public
	 * 
	 * @params Mixed $value
	 * 
	 * @return Yume\Fure\Cache\CacheItemInterface
	 */
	
	public function set( Mixed $value ): CacheItemInterface;

}

?>
