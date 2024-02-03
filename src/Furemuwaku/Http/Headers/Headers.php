<?php

namespace Yume\Fure\Http\Header;

use Yume\Fure\Util\Arr;

/*
 * Headers
 * 
 * Http Header Map
 * 
 * @extends Yume\Fure\Util\Arr\Associative
 * 
 * @package Yune\Fure\Http\Header
 */
class Headers extends Arr\Associative {

	/*
	 * @inherit Yume\Fure\Util\Arr\Associative::__construct
	 * 
	 */
	final public function __construct( ?Headers $headers = Null ) {
		parent::__construct( $headers ?? [], insensitive: True );
	}

	/*
	 * @inherit Yume\Fure\Util\Arr\Associative->normalize
	 * 
	 */
	protected function normalize( Mixed $key ): String {
		if( is_string( $key ) ) {
			Header::assertKeyset( ( String ) $key );
		}
		else {
			throw new HeaderError( [$key], HeaderError::KEYSET_ERROR );
		}
		return parent::normalize( $key );
	}

	/*
	 * @inherit Yume\Fure\Util\Arr\Associative->offsetSet
	 * 
	 */
	public function offsetSet( Mixed $offset, Mixed $value ): Void {
		if( is_string( $offset ) ) {
			if( $value Instanceof HeaderItem === False ) {
				$value = new HeaderItem( $offset, Header::normalizeValue( $offset, $value ) );
			}
			parent::offsetSet( $offset, $value );
		}
		throw new HeaderError( [$offset], HeaderError::KEYSET_ERROR );
	}

}

?>