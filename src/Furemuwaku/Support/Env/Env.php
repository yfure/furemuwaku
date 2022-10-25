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
		if( IO\File\File::exists( $this->filename ) === False )
		{
			throw new Error\EnvError( $this->filename, Env\EnvError::FILE_ERROR );
		}
		$this->vars = new Support\Data\Data([
			"system" => $_ENV,
			"define" => []
		]);
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
		$this->parse( IO\File\File::readline( $this->filename ) );
	}
	
	public function setValue( String $env, Array | Bool | Int | Null | String $value = Null ): Static
	{
		if( $this->vars->__isset( $env ) )
		{
			
		}
		else {
			throw new Error\EnvError( $env, Error\EnvError::DEFINE_ERROR );
		}
		return( $this );
	}
	
	private function parse( Array $fline ): Static
	{
		$regexp = implode( "", [
			"/^(?:(?<line>",
				"[\s\t]*",
					"(?<doccomment>",
						"(?<symbol>\#)",
							"[\s\t]*",
						"(?<description>",
							"[^\n]*",
						")",
					")",
						"|",
					"(?<variable>",
						"(?<name>[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)",
							"[\s\t]*",
								"\=",
							"[\s\t]*",
						"(?<value>[^\n]*)",
					")",
			"))$/J"
		]);
		
		return( $this )->compile(
			Util\Arr::map( $fline, function( $i, $index, $value ) use( $regexp )
			{
				// Check if is not error.
				if( $match = Support\RegExp\RegExp::match( $regexp, $value, True ) )
				{
					return( $match );
				}
				if( valueIsNotEmpty( $value ) )
				{
					throw new Error\EnvError( 
						code: Error\EnvError::TOKEN_ERROR,
						message: [
							"message" => $value,
							"line" => $i++,
							"file" => $this->filename
						]
					);
				}
			})
		);
	}
	
	private function compile( Array $parsed ): Static
	{
		puts( "<pre>{}", $parsed );
		
		return( $this )->register(
			Util\Arr::map( $parsed, function( $i, $index, $value )
			{
				// ...
			})
		);
	}
	
	private function register( Array $compiled ): Static
	{
		Util\Arr::map( $compiled, function( $i, $index, $value )
		{
			// ...
		});
		return( $this );
	}
	
}

?>