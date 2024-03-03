<?php

namespace Yume\Fure\Util\Env;

use Stringable;

use Yume\Fure\Util;

/*
 * EnvVariableInterface
 *
 * @extends Stringable
 *
 * @package Yume\Fure\Util\Env
 */
interface EnvVariableInterface extends Stringable {
	
	/*
	 * Return the single comment after env definition.
	 * 
	 * @access Public
	 * 
	 * @return String
	 */
	public function getComment(): ? String;
	
	/*
	 * Return multiline comments before define variable.
	 *
	 * @access Public
	 *
	 * @return Array
	 */
	public function getComments(): ? Array;
	
	/*
	 * Return end of line after env closing definition.
	 * 
	 * @access Public
	 * 
	 * @return String
	 */
	public function getEOL(): ? String;
	
	/*
	 * Return line number of env definition.
	 * 
	 * @access Public
	 * 
	 * @return Int
	 */
	public function getLine(): ? Int;
	
	/*
	 * Return quote character of variable, e.g single quote ('), double quote (").
	 * 
	 * @access Public
	 * 
	 * @return String
	 */
	public function getQuote(): ? String;
	
	/*
	 * Return variable as raw.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getRaw(): ? String;
	
	/*
	 * Return variable type.
	 *
	 * @access Public
	 *
	 * @return Yume\Fure\Util\Type
	 */
	public function getType(): Util\Type;
	
	/*
	 * Return variable type as String.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getTypeAsString(): String;
	
	/*
	 * Return value of variable.
	 *
	 * @access Public
	 *
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public function getValue(): Mixed;
	
	/*
	 * Return if variable has single line comment.
	 *
	 * @access Public
	 *
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public function hasComment( ? Bool $optional = Null ): Bool;
	
	/*
	 * Return if variable has multiline comments.
	 *
	 * @access Public
	 *
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public function hasComments( ? Bool $optional = Null ): Bool;
	
	/*
	 * Return whether env has content after end of line definition.
	 * 
	 * @access Public
	 * 
	 * @params Bool $optional
	 * 
	 * @return Bool
	 */
	public function hasEOL( ? Bool $optional = Null ): Bool;
	
	/*
	 * Return whether the env has line number.
	 * 
	 * * @access Public
	 * 
	 * @params Bool $optional
	 * 
	 * @return Bool
	 */
	public function hasLine( ? Bool $optional = Null ): Bool;
	
	/*
	 * Return whether the env has raw syntax.
	 * 
	 * * @access Public
	 * 
	 * @params Bool $optional
	 * 
	 * @return Bool
	 */
	public function hasRaw( ? Bool $optional = Null ): Bool;
	
	/*
	 * Return whether the env has value.
	 * 
	 * * @access Public
	 * 
	 * @params Bool $optional
	 * 
	 * @return Bool
	 */
	public function hasValue( ? Bool $optional = Null ): Bool;
	
	/*
	 * Return if variable is commented.
	 *
	 * @access Public
	 *
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public function isCommented( ? Bool $optional = Null ): Bool;
	
	/*
	 * Return if value of variable is quoted.
	 *
	 * @access Public
	 *
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public function isQuoted( ? Bool $optional = Null ): Bool;
	
	/*
	 * Return if variable is declared by system.
	 *
	 * @access Public
	 *
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public function isSystem( ? Bool $optional = Null ): Bool;
	
	/*
	 * Returns a raw variable declaration.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function makeRaw(): String;
	
	/*
	 * Set variable value.
	 *
	 * @access Public
	 *
	 * @params Mixed $value
	 *
	 * @return Static
	 */
	public function setValue( Mixed $value ): Static;
	
}

?>