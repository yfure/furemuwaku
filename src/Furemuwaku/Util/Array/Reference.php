<?php

namespace Yume\Fure\Util\Array;

use Countable;
use WeakMap;

class Reference implements Countable
{
	
	private Readonly WeakMap $weak;
	private Array $data;
	
	public function __construct( Array $data = [] )
	{
		$this->weak = new WeakMap;
		//$this->weak[$this] = __CLASS__;
		
		$this->data = $data;
		
		foreach( $data As $key => $val )
		{
			$this->__set( $key, $val );
		}
	}
	
	public function __isset( String $key ): Bool
	{
		return( isset( $this->data[$key] ) );
	}
	
	public function __unset( String $key ): Void
	{
		if( $this->data[$key] Instanceof Reference )
		{
			unset( $this->weak[$this->data[$key]] );
		}
		unset( $this->data[$key] );
	}
	
	public function __get( String $key ): Mixed
	{
		return( $this )->data[$key] ?? Null;
	}
	
	public function __set( String $key, Mixed $val ): Void
	{
		if( is_array( $val ) )
		{
			$obj = new Reference( $val );
			
			if( is_null( $key ) )
			{
				$this->data[] = $obj;
				$this->weak[$obj] = array_key_last( $this->data );
			}
			else {
				$this->data[$key] = $obj;
				$this->weak[$obj] = $key;
			}
		}
		else {
			if( is_null( $key ) )
			{
				$this->data[] = $val;
			}
			else {
				$this->data[$key] = $val;
			}
		}
	}
	
	public function count(): Int
	{
		return( count( $this->data ) );
	}
	
	public function dump(): Void
	{
		var_dump( $this );
	}
	
}

?>