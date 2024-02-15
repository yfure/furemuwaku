<?php

namespace Yume\Fure\Http\Uri;

use JsonSerializable;

/*
 * Uri
 * 
 * Http Uri Implementation
 * 
 * @package Yume\Fure\Http\Uri
 */
class Uri implements UriInterface, JsonSerializable {

	/*
	 * Absolute http and https URIs require a host per RFC 7230 Section 2.7
	 * but in generic URIs the host can be empty. So for http(s) URIs
	 * we apply this default host when no host is given yet to form a
	 * valid URI.
	 * 
	 * @access Private Static
	 * 
	 * @values String
	 */
	private const HTTP_DEFAULT_HOST = "localhost";

	/**
	 * Unreserved characters for use in a regex.
	 *
	 * @see https://datatracker.ietf.org/doc/html/rfc3986#section-2.3
	 */
	private const CHAR_UNRESERVED = "a-zA-Z0-9_\-\.~";

	/**
	 * Sub-delims for use in a regex.
	 *
	 * @see https://datatracker.ietf.org/doc/html/rfc3986#section-2.2
	 */
	private const CHAR_SUB_DELIMS = "!\$&\'\(\)\*\+,;=";

	

	/** @var string Uri scheme. */
	private String $scheme = "";

	/** @var string Uri user info. */
	private String $userInfo = "";

	/** @var string Uri host. */
	private String $host = "";

	/** @var int|null Uri port. */
	private ?Int $port = Null;

	/** @var string Uri path. */
	private String $path = "";

	/** @var string Uri query string. */
	private String $query = "";

	/** @var string Uri fragment. */
	private String $fragment = "";

	/** @var string|null String representation */
	private ?String $composedComponents = Null;

	/*
	 * Construct method of class Uri.
	 * 
	 * @access Public Initialize
	 * 
	 * @params String $uri
	 * 
	 * @return Void
	 */
	public function __construct( String $uri = "" )
	{
		if( valueIsNotEmpty( $uri ) ) {
			$parts = $this->parse( $uri );
			if( $parts === False ) {
				throw new MalformedUriException("Unable to parse URI: $uri");
			}
			$this->applyParts( $parts );
		}
	}

	public function __toString(): String {
		if ($this->composedComponents === null) {
			$this->composedComponents = UriUtility::composeComponents(
				$this->scheme,
				$this->getAuthority(),
				$this->path,
				$this->query,
				$this->fragment
			);
		}

		return $this->composedComponents;
	}

	public function getScheme(): string
	{
		return $this->scheme;
	}

	public function getAuthority(): string
	{
		$authority = $this->host;
		if ($this->userInfo !== '') {
			$authority = $this->userInfo.'@'.$authority;
		}

		if ($this->port !== null) {
			$authority .= ':'.$this->port;
		}

		return $authority;
	}

	public function getUserInfo(): string
	{
		return $this->userInfo;
	}

	public function getHost(): string
	{
		return $this->host;
	}

	public function getPort(): ?int
	{
		return $this->port;
	}

	public function getPath(): string
	{
		return $this->path;
	}

	public function getQuery(): string
	{
		return $this->query;
	}

	public function getFragment(): string
	{
		return $this->fragment;
	}

	/**
	 * UTF-8 aware \parse_url() replacement.
	 *
	 * The internal function produces broken output for non ASCII domain names
	 * (IDN) when used with locales other than "C".
	 *
	 * On the other hand, cURL understands IDN correctly only when UTF-8 locale
	 * is configured ("C.UTF-8", "en_US.UTF-8", etc.).
	 *
	 * @see https://bugs.php.net/bug.php?id=52923
	 * @see https://www.php.net/manual/en/function.parse-url.php#114817
	 * @see https://curl.haxx.se/libcurl/c/CURLOPT_URL.html#ENCODING
	 *
	 * @return array|false
	 */
	public function parse(string $url)
	{
		// If IPv6
		$prefix = '';
		if (preg_match('%^(.*://\[[0-9:a-f]+\])(.*?)$%', $url, $matches)) {
			/** @var array{0:string, 1:string, 2:string} $matches */
			$prefix = $matches[1];
			$url = $matches[2];
		}

		/** @var string */
		$encodedUrl = preg_replace_callback(
			'%[^:/@?&=#]+%usD',
			static function ($matches) {
				return urlencode($matches[0]);
			},
			$url
		);

		$result = parse_url($prefix.$encodedUrl);

		if ($result === false) {
			return false;
		}

		return array_map('urldecode', $result);
	}

