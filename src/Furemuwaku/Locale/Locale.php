<?php

namespace Yume\Fure\Locale;

use DateTimeZone;

use Yume\Fure\Error;
use Yume\Fure\IO\Path;
use Yume\Fure\Locale\Clock;
use Yume\Fure\Locale\DateTime;
use Yume\Fure\Locale\Language;
use Yume\Fure\Support;
use Yume\Fure\Util;

/*
 * Locale
 *
 * @extends Yume\Fure\Support\Singletom
 *
 * @package Yume\Fure\Locale
 */
class Locale extends Support\Singleton {
	
	/*
	 * Default language translation.
	 *
	 * @access Public Static
	 *
	 * @values String
	 */
	public const DEFAULT_LANGUAGE = "en";
	
	/*
	 * Default date timezone.
	 *
	 * @access Public Static
	 *
	 * @values String
	 */
	public const DEFAULT_TIMEZONE = "Asia/Jakarta";
	
	/*
	 * Instance of class Clock.
	 *
	 * @access Public Readonly
	 *
	 * @values Yume\Fure\Locale\Clock\ClockInterface
	 */
	public Readonly Clock\ClockInterface $clock;
	
	/*
	 * Instance of class Language.
	 *
	 * @access Private
	 *
	 * @values Yume\Fure\Locale\Language\Language
	 */
	private ? Language\Language $language = Null;
	
	/*
	 * Instance of class DateTimeZone.
	 *
	 * @access Private
	 *
	 * @values DateTimeZone
	 */
	private ? DateTimeZone $timezone = Null;
	
