<?php

namespace Yume\Fure\Locale\Language;

use Yume\Fure\Util;
use Yume\Fure\Util\Array;

/*
 * Language
 *
 * @extends Yume\Fure\Util\Array\Associative
 *
 * @package Yume\Fure\Locale\Language
 */
final class Language extends Array\Associative
{
	
	/*
	 * Construct method of class Language.
	 *
	 * @access Public Initialize
	 *
	 * @params String $language
	 * @params Array $translation
	 *
	 * @return Void
	 */
	public function __construct( public Readonly String $language, Array $translation = [] )
	{
		parent::__construct( $translation );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Array\Associative::__toString
	 *
	 */
	public function __toString()
	{
		return( Util\Strings::format( "{}<{}> {}", $this::class, $this->language, parent::__toString() ) );
	}
	
	/*
	 * Return if language is same or equals.
	 *
	 * @access Public Static
	 *
	 * @params String $lang
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public function isLang( String $lang, ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $this->isLang( $lang ) === $optional : $this->lang() === $lang );
	}
	
	/*
	 * Return language name.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function lang(): String
	{
		return( $this )->language;
	}
	
	/*
	 * @inherit Yume\Fure\Util\Array\Associative::offsetSet
	 *
	 */
	public function offsetSet( Mixed $offset, Mixed $value ): Void
	{
		// If value is Array type.
		if( is_array( $value ) || $value Instanceof Language )
		{
			// Check if value is exists and value is Instance of Language.
			if( $this[$offset] Instanceof Language )
			{
				$value = $this[$offset]->replace( $value );
			}
			else {
				$value = new Language( $this->language, $value );
			}
		}
		parent::offsetSet( $offset, $value );
	}
	
	/*
	 * Recursive for resolve required translation.
	 *
	 * @access Private
	 *
	 * @params Array $array
	 *
	 * @return Array
	 */
	private function recursive( Array $array ): Array
	{
		$stack = [];
		
		foreach( $array As $key => $value )
		{
			if( preg_match( "/^\@import(?:\:(?<key>[^\n]+))*$/i", $key, $match, PREG_UNMATCHED_AS_NULL ) )
			{
				// If only one translation required.
				if( is_array( $value ) === False ) $value = [$value];
				
				// Mapping required translations.
				$value = array_map( fn( String $require ) => Locale\Locale::getTranslation( $require, [] ) );
				
				// Resolve key name.
				$key = $match['key'] ?? Null;
			}
			
			// If value is array.
			if( is_array( $value ) )
			{
				// Re-recursive values.
				$value = $this->recursive( $value );
				
				// If key not available.
				if( is_null( $key ) )
				{
					$unpack = [
						...$stack,
						...$value
					];
				}
				else {
					$value = [
						$key => $value
					];
				}
				$stack = array_replace_recursive( $stack, $unpack ?? $value );
			}
			else {
				$stack[$key] = $value;
			}
		}
		return( $stack );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Array\Arrayable::replace
	 *
	 */
	public function replace( Array | Array\Arrayable $array, Bool $recursive = False ): Static
	{
		foreach( $array As $offset => $value )
		{
			// Check if value of element is Array.
			if( is_array( $value ) )
			{
				// Skip create new Static Instance if recursion is
				// allowed and if previous element value is Instanceof Arrayable.
				if( $recursive && $this[$offset] Instanceof Array\Arrayable )
				{
					continue;
				}
				$array[$offset] = new Static( $this->language, $value );
			}
		}
		return( parent::replace( $array, $recursive ) );
	}
	
}

?>