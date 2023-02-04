<?php

namespace Yume\Fure\Cache;

use DateInterval;
use DateTimeInterface;

use Yume\Fure\Locale\Clock;
use Yume\Fure\Util;

/*
 * CacheItem
 *
 * @package Yume\Fure\Cache;
 */
class CacheItem implements CacheItemInterface
{
	
	/*
	 * Cache expiration time.
	 *
	 * @access Private
	 *
	 * @values Int
	 */
	private ? Int $expires = Null;
	
	/*
	 * Construct method of class CacheItem
	 *
	 * @access Public Instance
	 *
	 * @params Private String $key
	 * @params Private Mixed $value
	 * @params Private Bool $hit
	 *
	 * @return Void
	 */
	public function __construct(
		
		/*
		 * Cache key name.
		 *
		 * @access Private
		 *
		 * @values String
		 */
		private String $key,
		
		/*
		 * Cache values.
		 *
		 * @access Private
		 *
		 * @values Mixed
		 */
		private Mixed $value,
		
		/*
		 * Cache hit.
		 *
		 * @access Private
		 *
		 * @values Bool
		 */
		private Bool $hit,
		
		/*
		 * ClockInterface implementation.
		 *
		 * @access Private
		 *
		 * @values Yume\Fure\Locale\Clock\ClockInterface
		 */
		private ? Clock\ClockInterface $clock = Null
		
	) {
		$this->clock = $clock ??= new Clock\Clock; 
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheInterface
	 *
	 */
	public function expiresAfter( DateInterval | Int | Null $time ): CacheItemInterface
	{
		// If time is Null type.
		if( $time === Null ) return( $this )->expiresAt( Null );
		
		// If time is Int type.
		if( is_int( $time ) )
		{
			// Check if provided TTL is supported.
			if( $interval = DateInterval::createFromDateString( Util\Str::fmt( "{} seconds", $time ) ) )
			{
				$time = $interval;
			}
			else {
				throw new CacheError( $time, CacheError::TTL_ERROR );
			}
		}
		
		// If time is Instanceof DateInterval class.
		if( $time Instanceof DateInterval ) return( $this )->expiresAt( $this->clock->now()->add( $time ) );
		
		// Invalid time passed.
		throw new CacheError( ucfirst( gettype( $time ) ), CacheError::TIME_ERROR );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheInterface
	 *
	 */
	public function expiresAt( ? DateTimeInterface $expiration ): CacheItemInterface
	{
		return([ $this, $this->expires = $expiration Instanceof DateTimeInterface ? $expiration->getTimestamp() : Null ][0]);
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheInterface
	 *
	 */
	public function get(): Mixed
	{
		return( $this )->value;
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheInterface
	 *
	 */
	public function getKey(): String
	{
		return( $this )->key;
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheInterface
	 *
	 */
	public function isHit(): Bool
	{
		// Check if cache is not hit.
		if( $this->hit === False )
		{
			// Check if cache has no expiration time.
			if( $this->expires === Null )
			{
				return( True );
			}
			return( $this->expires - $this->clock->now()->getTimestamp() > 0 );
		}
		return( False );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheInterface
	 *
	 */
	public function set( Mixed $value ): CacheItemInterface
	{
		return([ $this, $this->value = $value ][0]);
	}
	
	/*
	 * Set cache as hit.
	 *
	 * @access Public
	 *
	 * @params Bool $hit
	 *
	 * @return Yume\Fure\Cache\CacheInterface
	 */
	public function setHit( Bool $hit ): CacheItemInterface
	{
		return([ $this, $this->hist = $hit ][0]);
	}
	
}

?>