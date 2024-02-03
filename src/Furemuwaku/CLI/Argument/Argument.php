<?php

namespace Yume\Fure\CLI\Argument;

use ArrayAccess;
use Countable;

use Yume\Fure\Util;
use Yume\Fure\Util\Json;
use Yume\Fure\Util\RegExp;

/*
 * Argument
 *
 * Command Line Interface parser.
 *
 * @package Yume\Fure\CLI\Argument
 */
class Argument implements ArrayAccess, Countable {
	
	/*
	 * Argument values parsed.
	 *
	 * @access Private
	 *
	 * @values Array
	 */
	private Array $args;
	
	/*
	 * Command name.
	 *
	 * The second argument after the file name will be considered a command if it does not have an option type.
	 *
	 * @access Public Readonly
	 *
	 * @values String
	 */
	public Readonly ? String $command;
	
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
	public function __construct( ? Array $argv = Null ) {
		$this->parse( $argv );
	}
	
	/*
	 * Get argument.
	 *
	 * @access Public
	 *
	 * @params Int|String $arg
	 *
	 * @return Mixed
	 */
	public function __get( Int | String $arg ): Mixed {
		return( $this )->get( $arg );
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
	private function build( Int | String $name, Mixed $value, Bool $long = False ): ArgumentValue {
		try {
			$value = $this->value( $value );
		}
		catch( Json\JsonError $e ) {
			if( is_int( $name ) ) {
				$name = $value;
			}
			throw new ArgumentJsonValueError( $name, previous: $e );
		}
		return( new ArgumentValue( ...[ "name" => $name, "long" => $long ], ...$value ) );
	}
	
	/*
	 * Count arguments.
	 *
	 * @access Public
	 *
	 * @return Int
	 */
	public function count(): Int {
		return( count( $this->args ) );
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
	public function get( Int | String $arg, Mixed $default = Null ): Mixed {
		return( $this )->args[$arg] ?? $default;
	}

	/*
	 * Return all arguments.
	 * 
	 * @access Public
	 * 
	 * @return Array<ArgumentValue>
	 */
	public function getAll(): Array {
		return( $this->args );
	}
	
	/*
	 * Get command name.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getCommandName(): String {
		return( $this )->command;
	}
	
	/*
	 * Get filename.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getFileName(): String {
		return( $this )->file;
	}
	
	/*
	 * Has argument.
	 *
	 * @access Public
	 *
	 * @params Int|String $arg
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public function has( Int | String $arg, ? Bool $optional = Null ): Bool {
		return( $optional === Null ? isset( $this->args[$arg] ) : isset( $this->args[$arg] ) === $optional );
	}
	
	/*
	 * Return if command is available.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function hasCommand(): Bool {
		return( $this )->command !== Null;
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
	public function offsetExists( Mixed $offset ): Bool {
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
	public function offsetGet( Mixed $offset ): Mixed {
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
	public function offsetSet( Mixed $offset, Mixed $value ): Void {
		if( $value Instanceof ArgumentValue === False ) {
			throw new ArgumentError( [ $offset, ArgumentValue::class, type( $value ) ], ArgumentError::SET_ERROR );
		}
		$this->args[$offset] = $value;
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
	public function offsetUnset( Mixed $offset ): Void {
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
	private function parse( ? Array $argv = Null ) {
		$argv = $argv ?? $_SERVER['argv'] ?? [];
		$args = [];
		
		// Remove filename from argument values.
		$this->file = array_shift( $argv );
		
		// Counting argument based on argument values length.
		for( $i = 0, $len = count( $argv ); $i < $len; $i++ ) {
			$arg = $argv[$i] ?? Null;
			$idx = $i +1;
			if( $arg === Null ) continue;
			
			/*
			 * Check if the option of the argument is
			 * not an empty string enclosed in single
			 * or double quotes.
			 *
			 */
			if( $arg !== "" ) {
				if( substr( $arg, 0, 2 ) === "--" ) {
					$eqPost = strpos( $arg, "=" );
					if( $eqPost !== False ) {
						$key = substr( $arg, 2, $eqPost -2 );
						$val = substr( $arg, $eqPost +1 );
					}
					else {
						$key = substr( $arg, 2 );
						$val = $argv[$idx] ?? Null;
						
						// If argument value is not enclosed empty string.
						if( $val !== "" && $val !== Null ) {
							if( $idx < $len && strlen( $val ) !== 0 && $val[0] !== "-" ) {
								$i++;
							}
							else {
								if( isset( $args[$key] ) === False ) {
									$val = True;
								}
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
					
					/*
					 * If the argument option is given like
					 * this --= then it will not be considered.
					 *
					 */
					if( $key !== "" ) {
						$args[$key] = $this->build( $key, $val !== Null ? $val : Null, True );
					}
				}
				
				// If argument value is short option.
				else if( substr( $arg, 0, 1 ) === "-" ) {
					if( substr( $arg, 2, 1 ) === "=" ) {
						$key = substr( $arg, 1, 1 );
						$val = substr( $arg, 3 );
						
						$args[$key] = $this->build( $key, $val, False );
					}
					else {
						
						// If a short option is like this -xyz
						// then it will be considered invalid,
						// only like this -x
						if( strlen( $arg ) !== 2 ) {
							throw new ArgumentShortOptionError( $arg );
						}
						$args[$arg[1]] = True;
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
		foreach( $args As $name => $value ) {
			if( $value Instanceof ArgumentValue ) {
				continue;
			}
			$args[$name] = $this->build( $name, $value, False );
		}
		if( $command = array_values( $args )[0] ?? Null ) {
			if( $command->type->name === "String" && type( $command->name, "Integer" ) ) {
				$this->command = $command->value;
				unset( $args[$command->name] );
			}
		}
		else {
			$this->command = Null;
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
	private function value( Mixed $value ): Array {
		$value = match( True ) {
			is_bool( $value ) || is_int( $value ) => ( Bool ) $value,
			is_null( $value ) => True,
			is_string( $value ) => match( ucfirst( strtolower( $value ) ) ) {
				"?", "None", "Null" => Null,
				"I", "Y", "Yes", "True" => True,
				"!", "N", "Not", "False" => False,
				default => match( True ) {
					RegExp\RegExp::test( "/^(?:[0-9]{1})(?:[0-9_]{1,}(?:[0-9]{1})*)*$/", $value ) => ( Int ) $value,
					RegExp\RegExp::test( "/^(?:[0-9]+)\.(?:[0-9]+)$/", $value ) => ( Float ) $value,
					RegExp\RegExp::test( "/^(?:\[[^\]]*\]|\{[^\}]*\})$/", $value ) => Json\Json::decode( $value, True ),
					default => $value
				}
			}
		};
		return([
			"value" => $value,
			"type" => match( type( $value ) ) {
				"Array" => Util\Type::Array,
				"Boolean" => Util\Type::Bool,
				"Double" => Util\Type::Double,
				"Float" => Util\Type::Float,
				"Int" => Util\Type::Int,
				"Integer" => Util\Type::Integer,
				"NULL" => Util\Type::None,
				"String" => Util\Type::String,
				default => Util\Type::Mixed
			}
		]);
	}
	
}

?>