	/*
	 * @inherit Yume\Fure\Support\Singleton
	 *
	 */
	protected function __construct() {
		$this->clock = new Clock\Clock;
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
	private static function assertTimeZone( String $timezone ): Void {
		if( self::isAvailableTimeZone( $timezone, False ) ) {
			throw new Error\UnexpectedError( Util\Strings::format( "Unsupported timezone for {}", $timezone ) );
		}
	}
	
	/*
	 * Return new DateTimeImmutable instance.
	 *
	 * @access Public Static
	 *
	 * @return Yume\Fure\Locale\DateTime\DateTimeImmutable
	 */
	public static function clock(): DateTime\DateTimeImmutable {
		return( self::self() )->clock->now();
	}
	
	/*
	 * Return Language instance.
	 *
	 * @access Public Static
	 *
	 * @return Yume\Fure\Locale\Language\Language
	 */
	public static function getLanguage(): Language\Language {
		if( self::self()->language === Null ) {
			self::setLanguage();
		}
		return( self::self() )->language;
	}
	
	/*
	 * Return language name.
	 *
	 * @access Public Static
	 *
	 * @return String
	 */
	public static function getLanguageName(): String {
		if( self::self()->language === Null ) {
			self::setLanguage();
		}
		return( self::self() )->language->language;
	}
	
	/*
	 * Return DateTimeZone instance.
	 *
	 * @access Public Static
	 *
	 * @return DateTimeZone
	 */
	public static function getTimeZone(): DateTimeZone {
		if( self::self()->timezone === Null ) {
			self::setTimeZone();
		}
		return( self::self() )->timezone;
	}
	
	/*
	 * Return date timezone name.
	 *
	 * @access Public Static
	 *
	 * @return String
	 */
	public static function getTimeZoneName(): String {
		if( self::self()->timezone === Null ) {
			self::setTimeZone();
		}
		return( self::self() )->timezone->getName();
	}
	
	/*
	 * Import translations module.
	 *
	 * @access Public Static
	 *
	 * @params String $name
	 * @params Array $optional
	 *
	 * @return Array|Yume\Fure\Locale\Language\Language
	 */
	public static function getTranslation( String $name, ? Array $optional = Null ): Array | Language\Language | Null {
		$lang = self::getLanguageName();
		$source = Path\Paths::AppLanguage->path( join( "/", [ $lang, "{$name}.php" ] ) );
		try {
			// Normalize translation name.
			$name = str_replace( "/", ".", $name );
			
			// Check if translation has imported.
			if( isset( self::self()->language[$name] ) ) {
				return( self::self()->language[$name] );
			}
			return( Support\Package::import( $source ) );
		}
		catch( Error\ModuleError ) {
			return( $optional ?? Null );
		}
	}
	
	/*
	 * Check if timezone is available.
	 *
	 * @access Public Static
	 *
	 * @params String $timezone
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public static function isAvailableTimeZone( String $timezone, ? Bool $optional = Null ): Bool {
		if( $optional === Null ) {
			return( in_array( $timezone, DateTimeZone::listIdentifiers( DateTimeZone::ALL ) ) );
		}
		return( $optional === self::isAvailableTimeZone( $timezone ) );
	}
	
	/*
	 * Set language.
	 *
	 * @access Public Static
	 *
	 * @params String $lang
	 *
	 * @return Void
	 */
	public static function setLanguage( ? String $lang = Null ): Void {
		// Normalize language name.
		$lang = strtolower( $lang ?? config( "locale.language", self::DEFAULT_LANGUAGE ) );
		
		// Check if language name not same.
		if( self::self()->language === Null ||
			self::self()->language->isLang( $lang, False ) ) {
			self::self()->language = new Language\Language( $lang );
		}
	}
	
	/*
	 * Set default application timezone.
	 *
	 * @access Public Static
	 *
	 * @params String|DateTimeZone $timezone
	 *
	 * @return Void
	 */
	public static function setTimeZone( String | DateTimeZone | Null $timezone = Null ): Void {
		$timezone ??= config( "locale" )->timezone ??= self::DEFAULT_TIMEZONE;
		if( self::self()->timezone === Null ||
			self::self()->timezone !== $timezone ) {
			self::assertTimeZone( $timezone );
			self::self()->timezone = new DateTimeZone( $timezone );
			
			// Set default locale date timezone.
			date_default_timezone_set( $timezone );
		}
	}
	
	/*
	 * Set language translations.
	 *
	 * @access Public Static
	 *
	 * @params Array|String|Yume\Fure\Locale\Language\Language $translation
	 *  Array of translations or translation names.
	 *  String translation name.
	 *  Language Instance.
	 *
	 * @return Void
	 */
	public static function setTranslation( Array | String | Language\Language $translation ): Void {
		if( is_string( $translation ) ) {
			$translation = [$translation];
		}
		foreach( $translation As $index => $value ) {
			if( is_string( $value ) ) $value = self::getTranslation( $index = $value, [] );
			if( is_array( $value ) ) {
				if( is_int( $index ) ) {
					foreach( $value As $key => $val ) {
						self::self()->language[$key] = $val;
					}
					continue;
				}
			}
			self::self()->language[str_replace( "/", ".", $index )] = $value;
		}
	}
	
	/*
	 * Return translation string by key name.
	 *
	 * @access Public Static
	 *
	 * @params String $key
	 * @params String $optional
	 * @params Bool $format
	 * @params Mixed $values
	 *
	 * @return String
	 */
	public static function translate( String $key, ? String $optional = Null, Bool $format = False, Mixed ...$values ): ? String {
		
		// Get translation strings.
		$translate = Util\Arrays::ify( $key, self::self()->language, False );
		
		// If translation is inherit another translation.
		while( static::isInheritTranslate( $translate ?? "", $match ) ) {
			
			// Re-translate inherited translation value.
			$translate = Util\Arrays::ify( $match['inherit'] ?? Util\Arrays::ifyJoin([ $match['source'], ...static::splitGroup( $match['group'] ) ]), self::self()->language, False );
		}
		$translate ??= $optional;

		// echo dump( [ self::self()->language, $translate, $key ], True );
		
		// If translation is available and
		// if format is allowed.
		if( $translate && $format ) {
			return( util\Strings::format( $translate, ...Util\Arrays::map( $values, fn( Int $i, Mixed $k, Mixed $v ) => $v ?? "" ) ) );
		}
		return( $translate );
	}

	/*
	 * Return if string is syntax of inherit translation message.
	 * 
	 * @access Static Private
	 * 
	 * @params String $translate
	 * @params Mixed &$matches
	 * 
	 * @return Bool
	 */
	static private function isInheritTranslate( String $translate, Mixed &$match = Null ): Bool {
		return( preg_match( "/^\@(?:[Ii]nherit\:(?<inherit>[^\n]+)|(?:(?<source>[^\<]+)<(?<group>[^\>]+)[\>]+))$/", $translate, $match, PREG_UNMATCHED_AS_NULL ) );
	}

	static private function splitGroup( String $group ): Array {
		return( Util\Arrays::map( split( $group, "<" ), fn( Int $i, Int $idx, String $value ) => trim( $value, "<>" ) ) );
	}
	
}

?>
