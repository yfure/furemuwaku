<?php

namespace Yume\Fure\Http\Uri;

use Yume\Fure\Support;
use Yume\Fure\Util\RegExp;

/*
 * UriUtility
 * 
 * This class utility is sourced by https://github.com/guzzle/psr7
 * 
 * @source https://github.com/guzzle/psr7
 * 
 * @package Yume\Fure\Http\Uri
 */
final class UriUtility extends Support\Uninitialize {

	/*
	 * Capitalize percent encoding from uri.
	 * 
	 * @access Private Static
	 * 
	 * @params Yume\Fure\Http\Uri\UriInterface $uri
	 * 
	 * @return Yume\Fure\Http\Uri\UriInterface
	 */
	private static function capitalizePercentEncoding( UriInterface $uri ): UriInterface {
		$pattern = new RegExp\Pattern( "(?:%[A-Fa-f0-9]{2})++" );
		$callback = fn( RegExp\Matches $match ) => strtoupper( $match[0] );
		return $uri
			->withPath( $pattern->replace( $uri->getPath(), $callback ) )
			->withQuery( $pattern->replace( $uri->getQuery(), $callback ) );
	}

	/*
	 * Composes a URI reference string from its various components.
	 *
	 * @access Public Static
	 * 
	 * @params String $scheme
	 * @params String $authority
	 * @params String $path
	 * @params String $query
	 * @params String $fragment
	 * 
	 * @return String
	 */
	public static function composeComponents( ?String $scheme, ?String $authority, String $path, ?String $query, ?String $fragment ): String {
		$uri = [];
		if( valueIsNotEmpty( $scheme ) ) {
			$uri[] = ":";
		}
		if( valueIsNotEmpty( $authority ) || $scheme === "file" ) {
			$uri[] = "//";
			$uri[] = $authority;
		}
		if( valueIsNotEmpty( $authority ) && 
			valueIsNotEmpty( $path ) && 
			$path[0] != "/" 
		) {
			$path = f( "/{}", $path );
		}
		$uri[] = $path;
		if( valueIsNotEmpty( $query ) ) {
			$uri[] = "?";
			$uri[] = $query;
		}
		if( valueIsNotEmpty( $fragment ) ) {
			$uri[] = "#";
			$uri[] = $fragment;
		}
		return join( "", $uri );
	}

	/*
	 * Return the port number of URI.
	 * 
	 * @access Private Static
	 * 
	 * @params Yume\Fure\Http\Uri\UriInterface $uri
	 * 
	 * @return Int
	 */
	private static function computePort( UriInterface $uri ): Int {
		return $uri->getPort() !== Null ? $uri->getPort() : ( $uri->getScheme() === "https" ? 443 : 80 );
	}

	/*
	 * Decode unreserved characters from uri.
	 * 
	 * @access Private Static
	 * 
	 * @params Yume\Fure\Http\Uri\UriInterface $uri
	 * 
	 * @return Yume\Fure\Http\Uri\UriInterface
	 */
	private static function decodeUnreservedCharacters( UriInterface $uri ): UriInterface {
		$pattern = new RegExp\Pattern( "%(?:2D|2E|5F|7E|3[0-9]|[46][1-9A-F]|[57][0-9A])", [ "i" ] );
		$callback = fn( RegExp\Matches $match ) => rawurldecode( $match[0] );
		return $uri
			->withPath( $pattern->replace( $uri->getPath(), $callback, ) )
			->withQuery( $pattern->replace( $uri->getQuery(), $callback ) );
	}

	/*
	 * Return relative pathname of URI.
	 * 
	 * @access Private Static
	 * 
	 * @params Yume\Fure\Http\Uri\UriInterface $base
	 * @params Yume\Fure\Http\Uri\UriInterface $target
	 * 
	 * @return String
	 */
	private static function getRelativePath( UriInterface $base, UriInterface $target ): String
	{
		$sourceSegments = explode( "/", $base->getPath() );
		// $sourceLastSegment = array_pop( $sourceSegments );
		$targetSegments = explode( "/", $target->getPath() );
		$targetLastSegment = array_pop( $targetSegments );
		foreach( $sourceSegments as $i => $segment ) {
			if( isset( $targetSegments[$i] ) && $segment === $targetSegments[$i] ) {
				unset( $sourceSegments[$i], $targetSegments[$i] );
				continue;
			}
			break;
		}
		$targetSegments[] = $targetLastSegment;
		$relativePath = join( "", [ str_repeat( "../", count( $sourceSegments ) ),
			join( "/", $targetSegments )
		]);
		if( valueIsEmpty( $relativePath ) || strpos( explode( "/", $relativePath, 2 )[0], ":" ) !== False ) {
			$relativePath = f( "./{}", $relativePath );
		}
		else if( $relativePath[0] === "/" ) {
			$prefix = valueIsNotEmpty( $base->getAuthority() ) && valueIsEmpty( $base->getPath() ) ? "." : "./";
			$relativePath = join( "", [ $prefix, $relativePath ]);
		}
		return $relativePath;
	}

