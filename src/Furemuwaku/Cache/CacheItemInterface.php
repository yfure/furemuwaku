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
	
	public function expiresAfter( DateInterval | Int | Null $time ): CacheItemInterface;
	public function expiresAt( ? DateTimeInterface $expiration ): CacheItemInterface;
	
	public function get(): Mixed;
	public function getKey(): String;
	
	public function isHit(): Bool;
	
	public function set( Mixed $value ): CacheItemInterface;
	
}

?>