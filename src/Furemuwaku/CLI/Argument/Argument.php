<?php

namespace Yume\Fure\CLI\Argument;

use ArrayAccess;

use Yume\Fure\Util\Json;
use Yume\Fure\Util\RegExp;

/*
 * Argument
 *
 * @package Yume\Fure\CLI\Argument
 */
class Argument implements ArrayAccess
{
	
	/*
	 * Argument values parsed.
	 *
	 * @access Private Readonly
	 *
	 * @values Array
	 */
	private Readonly Array $args;
	
	/*
	 * File name.
	 *
	 * @access Public Readonly
	 *
	 * @values String
	 */
	public Readonly String $file;
	
	/*
	 * Construct method of class Argument.
	 *
	 * @access Public Instance
	 *
	 * @params Array $argv
	 *
	 * @return Void
	 */
	public function __construct( ? Array $argv = Null )
	{
		$this->parse( $argv );
	}
	
	/*
	 * Value builder.
	 *
	 * @access Private
	 *
	 * @params Int|String $name
	 * @params Mixed $value
	 * @params Bool $long
	 *
	 * @return Yume\Fure\CLI\Argument\ArgumentValue
	 *
	 * @throws Yume\Fure\CLI\Argument\ArgumentJsonValueError
	 */
	private function build( Int | String $name, Mixed $value, Bool $long = False ): ArgumentValue
	{
		try
		{
			$value = $this->value( $value );
		}
		catch( Json\JsonError $e )
		{
			if( is_int( $name ) )
			{
				$name = $value;
			}
			throw new ArgumentJsonValueError( $name, previous: $e );
		}
		return( new ArgumentValue( ...[
			"name" => $name,
			"long" => $long,
			...$value
		]));
	}
	
	/*
	 * Get value.
	 *
	 * @access Public
	 *
	 * @params Int|String $arg
	 * @params Mixed $default
	 *
	 * @return Mixed
	 */
	public function get( Int | String $arg, Mixed $default = Null ): Mixed
	{
		return( $this )->args[$arg] ?? $default;
	}
	
	/*
	 * Get filename.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getFileName(): String
	{
		return( $this )->file;
	}
	
	/*
	 * Has argument.
	 *
	 * @access Public
	 *
	 * @params Int|String $arg
	 *
	 * @return Bool
	 */
	public function has( Int | String $arg ): Bool
	{
		return( isset( $this->args[$arg] ) );
	}
	
	/*
	 * Whether or not an offset exists.
	 *
	 * @access Public
	 *
	 * @params Mixed $offset
	 *
	 * @return Bool
	 */
	public function offsetExists( Mixed $offset ): Bool
	{
		return( isset( $this->args[$offset] ) );
	}
	
	/*
	 * Returns the value at specified offset.
	 *
	 * @access Public
	 *
	 * @params Mixed $offset
	 *
	 * @return Mixed
	 */
	public function offsetGet( Mixed $offset ): Mixed
	{
		return( $this->offsetExists( $offset ) ? $this->args[$offset] : Null );
	}
	
	/*
	 * Assigns a value to the specified offset.
	 *
	 * @access Public
	 *
	 * @params Mixed $offset
	 * @params Mixed $value
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\CLI\Argument\ArgumentError
	 */
	public function offsetSet( Mixed $offset, Mixed $value ): Void
	{
		throw new ArgumentError( $offset, ArgumentError::SET_ERROR );
	}
	
	/*
	 * Unsets an offset.
	 *
	 * @access Public
	 *
	 * @params Mixed $offset
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\CLI\Argument\ArgumentError
	 */
	public function offsetUnset( Mixed $offset ): Void
	{
		throw new ArgumentError( $offset, ArgumentError::UNSET_ERROR );
	}
	
