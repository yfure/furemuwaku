<?php

namespace Yume\Fure\Util\Env;

use Yume\Fure\Error;
use Yume\Fure\Support\Design;
use Yume\Fure\Support\File;
use Yume\Fure\Support\Reflect;

/*
 * Env
 *
 * @package Yume\Fure\Util\Env
 *
 * @extends Yume\Fure\Support\Design\Singleton
 */
class Env extends Design\Singleton
{
	
	/*
	 * Environment Parser Instance
	 *
	 * @access Private Readonly
	 *
	 * @values Yume\Fure\Util\Env\EnvParser
	 */
	private Readonly EnvParser $parser;
	
	/*
	 * Environment Parsed Vars
	 *
	 * @access Private Readonly
	 *
	 * @values Yume\Fure\Util\Env\EnvVars<String:Yume\Fure\Util\Env\EnvVar>
	 */
	private Readonly EnvVariables $vars;
	
	/*
	 * @inherit Yume\Fure\Support\Design\Singleton
	 *
	 */
	protected function __construct()
	{
		// Create new Environment Parser instance.
		$this->parser = new EnvParser( ".env" );
		$this->parser->parse();
		
		// Get all parsed Environment Variables.
		$this->vars = $this->parser->getVars();
	}
	
	/*
	 * Re-writer environment file.
	 *
	 * @access Protected
	 *
	 * @return Void
	 */
	protected function rewrite(): Void
	{
		// Environment instance.
		$env = self::self();
		
		// Get all environment variables.
		$vars = $env->vars;
		
		// Raws
		$raws = $this->parser->read();
		
		// Mapping environment variables.
		$vars->map( function( Int $i, String $name, EnvVariable $var ) use( &$raws, $vars )
		{
			// Check if variable is unset.
			if( $var->isUnset() )
			{
				// Just replace old raw with blank value.
				$raws = str_replace( $var->getRaw(), "", $raws );
				$vars->__unset( $var->getName() );
			}
			
			// Check if variable has raw.
			else if( $var->hasRaw() )
			{
				// Just replace old raw with converted variable.
				$raws = str_replace( $var->getRaw(), $var->convert(), $raws );
			}
			else {
				$raws .= "\n";
				$raws .= $var->convert();
				
				// Set variable raw.
				Reflect\ReflectProperty::setValue( $var, "raw", $var->convert() );
			}
		});
		
		// Save file.
		File\File::write( $env->parser->getFile(), $raws );
	}
	
	/*
	 * Get env values.
	 *
	 * @access Public Static
	 *
	 * @params String $env
	 * @params Mixed $optional
	 *
	 * @return Mixed
	 *
	 * @throws Yume\Fure\Error\KeyError
	 * @throws Yume\Fure\Util\Env\EnvError
	 */
	public static function get( String $name, Mixed $optional = Null ): Mixed
	{
		// Environment instance.
		$env = self::self();
		
		// Check if environment variable has defined.
		if( isset( $env->vars[$name] ) )
		{
			// Check if environment variable is not commented.
			if( $env->vars[$name]->hasCommented() === False )
			{
				// Check if environment variable has values.
				if( $env->vars[$name]->hasValue() )
				{
					return( $env->vars[$name] )->getValue();
				}
				return( $optional );
			}
		}
		throw new EnvError( $name, Null, 0, EnvError::REFERENCE_ERROR, new Error\KeyError( $name ) );
	}
	
	/*
	 * Check if environment variable is set.
	 *
	 * @access Public Static
	 *
	 * @params String $name
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public static function isset( String $name, ? Bool $optional = Null ): Bool
	{
		// Check if environment variable is exists & not commented.
		$isset = isset( self::self()->vars[$name] ) && self::self()->vars[$name]->hasCommented() === False;
		
		// Return isset by optional.
		return( $optional !== Null ? $optional === $isset : $isset );
	}
	
	/*
	 * Set new environment variable or just update.
	 *
	 * @access Public Static
	 *
	 * @params String $name
	 * @params Array|Bool|Int|String $value
	 * @params Yume\Fure\Util\Env\EnvTypes $type
	 * @params String $comment
	 * @params Bool $commented
	 * @params Bool $quoted
	 *
	 * @return Void
	 */
	public static function set( String $name, Array | Bool | Int | Null | String $value = Null, ? EnvTypes $type = Null, False | Null | String $comment = False, Array | False | Null $comments = False, Bool $commented = False, Bool $quoted = False ): Void
	{
		// Environment instance.
		$env = self::self();
		
		// Check if environment has defined.
		if( isset( $env->vars[$name] ) )
		{
			$env->vars[$name]->setComment( $comment !== False ? $comment : $env->vars[$name]->getComment() );
			$env->vars[$name]->setMultipleComments( $comments !== False ? $comments : $env->vars[$name]->getMultipleComments() );
			$env->vars[$name]->setCommented( $commented );
			$env->vars[$name]->setValue( $value );
		}
		else {
			
			// Set new variable.
			$env->vars[$name] = new EnvVariable( $name, $value, $type ?? EnvTypes::NONE, $comment !== False ? $comment : Null, $comments !== False ? $comments : Null, $commented, $quoted );
		}
		
		// Rewrite environment file.
		$env->rewrite();
	}
	
	/*
	 * Unset environment variable.
	 *
	 * @access Public Static
	 *
	 * @params String $name
	 *
	 * @return Void
	 */
	public static function unset( String $name ): Void
	{
		// Check if environment has defined.
		if( isset( self::self()->vars[$name] ) )
		{
			// Unset environment.
			self::self()->vars[$name]->setAsUnset();
			
			// Rewrite environment file.
			self::self()->rewrite();
		}
	}
	
}

?>