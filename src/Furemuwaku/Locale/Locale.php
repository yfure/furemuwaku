<?php

namespace Yume\Fure\Locale;

use DateTimeInterface;
use DateTimeZone;
use Throwable;

use Yume\Fure\Error;
use Yume\Fure\Locale\Clock;
use Yume\Fure\Locale\DateTime;
use Yume\Fure\Locale\Language;
use Yume\Fure\Support\Design;
use Yume\Fure\Support\Package;
use Yume\Fure\Support\Path;
use Yume\Fure\Util;
use Yume\Fure\Util\Env;

/*
 * Locale
 *
 * @package Yume\Fure\Locale
 *
 * @extends Yume\Fure\Support\Design\Singleton
 */
class Locale extends Design\Singleton
{
	
	/*
	 * DateTime Instance class.
	 *
	 * @access Protected Static
	 *
	 * @values Yume\Fure\Locale\DateTime\DateTime
	 */
	protected static ? DateTime\DateTime $datetime = Null;
	
	/*
	 * Language Instance Class.
	 *
	 * @access Protected Static
	 *
	 * @values Yume\Fure\Locale\Language\Language
	 */
	protected static ? Language\Language $language = Null;
	
	/*
	 * DateTimeZone Instance Class.
	 *
	 * @access Proteced Static
	 *
	 * @values DateTimeZone
	 */
	protected static ? DateTimeZone $timezone = Null;
	
	/*
	 * Default language translation.
	 *
	 * @access Protected Static
	 *
	 * @values String
	 */
	protected static String $defaultLanguage = "en-us";
	
	/*
	 * Default date timezone.
	 *
	 * @access Protected Static
	 *
	 * @values String
	 */
	protected static String $defaultTimezone = "Asia/Jakarta";
	
	/*
	 * @inherit Yume\Fure\Support\Design\Singleton
	 *
	 */
	protected function __construct( Bool $setup = False )
	{
		if( $setup )
		{
			$this->setup();
		}
	}
	
	/*
	 * Check if timezone is available.
	 *
	 * @access Public Static
	 *
	 * @params String $timezone
	 *
	 * @return Bool
	 */
	public static function isAvailableTimeZone( String $timezone ): Bool
	{
		return( in_array( $timezone, DateTimeZone::listIdentifiers( DateTimeZone::ALL ) ) );
	}
	
	/*
	 * Get DateTime instance.
	 *
	 * @access Public Static
	 *
	 * @return Yume\Fure\Locale\DateTime\DateTime
	 */
	public static function getDateTime(): ? DateTime\DateTime
	{
		if( self::$datetime === Null )
		{
			self::setDateTime(
				"now", self::getTimeZone()
			);
		}
		return( self::$datetime );
	}
	
	/*
	 * Get Language instance.
	 *
	 * @access Public Static
	 *
	 * @return Yume\Fure\Locale\Language\Language
	 */
	public static function getLanguage(): Language\Language
	{
		if( self::$language === Null )
		{
			self::setLanguage(
				env( "LOCALE_LANGUAGE", self::$defaultLanguage )
			);
		}
		return( self::$language );
	}
	
	/*
	 * Get language as string.
	 *
	 * @access Public Static
	 *
	 * @return String
	 */
	public static function getLanguageName(): String
	{
		return( self::getLanguage() )->getLanguage();
	}
	
	/*
	 * Get DateTimeZone instance.
	 *
	 * @access Public Static
	 *
	 * @return DateTimeZone
	 */
	public static function getTimeZone(): DateTimeZone
	{
		if( self::$timezone === Null )
		{
			self::setTimeZone(
				env( "LOCALE_DATE_TIMEZOBE", self::$defaultTimeZone )
			);
		}
		return( self::$timezone );
	}
	
	/*
	 * Get default Language.
	 *
	 * @access Public Static
	 *
	 * @return String
	 */
	public static function getDefaultLanguage(): String
	{
		return( self::$defaultLanguage );
	}
	
