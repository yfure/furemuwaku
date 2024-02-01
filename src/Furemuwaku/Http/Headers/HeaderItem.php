<?php

namespace Yume\Fure\Http\Header;

use Stringable;

/*
 * HeaderItem
 * 
 * @package Yume\Fure\Http\Header
 */
final class HeaderItem implements Stringable {

	/*
	 * Keyset header name.
	 * 
	 * @access Public Readonly
	 * 
	 * @values String
	 */
	public Readonly String $keyset;

	/*
	 * Array of header values.
	 * 
	 * @access Public Readonly
	 * 
	 * @values Array
	 */
	public Readonly ?Array $value;

	/*
	 * Instance of class HeaderItem.
	 * 
	 * @access Public Initialize
	 * 
	 * @params String $keyset
	 * @params Array<String> $value
	 * 
	 * @return Void
	 */
	public function __construct( String $keyset, Array $value = Null ) {
		$this->keyset = $keyset;
		$this->value = $value;
	}

	/*
	 * Convert class to String.
	 * 
	 * @access Public
	 * 
	 * @return String
	 */
	public function __toString(): String {
		return f( "{}: {}", $this->keyset, $this->line() );
	}

	/*
	 * Return comma sperarated header values.
	 * 
	 * @access Public
	 * 
	 * @return String
	 */
	public function line(): String {
		return join( "\x2c\x20", $this->value ?? [] );
	}

}

?>