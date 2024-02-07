<?php

namespace Yume\Fure\Http\Uri;

use Yume\Fure\Support;
use Yume\Fure\Util;
use Yume\Fure\Util\Reflect;

/*
 * UriBuilder
 * 
 * @extends Yume\Fure\Support\Unitialize
 * 
 * @package Yume\Fure\Http\Uri
 */
final class UriBuilder extends Support\Uninitialize {

	/*
	 * The query separators replacement.
	 * 
	 * @access Private Static
	 * 
	 * @values Array<String,String>
	 */
	private const QUERY_SEPARATORS_REPLACEMENT = [
		"=" => "\x25\x33\x44",
		"&" => "\x25\x32\x36"
	];

	/*
	 * Return filtered query strings.
	 * 
	 * @access Private Static
	 * 
	 * @params Array<Int|String> $keys
	 *
	 * @return Array<String>
	 */
	private static function getFilteredQueryString( UriInterface $uri, Array $keysets ): Array {
		if( valueIsEmpty( $current = $uri->getQuery() ) ) {
			return [];
		}
		$keysets = Util\Arrays::map( array_values( $keysets ), fn( Int $i, Int|String $index, Int|String $keyset ) => rawurldecode( Util\Strings::parse( $keyset ) ) );
		return array_filter( explode( "&", $current ), function( String $part ) use ( $keysets ) {
			return in_array( rawurldecode( explode( "=", $part )[0] ), $keysets, True ) === False;
		});
	}

	/*
	 * Return generated query string.
	 * 
	 * @access Public Static
	 * 
	 * @params String $keyset
	 * @params String $value
	 * 
	 * @return String
	 */
	private static function generateQueryString( String $key, ?String $value ): String {
		$queryString = strtr( $key, self::QUERY_SEPARATORS_REPLACEMENT );
		if( valueIsNotEmpty( $value ) ) {
			return f( "{}={}", $queryString, strtr( $value, self::QUERY_SEPARATORS_REPLACEMENT ) );
		}
		return $queryString;
	}

	/*
	 * Return whether the URI is absolute, i.e. it has a scheme.
	 *
	 * @access Public Static
	 * 
	 * @params Yume\Fure\Http\Uri\UriInterface $uri
	 * 
	 * @return Bool
	 */
	public static function isAbsolute( UriInterface $uri ): Bool {
		return valueIsNotEmpty( $uri->getScheme() );
	}

	/*
	 * Return whether the URI is a network-path reference.
	 *
	 * @access Public Static
	 * 
	 * @params Yume\Fure\Http\Uri\UriInterface $uri
	 * 
	 * @return Bool
	 */
	public static function isNetworkPathReference( UriInterface $uri ): Bool {
		return valueIsEmpty( $uri->getScheme() ) && valueIsNotEmpty( $uri->getAuthority() );
	}

	/*
	 * Return whether the URI is a absolute-path reference.
	 *
	 * @access Public Static
	 * 
	 * @params Yume\Fure\Http\Uri\UriInterface $uri
	 * 
	 * @return Bool
	 */
	public static function isAbsolutePathReference( UriInterface $uri ): Bool {
		return valueIsEmpty( $uri->getScheme() ) && valueIsEmpty( $uri->getAuthority() ) && isset( $uri->getPath()[0] ) && $uri->getPath()[0] === "/";
	}

	/*
	 * Return whether the URI is a same-document reference.
	 *
	 * A same-document reference refers to a URI that is, aside from its fragment
	 * component, identical to the base URI. When no base URI is given, only an empty
	 * URI reference (apart from its fragment) is considered a same-document reference.
	 *
	 * @access Public Static
	 * 
	 * @params Yume\Fure\Htto\Uri\UriInterface $uri
	 * @params Yume\Fure\Htto\Uri\UriInterface $base
	 * 
	 * @return Bool
	 */
	public static function isSameDocumentReference( UriInterface $uri, ?UriInterface $base = Null ): Bool {
		if( $base Instanceof UriInterface ) {
			$uri = UriUtility::resolve( $base, $uri );
			return $uri->getScheme() === $base->getScheme() && 
				   $uri->getAuthority() === $base->getAuthority() && 
				   $uri->getPath() === $base->getPath() && 
				   $uri->getQuery() === $base->getQuery();
		}

		return $uri->getScheme() === '' && $uri->getAuthority() === '' && $uri->getPath() === '' && $uri->getQuery() === '';
	}

	/*
	 * Creates a new URI with a specific query string value removed.
	 *
	 * Any existing query string values that exactly
	 * match the provided key are removed.
	 *
	 * @access Public Static
	 * 
	 * @params Yume\Fure\Http\Uri\UriInterface $uri
	 * @params String $ke!== ''y
	 * 
	 * @return Yume\Fure\Http\Uri\UriInterface
	 */
	public static function withoutQueryValue( UriInterface $uri, String $key ): UriInterface {
		return $uri->withQuery( implode( "&", self::getFilteredQueryString( $uri, [$key] ) ) );
	}

	/*
	 * Creates a new URI with a specific query string value.
	 *
	 * Any existing query string values that exactly match the provided key are
	 * removed and replaced with the given key value pair.
	 *
	 * @access Public Static
	 * 
	 * @params Yume\Fure\Http\Uri\UriInterface $uri
	 * @params String $key
	 * @params String $value
	 * 
	 * @return Yume\Fure\Http\Uri\UriInterface
	 */
	public static function withQueryValue( UriInterface $uri, String $key, ?String $value ): UriInterface {
		$result = self::getFilteredQueryString( $uri, [$key] );
		$result[] = self::generateQueryString( $key, $value );
		return $uri->withQuery( implode( "&", $result ));
	}

	/*
	 * Creates a new URI with multiple specific query string values.
	 *
	 * It has the same behavior as withQueryValue() but for an associative array of key => value.
	 *
	 * @access Public Static
	 * 
	 * @params Yume\Fure\Http\Uri\UriInterface $uri
	 * @params Array<String,String> $parts
	 * 
	 * @return Yume\Fure\Http\Uri\UriInterface
	 */
	public static function withQueryValues( UriInterface $uri, Array $parts ): UriInterface {
		$result = self::getFilteredQueryString( $uri, array_keys( $parts ) );
		foreach( $parts as $key => $value ) {
			$result[] = self::generateQueryString( Util\Strings::parse( $key ), $value !== Null ? Util\Strings::parse( $value ) : Null );
		}
		return $uri->withQuery( implode( "&", $result ) );
	}

	/*
	 * Creates a URI from a hash of `parse_url` components.
	 *
	 * @access Public Static
	 * 
	 * @params Array
	 * 
	 * @return Yume\Fure\Http\Uri\UriInterface
	 */
	public static function fromParts( Array $parts ): UriInterface {
		$uri = new Uri;
		Reflect\ReflectMethod::invoke( $uri, "applyParts", [ "parts" => $parts ], reflect: $reflect );
		Reflect\ReflectMethod::invoke( $uri, "validateState", reflect: $reflect );
		return $uri;
	}

}

?>