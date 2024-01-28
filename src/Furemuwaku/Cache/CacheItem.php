<?php

namespace Yume\Fure\Cache;

use DateInterval;
use DateTimeInterface;

use Yume\Fure\Locale\Clock;
use Yume\Fure\Util;

/*
 * CacheItem
 * 
 * @package Yume\Fure\Cache
 */
class CacheItem implements CacheItemInterface {

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
	 * @params Private Yume\Fure\Locale\Clock\ClockInterface
	 *
	 * @return Void
	 */
	public function __construct( private String $key, private Mixed $value, private Bool $hit, private ? Clock\ClockInterface $clock = Null ) {
		$this->clock = $clock ??= new Clock\Clock; 
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheInterface::expiresAfter
	 *
	 */
	public function expiresAfter( DateInterval | Int | Null $time ): CacheItemInterface {
		if( $time === Null ) {
			return( $this )->expiresAt( Null );
		}
		if( is_int( $time ) ) {
			if( $interval = DateInterval::createFromDateString( Util\Strings::format( "{} seconds", $time ) ) ) {
				$time = $interval;
			}
			else {
				throw new CacheError( $time, CacheError::TTL_ERROR );
			}
		}
		if( $time Instanceof DateInterval ) {
			return( $this )->expiresAt( $this->clock->now()->add( $time ) );
		}
		
		// Invalid time passed.
		throw new CacheError( ucfirst( gettype( $time ) ), CacheError::TIME_ERROR );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheInterface::expiresAt
	 *
	 */
	public function expiresAt( ? DateTimeInterface $expiration ): CacheItemInterface {
		return([ $this, $this->expires = $expiration Instanceof DateTimeInterface ? $expiration->getTimestamp() : Null ][0]);
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheInterface::get
	 *
	 */
	public function get(): Mixed {
		return( $this )->value;
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheInterface::getExpires
	 *
	 */
	public function getExpires(): ? Int {
		return( $this )->expires;
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheInterface::getKey
	 *
	 */
	public function getKey(): String {
		return( $this )->key;
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheInterface::isHit
	 *
	 */
	public function isHit(): Bool {
		// Check if cache is not hit.
		if( $this->hit === False ) {
			// Check if cache has no expiration time.
			if( $this->expires === Null ) {
				return( True );
			}
			return( $this->expires < $this->clock->now()->getTimestamp() );
		}
		return( False );
	}
	
	/*
	 * @inherit Yume\Fure\Cache\CacheInterface::set
	 *
	 */
	public function set( Mixed $value ): CacheItemInterface {
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
	public function setHit( Bool $hit ): CacheItemInterface {
		return([ $this, $this->hit = $hit ][0]);
	}

}

?>