	/*
	 * Parse command line argument values.
	 *
	 * Note, this Code or Script is not original by Yume
	 * Creators or Developers, it is developed from
	 * Patrick Fisher's source code.
	 *
	 * @author Patrick Fisher
	 * @source https://github.com/pwfisher/CommandLine.php
	 *
	 * @modify Ari Setiawan (hxAri)
	 *
	 * @access Private
	 *
	 * @params Array $argv
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\CLI\Argument\ShortOptionError
	 */
	private function parse( ? Array $argv = Null )
	{
		// Get command line argument value.
		$argv = $argv ?? $_SERVER['argv'] ?? [];
		$args = [];
		
		// Remove filename from argument values.
		$this->file = array_shift( $argv );
		
		// Counting argument based on argument values length.
		for( $i = 0, $len = count( $argv ); $i < $len; $i++ )
		{
			// Get argument value.
			$arg = $argv[$i] ?? Null;
			
			// Index number.
			$idx = $i +1;
			
			// Skip if argument value has unset.
			if( $arg === Null ) continue;
			
			/*
			 * Check if the option of the argument is
			 * not an empty string enclosed in single
			 * or double quotes.
			 *
			 */
			if( $arg !== "" )
			{
				// If argument value is long option.
				if( substr( $arg, 0, 2 ) === "--" )
				{
					// Get position equal symbol position.
					$eqPost = strpos( $arg, "=" );
					
					// If argument has equal symbol.
					if( $eqPost !== False )
					{
						$key = substr( $arg, 2, $eqPost -2 );
						$val = substr( $arg, $eq +1 );
					}
					else {
						
						// Get key name.
						$key = substr( $arg, 2 );
						
						// Index value.
						$val = $argv[$idx] ?? "";
						
						// If argument value is not enclosed empty string.
						if( $val !== "" )
						{
							// If doesn't minus symbol.
							if( $idx < $len && strlen( $val ) !== 0 && $val[0] !== "-" )
							{
								$i++;
							}
							else {
								
								// If argument is not exists.
								if( isset( $args[$key] ) === False ) $val = True;
							}
						}
						else {
							
							/*
							 * Since empty values will still be added to
							 * named options so that empty strings enclosed
							 * by single or double quotes are not registered
							 * again to the argument we unset them
							 * from the $argv list.
							 *
							 */
							unset( $argv[$idx] );
						}
					}
					$args[$key] = $this->build( $key, $val, True );
				}
				
				// If argument value is short option.
				else if( substr( $arg, 0, 1 ) === "-" )
				{
					// If position 2 has equal symbol.
					if( substr( $arg, 2, 1 ) === "=" )
					{
						$key = substr( $arg, 1, 1 );
						$val = substr( $arg, 3 );
						
						$args[$key] = $this->build( $key, $val, False );
					}
					else {
						throw new ArgumentShortOptionError( $arg );
					}
				}
				else {
					$args[] = $arg;
				}
			}
			else {
				$args[] = $arg;
			}
		}
		
		// Mapping all arguments.
		foreach( $args As $name => $value )
		{
			// Skip if value has builded.
			if( $value Instanceof ArgumentValue ) continue;
			
			// Build value.
			$args[$name] = $this->build( $name, $value, False );
		}
		$this->args = $args;
	}
	
	/*
	 * Value normalization.
	 *
	 * @access Private
	 *
	 * @params Mixed $value
	 *
	 * @return Array
	 */
	private function value( Mixed $value ): Array
	{
		// If value is Bool or Int type.
		if( is_bool( $value ) ||
			is_int( $value ) )
		{
			$value = ( Bool ) $value;
		}
		
		// If value is String type.
		if( is_string( $value ) )
		{
			$value = match( ucfirst( strtolower( $value ) ) )
			{
				// Nullable
				"?", "None", "Null" => Null,
				
				// Boolean (True)
				"I", "1", "Y", "Yes", "True" => True,
				
				// Boolean (False)
				"!", "0", "N", "Not", "False" => False,
				
				// Re-Match
				default => match( True )
				{
					// Number (Integer)
					RegExp\RegExp::test( "/^(?:[0-9]{1})([0-9_]{1,}[0-9]{1})*$/", $value ) => ( Int ) $value,
					
					// Number (Floating)
					RegExp\RegExp::test( "/^(?:[0-9]+)\.(?:[0-9]+)$/", $value ) => ( Float ) $value,
					
					// Array
					RegExp\RegExp::test( "/^(?:\[[^\]]*\]|\{[^\}]*\})$/", $value ) => Json\Json::decode( $value, True ),
					
					default => $value
				}
			};
		}
		return([
			"type" => type( $value ),
			"value" => $value
		]);
	}
	
}

?>