	/*
	 * Get default DateTimeZone.
	 *
	 * @access Public Static
	 *
	 * @return String
	 */
	public static function getDefaultTimeZone(): String
	{
		return( self::$defaultTimezone );
	}
	
	/*
	 * Set datetime application.
	 *
	 * @access Public Static
	 *
	 * @params String $datetime
	 * @params String $timezone
	 *
	 * @return Void
	 */
	public static function setDateTime( ? String $datetime = Null, ? String $timezone = Null ): Void
	{
		// Set DateTimeZone instance.
		self::setTimeZone( $timezone );
		
		// Set DateTime instance.
		self::$datetime = new DateTime\DateTime( $datetime, self::$timezone );
	}
	
	/*
	 * Set language translation application.
	 *
	 * @access Public Static
	 *
	 * @params String $language
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\Error\TypeError
	 */
	public static function setLanguage( ? String $language = Null ): Void
	{
		try
		{
			// Import language file.
			self::$language = Package\Package::import( sprintf( "/%s/%s", Path\PathName::APP_LANG->value, $language ??= self::$defaultLanguage ) );
		}
		catch( Throwable $e )
		{
			throw new Error\TypeError( Util\Str::fmt( "An error occurred while setting the translation language {}", $language ), 0, $e );
		}
	}
	
	/*
	 * Set date timezone application.
	 *
	 * @access Public Static
	 *
	 * @params String $timezone
	 *
	 * @return Void
	 */
	public static function setTimeZone( ? String $timezone = Null ): Void
	{
		// Asserting timezone.
		self::assertTimeZone( $timezone ??= self::$defaultTimezone );
		
		// Check if current timezone is same.
		if( self::$timezone !== Null && self::$timezone->getName() !== $timezone || self::$timezone === Null )
		{
			// Set DateTimeZone instance.
			self::$timezone = new DateTimeZone( $timezone );
			
			// Set default locale date timezone.
			date_default_timezone_set( $timezone );
		}
	}
	
	/*
	 * Setup localization.
	 *
	 * @access Public
	 *
	 * @params String $datetime
	 * @params String $language
	 * @params String $timezone
	 *
	 * @return Void
	 */
	public function setup( ? String $datetime = Null, ? String $language = Null, ? String $timezone = Null ): Void
	{
		// Set Application Language.
		Locale::setLanguage( $language );
		
		// Set Application DateTime.
		Locale::setDateTime( $datetime, $timezone );
	}
	
	/*
	 * Get language translation.
	 *
	 * @access Public Static
	 *
	 * @params String $ify
	 * @params Mixed ...$format
	 *
	 * @return String
	 *
	 * @throws Yume\Fure\Error\TypeError
	 */
	public static function translate( String $ify, Mixed ...$format ): ? String
	{
		try
		{
			// Check if translation language is unavailable.
			if( self::$language === Null )
			{
				// Set translation language.
				self::setLanguage( self::$defaultLanguage );
			}
			
			// Get translation strings.
			$translation = Util\Arr::ify( $ify, self::$language );
			
			// Check if translation is string.
			if( is_string( $translation ) )
			{
				// Check if translation string is inherit from other translation.
				if( $translation[0] === "^" )
				{
					return( self::translate( str_replace( "^", "", $translation ), ...$format ) );
				}
				return( Util\Str::fmt( $translation, ...$format ) );
			}
			throw new Error\TypeError( Util\Str::fmt( "Translation language must be string value, {+:ucfirst} given", type( $translation ) ) );
		}
		catch( Error\LookupError $e )
		{
			return( Null );
		}
	}
	
	/*
	 * Assertion for Timezone.
	 *
	 * @access Private Static
	 *
	 * @params String $timezone
	 *
	 * @return Void
	 */
	private static function assertTimeZone( String $timezone ): Void
	{
		if( self::isAvailableTimeZone( $timezone ) === False )
		{
			throw new Error\TypeError( Util\Str::fmt( "Unsupported timezone for {}", $timezone ) );
		}
	}
	
}

?>