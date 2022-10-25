<?php

namespace Yume\Fure\Support\Env;

use Yume\Fure\Error;
use Yume\Fure\IO;
use Yume\Fure\Support;
use Yume\Fure\Util;

/*
 * Env
 *
 * @extends Yume\Fure\Support\Design\Creational\Singleton
 *
 * @package Yume\Fure\Support\Env
 */
class Env extends Support\Design\Creational\Singleton implements EnvInterface
{
	
	/*
	 * Environment file name.
	 *
	 * @access Private
	 *
	 * @values String
	 */
	private String $filename = ".env";
	
	/*
	 * Environment created.
	 *
	 * @access Private
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	private Support\Data\DataInterface $vars;
	
	/*
	 * @inherit Yume\Fure\Support\Design\Creational\Singleton
	 *
	 */
	protected function __construct()
	{
		// Check if file environment is exists.
		if( IO\File\File::exists( $this->filename ) )
		{
			// Set all built-in environment variables.
			$this->vars = new Support\Data\Data( Util\Arr::map( $_ENV, function( $i, $env, $value )
			{
				// Check if value is not array type.
				if( is_array( $value ) === False )
				{
					// If environment name is `BOOTCLASSPATH`.
					if( $env === "BOOTCLASSPATH" )
					{
						return([
							"type" => "Array",
							"value" => explode( ":", $value )
						]);
					}
					else {
						
						// Match environment values.
						$match = $this->matchValue( $value );
						
						return([
							"type" => $type = $this->type( $match ),
							"value" => $this->value( $type, $match )
						]);
					}
				}
				return( $value );
			}));
		}
		else {
			throw new Error\EnvError( $this->filename, Env\EnvError::FILE_ERROR );
		}
	}
	
	/*
	 * @inherit Yume\Fure\Support\Env\EnvInterface
	 *
	 */
	public function getAll( Bool $type = False ): Support\Data\DataInterface
	{
		return( new Support\Data\Data( $type ? $this->vars->__toArray() : Util\Arr::map( $this->vars, fn( $i, $env, $value ) => $value->value )->__toArray() ) );
	}
	
	/*
	 * @inherit Yume\Fure\Support\Env\EnvInterface
	 *
	 */
	public function getEnv( String $env, Mixed $none = Null ): Mixed
	{
		if( $this->vars->__isset( $env ) )
		{
			return( $this->vars )->__get( $env );
		}
		return( $none );
	}
	
	/*
	 * @inherit Yume\Fure\Support\Env\EnvInterface
	 *
	 */
	public function getFilename(): String
	{
		return( $this->filename );
	}
	
	/*
	 * @inherit Yume\Fure\Support\Env\EnvInterface
	 *
	 */
	public function load(): Void
	{
		$this->parse( IO\File\File::readline( $this->filename ) )->register();
	}
	
	/*
	 * Parses the contents of the environment variable file line-by-line.
	 *
	 * @access Private
	 *
	 * @params Array $lines
	 *
	 * @return Void
	 */
	private function parse( Array $lines ): Static
	{
		// Mapping lines.
		Util\Arr::map( $lines, function( $i, $index, $line )
		{
			// Check if is not error.
			if( $parse = Support\RegExp\RegExp::match( "/^(?:(?<line>[\s\t]*(?<doccomment>(?<symbol>\#)[\s\t]*(?<description>[^\n]*))|(?<variable>(?<name>[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)[\s\t]*\=[\s\t]*(?<value>[^\n]*))))$/J", $line, True ) )
			{
				// If value is not null type and variable element is exists.
				if( isset( $parse['variable'] ) )
				{
					// If variable has value.
					if( $parse['value'] )
					{
						// Match environment values.
						$match = $this->matchValue( $parse['value'] );
						
						// Get variable type and value.
						$parse['value'] = $this->value( $parse['type'] = $this->type( $match ), $match );
					}
					
					// Set variable to environment collections.
					$this->vars->{ $parse['name'] } = [];
					$this->vars->{ $parse['name'] }->type = $parse['type'] ?? "None";
					$this->vars->{ $parse['name'] }->value = $parse['value'] ?? "";
				}
				return;
			}
			
			// If value of line is not empty.
			else if( valueIsNotEmpty( $line ) )
			{
				throw new Error\EnvError( 
					code: Error\EnvError::TOKEN_ERROR,
					message: [
						"message" => $line,
						"line" => $i++,
						"file" => $this->filename
					]
				);
			}
		});
		
		return( $this );
	}
	
	/*
	 * Register variable into super global.
	 *
	 * @access Private
	 *
	 * @return Void
	 */
	private function register(): Void
	{
		$this->vars->map( fn( $i, $env, $value ) => $_ENV[$env] = $value->value Instanceof Support\Data\DataInterface ? $value->value->__toArray() : $value->value );
	}
	
	/*
	 * Match environment values.
	 *
	 * @access Private
	 *
	 * @params String $value
	 *
	 * @return Array|False
	 */
	private function matchValue( String $value ): Array | False
	{
		return( Support\RegExp\RegExp::match( "/^(?:(?<value>(?<int>[\d]+)|(?<bool>True|False)|(?<array>\{.*?\}|\[.*?\])|(?<string>\"(?<string>.*?)\"|\'(?<string>.*?)\'|(?<string>[^\n]+))))$/iJ", $value, True ) );
	}
	
	/*
	 * Get value name type.
	 *
	 * @access Private
	 *
	 * @params Array $match
	 *
	 * @return String
	 */
	private function type( Array $match ): String
	{
		return( match( True )
		{
			// If value type is Int.
			isset( $match['int'] ) => "Int",
			
			// If value type is Bool.
			isset( $match['bool'] ) => "Bool",
			
			// If value type is Array.
			isset( $match['array'] ) => "Array",
			
			// If value type is String.
			isset( $match['string'] ) => "String",
			
			default => "Null"
		});
	}
	
	/*
	 * Get converted value.
	 *
	 * @access Private
	 *
	 * @params String $type
	 * @params Array $match
	 *
	 * @return Mixed
	 */
	private function value( String $type, Array $match ): Mixed
	{
		return( match( $type )
		{
			// If value type is Int.
			"Int" => ( Int ) $match['value'],
			
			// If value type is Bool.
			"Bool" => strtoupper( $match['value'] ) === "TRUE",
			
			// If value type is Null.
			"Null" => Null,
			
			// If value type is Array.
			"Array" => Util\JSON::decode( $match['value'], True ),
			
			// If value type is String.
			"String" => $match['string'],
		});
	}
	
}

?>