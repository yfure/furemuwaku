<?php

namespace Yume\Fure\Support\Env;

use Yume\Fure\Error;
use Yume\Fure\IO;
use Yume\Fure\Support;

/*
 * Env
 *
 * @extends Yume\Fure\Support\Design\Creational\Singleton
 *
 * @package Yume\Fure\Support\Env
 */
class Env extends Support\Design\Creational\Singleton
{
	
	/*
	 * Environment file name.
	 *
	 * @access Private
	 *
	 * @values String
	 */
	private String $filename = "env";
	
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
		$read = IO\File\File::read( $this->filename );
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
	
}

?>