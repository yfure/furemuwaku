<?php

namespace Yume\Fure\Util\Env;

use Generator;

/*
 * EnvParserInterface
 *
 * @package Yume\Fure\Util\Env
 */
interface EnvParserInterface {
	
	/*
	 * Find line number by current raw matched.
	 *
	 * @access Public
	 *
	 * @params String $raw
	 *
	 * @return Int
	 */
	public function findLine( ? String $raw = Null ): Int;
	
	/*
	 * Return if raw contents is single line comment.
	 *
	 * @access Public
	 *
	 * @params String $comment
	 * @params Bool $optional
	 * @params Mixed &$matches
	 *
	 * @return Bool
	 */
	public function isComment( String $comment, ? Bool $optional = Null, Mixed &$matches = [] ): Bool;
	
	/*
	 * Parse environment contents.
	 *
	 * @access Public
	 *
	 * @return Generator
	 *  Generator result of process.
	 */
	public function parse(): Generator;
	
	/*
	 * Set parser contents.
	 *
	 * @access Public
	 *
	 * @params String $contents
	 *
	 * @return Yume\Fure\Util\Env\EnvParserInterface
	 */
	public function setContents( String $contents ): EnvParserInterface;
	
}

?>