	public function withScheme($scheme): UriInterface
	{
		$scheme = $this->filterScheme($scheme);

		if ($this->scheme === $scheme) {
			return $this;
		}

		$new = clone $this;
		$new->scheme = $scheme;
		$new->composedComponents = null;
		$new->removeDefaultPort();
		$new->validateState();

		return $new;
	}

	public function withUserInfo($user, $password = null): UriInterface
	{
		$info = $this->filterUserInfoComponent($user);
		if ($password !== null) {
			$info .= ':'.$this->filterUserInfoComponent($password);
		}

		if ($this->userInfo === $info) {
			return $this;
		}

		$new = clone $this;
		$new->userInfo = $info;
		$new->composedComponents = null;
		$new->validateState();

		return $new;
	}

	public function withHost($host): UriInterface
	{
		$host = $this->filterHost($host);

		if ($this->host === $host) {
			return $this;
		}

		$new = clone $this;
		$new->host = $host;
		$new->composedComponents = null;
		$new->validateState();

		return $new;
	}

	public function withPort($port): UriInterface
	{
		$port = $this->filterPort($port);

		if ($this->port === $port) {
			return $this;
		}

		$new = clone $this;
		$new->port = $port;
		$new->composedComponents = null;
		$new->removeDefaultPort();
		$new->validateState();

		return $new;
	}

	public function withPath($path): UriInterface
	{
		$path = $this->filterPath($path);

		if ($this->path === $path) {
			return $this;
		}

		$new = clone $this;
		$new->path = $path;
		$new->composedComponents = null;
		$new->validateState();

		return $new;
	}

	public function withQuery($query): UriInterface
	{
		$query = $this->filterQueryAndFragment($query);

		if ($this->query === $query) {
			return $this;
		}

		$new = clone $this;
		$new->query = $query;
		$new->composedComponents = null;

		return $new;
	}

	public function withFragment($fragment): UriInterface
	{
		$fragment = $this->filterQueryAndFragment($fragment);

		if ($this->fragment === $fragment) {
			return $this;
		}

		$new = clone $this;
		$new->fragment = $fragment;
		$new->composedComponents = null;

		return $new;
	}

	public function jsonSerialize(): string
	{
		return $this->__toString();
	}

	/**
	 * Apply parse_url parts to a URI.
	 *
	 * @param array $parts Array of parse_url parts to apply.
	 */
	private function applyParts(array $parts): void
	{
		$this->scheme = isset($parts['scheme'])
			? $this->filterScheme($parts['scheme'])
			: '';
		$this->userInfo = isset($parts['user'])
			? $this->filterUserInfoComponent($parts['user'])
			: '';
		$this->host = isset($parts['host'])
			? $this->filterHost($parts['host'])
			: '';
		$this->port = isset($parts['port'])
			? $this->filterPort($parts['port'])
			: null;
		$this->path = isset($parts['path'])
			? $this->filterPath($parts['path'])
			: '';
		$this->query = isset($parts['query'])
			? $this->filterQueryAndFragment($parts['query'])
			: '';
		$this->fragment = isset($parts['fragment'])
			? $this->filterQueryAndFragment($parts['fragment'])
			: '';
		if (isset($parts['pass'])) {
			$this->userInfo .= ':'.$this->filterUserInfoComponent($parts['pass']);
		}

		$this->removeDefaultPort();
	}