	/*
	 * Determines if a modified URL should be considered
	 * cross-origin with respect to an original URL.
	 * 
	 * @access Public Static
	 * 
	 * @params Yume\Fure\Http\Uri\UriInterface $original
	 * @params Yume\Fure\Http\Uri\UriInterface $modified
	 * 
	 * @return Bool
	 */
	public static function isCrossOrigin( UriInterface $original, UriInterface $modified ): Bool {
		return match( True ) {

			// Compare Binary safe case-insensitive.
			strcasecmp( $original->getHost(), $modified->getHost() ) !== 0,

			// Compare whether the URI Scheme is not Equal.
			$original->getScheme() !== $modified->getScheme(),

			// Compare whether the URI Port is not Same.
			self::computePort( $original ) !== self::computePort( $modified ) => True,

			// The URI is identified same as URI.
			default => False
		};
	}

	/*
	 * Return hether the URI has the default port of the current scheme.
	 *
	 * @params Yume\Fure\Http\Uri\UriInterface $uri
	 * 
	 * @return Bool
	 */
	public static function isDefaultPort( UriInterface $uri ): Bool
	{
		$port = $uri->getPort();
		if( $port !== Null ) {
			$scheme = strtoupper( $uri->getScheme() );
			$schemes = UriPort::names();
			return isset( $schemes[$scheme] ) && $schemes[$scheme] === $port;
		}
	}

	/*
	 * Return whether two URIs can be considered equivalent.
	 * 
	 * @access Public Static
	 * 
	 * @params Yume\Fure\Http\Uri\UriInterface $source
	 * @params Yume\Fure\Http\Uri\UriInterface $target
	 * @params Yume\Fure\Http\Uri\UriNormalize $flags
	 * 
	 * @return Bool
	 */
	public static function isEquivalent( UriInterface $source, UriInterface $target, UriNormalize $flags = UriNormalize::PRESERVING_NORMALIZATIONS ): Bool {
		return self::normalize( $source, $flags )->__toString() === self::normalize( $target, $flags )->__toString();
	}

	/*
	 * Return whether the URI is a relative-path reference.
	 *
	 * @params Yume\Fure\Http\Uri\UriInterface $uri
	 * 
	 * @return Bool
	 */
	public static function isRelativePathReference( UriInterface $uri ): Bool {
		return valueIsEmpty( $uri->getScheme() ) && valueIsEmpty( $uri->getAuthority() ) && ( valueIsEmpty( $uri->getPath() ) || $uri->getPath()[0] !== "/" );
	}

	/*
	 * Returns a normalized URI.
	 *
	 * @access Public Static
	 * 
	 * @params Yume\Fure\Http\Uri\UriInterface $uri
	 * @params Yume\Fure\Http\Uri\UriNormalize $flags
	 * 
	 * @return Yume\Fure\Http\Uri\UriInterface
	 */
	public static function normalize( UriInterface $uri, UriNormalize $flags = UriNormalize::PRESERVING_NORMALIZATIONS ): UriInterface {
		if( $flags->value & UriNormalize::CAPITALIZE_PERCENT_ENCODING->value ) {
			$uri = self::capitalizePercentEncoding($uri);
		}
		if ($flags->value & UriNormalize::DECODE_UNRESERVED_CHARACTERS->value ) {
			$uri = self::decodeUnreservedCharacters($uri);
		}
		if( $flags->value & UriNormalize::CONVERT_EMPTY_PATH->value && valueIsEmpty( $uri->getPath() ) && ( $uri->getScheme() === "http" || $uri->getScheme() === "https" ) ) {
			$uri = $uri->withPath( "/" );
		}
		if( $flags->value & UriNormalize::REMOVE_DEFAULT_HOST->value && $uri->getScheme() === "file" && $uri->getHost() === "localhost" ) {
			$uri = $uri->withHost( "" );
		}
		if( $flags->value & UriNormalize::REMOVE_DEFAULT_PORT->value && $uri->getPort() !== null && self::isDefaultPort( $uri ) ) {
			$uri = $uri->withPort( Null );
		}
		if( $flags->value & UriNormalize::REMOVE_DOT_SEGMENTS->value && self::isRelativePathReference( $uri, False ) ) {
			$uri = $uri->withPath( self::removeDotSegments( $uri->getPath() ) );
		}
		if( $flags->value & UriNormalize::REMOVE_DUPLICATE_SLASHES->value ) {
			$uri = $uri->withPath( RegExp\RegExp::replace( "\/\/++", $uri->getPath(), "/" ) );
		}
		if( $flags->value & UriNormalize::SORT_QUERY_PARAMETERS->value && valueIsEmpty( $uri->getQuery() ) ) {
			sort( $queryKeyValues = explode( "&", $uri->getQuery() ) );
			$uri = $uri->withQuery( implode( "&", $queryKeyValues ) );
		}
		return $uri;
	}

