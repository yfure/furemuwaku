<?php

namespace Yume\Fure\Util\Env;

use Generator;

use Yume\Fure\IO\File;
use Yume\Fure\Support;
use Yume\Fure\Util;

/*
 * Env
 *
 * @extends Yume\Fure\Support\Singleton
 *
 * @package Yume\Fure\Util\Env
 */
class Env extends Support\Singleton {
	
	/*
	 * Default source stored environment file.
	 *
	 * @access Public Static
	 *
	 * @values String
	 */
	public const DEFAULT = "env";

	/*
	 * Instance of class EnvParser.
	 * 
	 * @access Protected Readonly
	 * 
	 * @values Yume\Fure\Util\Env\EnvParser
	 */
	protected Readonly EnvParser $parser;
	
	/*
	 * Source stored environment file.
	 *
	 * @access Public Readonly
	 *
	 * @values String
	 */
	public Readonly String $source;
	
	/*
	 * All defined environment variable.
	 *
	 * @access Protected
	 *
	 * @values Array<Yume\Fure\Util\Env\EnvVariable>
	 */
	protected Array $vars = [];
	
	/*
	 * @inherit Yume\Fure\Support\Singleton::__construct
	 *
	 * @throws Yume\Fure\IO\File\FileNotFoundError
	 *  Throw if environment file does not exists.
	 */
	protected function __construct( ? String $source = Null, public Readonly Bool $override = True ) {
		Util\Arrays::map( $_ENV ?? [], fn( Int | String $i, String $name, Mixed $value ) => $this->vars[$name] = new EnvVariable( $name, $value, system: True ) );
		if( File\File::exists( $this->source = $source ??= self::DEFAULT, False ) ) {
			throw new File\FileNotFoundError( $this->source );
		}
		$this->parser = new EnvParser();
		$this->parser->setContents(
			File\File::read( $this->source )
		);
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
	 * @throws Yume\Fure\Util\Env\EnvError
	 *  Throw if environtment variable does not exists
	 */
	public static function get( String $name, Mixed $optional = Null ): Mixed {
		if( self::has( $name ) ) {
			return( self::self() )->vars[$name]->getValue();
		}
		if( $optional !== Null ) {
			return( $optional );
		}
		throw new EnvError( $name, EnvError::NAME_ERROR );
	}
	
	/*
	 * Get all defined environments.
	 *
	 * @access Public Static
	 *
	 * @return Array
	 */
	public static function getAll(): Array {
		return( self::self() )->vars;
	}
	
	/*
	 * Return if variable set and not commented.
	 *
	 * @access Public Static
	 *
	 * @params String $name
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public static function has( String $name, ? Bool $optional = Null ): Bool {
		return( $optional !== Null ? self::has( $name ) === $optional : isset( self::self()->vars[$name] ) && self::self()->vars[$name]->isCommented( False ) );
	}
	
	/*
	 *
	 * @throws Yume\Fure\util\Env\EnvError
	 *  If override is not allowed.
	 * 
	 */
	public function parse(): Void {
		foreach( $this->parser->parse() As $i => $var ) {
			if( isset( $this->vars[$var->name] ) ) {
				if( $this->vars[$var->name]->isSystem( False ) &&
					$this->vars[$var->name]->isCommented( False ) ) {
					if( $var->isCommented() ) continue;
					if( $this->override === False ) {
						throw new EnvError( [ $var->name, $var->getLine() ], EnvError::OVERRIDE_ERROR, Null, $this->source, $var->getLine() );
					}
				}
			}
			$this->vars[$var->name] = $var;
		}
	}
	
}

?>