	/**
	 * @param mixed $scheme
	 *
	 * @throws \InvalidArgumentException If the scheme is invalid.
	 */
	private function filterScheme($scheme): string
	{
		if (!is_string($scheme)) {
			throw new \InvalidArgumentException('Scheme must be a string');
		}

		return \strtr($scheme, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz');
	}

	/**
	 * @param mixed $component
	 *
	 * @throws \InvalidArgumentException If the user info is invalid.
	 */
	private function filterUserInfoComponent($component): string
	{
		if (!is_string($component)) {
			throw new \InvalidArgumentException('User info must be a string');
		}

		return preg_replace_callback(
			'/(?:[^%'.self::CHAR_UNRESERVED.self::CHAR_SUB_DELIMS.']+|%(?![A-Fa-f0-9]{2}))/',
			[$this, 'rawurlencodeMatchZero'],
			$component
		);
	}

	/**
	 * @param mixed $host
	 *
	 * @throws \InvalidArgumentException If the host is invalid.
	 */
	private function filterHost($host): string
	{
		if (!is_string($host)) {
			throw new \InvalidArgumentException('Host must be a string');
		}

		return \strtr($host, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz');
	}

	/**
	 * @param mixed $port
	 *
	 * @throws \InvalidArgumentException If the port is invalid.
	 */
	private function filterPort($port): ?int
	{
		if ($port === null) {
			return null;
		}

		$port = (int) $port;
		if (0 > $port || 0xFFFF < $port) {
			throw new \InvalidArgumentException(
				sprintf('Invalid port: %d. Must be between 0 and 65535', $port)
			);
		}

		return $port;
	}

	private function removeDefaultPort(): void
	{
		if ($this->port !== null && UriUtility::isDefaultPort($this)) {
			$this->port = null;
		}
	}

	/**
	 * Filters the path of a URI
	 *
	 * @param mixed $path
	 *
	 * @throws \InvalidArgumentException If the path is invalid.
	 */
	private function filterPath($path): string
	{
		if (!is_string($path)) {
			throw new \InvalidArgumentException('Path must be a string');
		}

		return preg_replace_callback(
			'/(?:[^'.self::CHAR_UNRESERVED.self::CHAR_SUB_DELIMS.'%:@\/]++|%(?![A-Fa-f0-9]{2}))/',
			[$this, 'rawurlencodeMatchZero'],
			$path
		);
	}

	/**
	 * Filters the query string or fragment of a URI.
	 *
	 * @param mixed $str
	 *
	 * @throws \InvalidArgumentException If the query or fragment is invalid.
	 */
	private function filterQueryAndFragment($str): string
	{
		if (!is_string($str)) {
			throw new \InvalidArgumentException('Query and fragment must be a string');
		}

		return preg_replace_callback(
			'/(?:[^'.self::CHAR_UNRESERVED.self::CHAR_SUB_DELIMS.'%:@\/\?]++|%(?![A-Fa-f0-9]{2}))/',
			[$this, 'rawurlencodeMatchZero'],
			$str
		);
	}

	private function rawurlencodeMatchZero(array $match): string
	{
		return rawurlencode($match[0]);
	}

	private function validateState(): void
	{
		if ($this->host === '' && ($this->scheme === 'http' || $this->scheme === 'https')) {
			$this->host = self::HTTP_DEFAULT_HOST;
		}

		if ($this->getAuthority() === '') {
			if (0 === strpos($this->path, '//')) {
				throw new MalformedUriException('The path of a URI without an authority must not start with two slashes "//"');
			}
			if ($this->scheme === '' && false !== strpos(explode('/', $this->path, 2)[0], ':')) {
				throw new MalformedUriException('A relative URI must not have a path beginning with a segment containing a colon');
			}
		}
	}

}

?>