	/*
	 * Returns the target URI as a relative reference from the base URI.
	 *
	 * @access Public Static
	 * 
	 * @params Yume\Fure\Http\Uri\UriInterface $base
	 * @params Yume\Fure\Http\Uri\UriInterface $target
	 * 
	 * @return Yume\Fure\Http\Uri\UriInterface
	 */
	public static function relativize( UriInterface $base, UriInterface $target ): UriInterface {
		if( valueIsNotEmpty( $target->getScheme() ) && ( $base->getScheme() !== $target->getScheme() || valueIsEmpty( $target->getAuthority() ) && valueIsNotEmpty( $base->getAuthority() ) ) ) {
			return $target;
		}
		if( self::isRelativePathReference( $target ) ) {
			return $target;
		}
		if( valueIsNotEmpty( $target->getAuthority() ) && $base->getAuthority() !== $target->getAuthority() ) {
			return $target->withScheme( "" );
		}
		$emptyPathUri = $target
			->withScheme( "" )
			->withPath( "" )
			->withUserInfo( "" )
			->withPort( Null )
			->withHost( "" );
		if( $base->getPath() !== $target->getPath() ) {
			return $emptyPathUri->withPath( self::getRelativePath( $base, $target ) );
		}

		if( $base->getQuery() === $target->getQuery() ) {
			return $emptyPathUri->withQuery('');
		}
		if( $target->getQuery() === "" ) {
			$segments = explode( "/", $target->getPath() );
			$lastSegment = end( $segments );
			return $emptyPathUri->withPath( valueIsNotEmpty( $lastSegment ) ? $lastSegment : "./" );
		}
		return $emptyPathUri;
	}
	
	/*
	 * Removes dot segments from a path and returns the new path.
	 *
	 * @access Public Static
	 * 
	 * @params String $path
	 * 
	 * @return String
	 */
	public static function removeDotSegments( String $path ): String {
		if( valueIsEmpty( $path ) || $path === "/" ) {
			return $path;
		}
		$results = [];
		$segments = explode( "/", $path );
		foreach( $segments as $segment ) {
			if( $segment === ".." ) {
				array_pop( $results );
			}
			else if( $segment !== "." ) {
				$results[] = $segment;
			}
		}
		$results = implode( "/", $results );
		if( $path[0] === "/" && ( valueIsNotEmpty( $results ) || $results[0] !== "/" ) ) {
			$results = f( "/{}", $results );
		} elseif( valueIsNotEmpty( $results ) && ( $segment === "." || $segment === ".." ) ) {
			$results = f( "{}/", $results );
		}
		return $results;
	}

	/*
	 * Converts the relative URI into a new URI that is resolved against the base URI.
	 *
	 * @access Public Static
	 * 
	 * @params Yume\Fure\Http\Uri\UriInterface $base
	 * @params Yume\Fure\Http\Uri\UriInterface $original
	 * 
	 * @return Yume\Fure\Http\Uri\UriInterface
	 */
	public static function resolve( UriInterface $base, UriInterface $relative ): UriInterface {
		if( valueIsNotEmpty( $relative->__toString() ) ) {
			return $base;
		}
		if( valueIsNotEmpty( $relative->getScheme() ) ) {
			return $relative->withPath( self::removeDotSegments( $relative->getPath() ) );
		}
		if( valueIsNotEmpty( $relative->getAuthority() ) ) {
			$targetAuthority = $relative->getAuthority();
			$targetPath = self::removeDotSegments( $relative->getPath() );
			$targetQuery = $relative->getQuery();
		}
		else {
			$targetAuthority = $base->getAuthority();
			if( valueIsEmpty( $relative->getPath() ) ) {
				$targetPath = $base->getPath();
				$targetQuery = valueIsNotEmpty( $relative->getQuery() ) ? $relative->getQuery() : $base->getQuery();
			}
			else {
				if( $relative->getPath()[0] === "/" ) {
					$targetPath = $relative->getPath();
				}
				else {
					if( valueIsNotEmpty( $targetAuthority ) && valueIsEmpty( $base->getPath() ) ) {
						$targetPath = f( "/{}", $relative->getPath() );
					}
					else {
						$lastSlashPos = strrpos( $base->getPath(), "/" );
						if( $lastSlashPos === False ) {
							$targetPath = $relative->getPath();
						}
						else {
							$targetPath = join( "", [ substr( $base->getPath(), 0, $lastSlashPos + 1 ),
								$relative->getPath()
							]);
						}
					}
				}
				$targetPath = self::removeDotSegments( $targetPath );
				$targetQuery = $relative->getQuery();
			}
		}
		return new Uri( self::composeComponents(
			$base->getScheme(),
			$targetAuthority,
			$targetPath,
			$targetQuery,
			$relative->getFragment()
		));
	}

}

?>