<?php

namespace Yume\Fure\View\Template;

/*
 * TemplateSyntaxInterface
 *
 * @package Yume\Fure\View\Template\TemplateSyntaxInterface
 */
interface TemplateSyntaxInterface
{
	
	/*
	 * Get syntax-backed tokens.
	 *
	 * @access Public
	 *
	 * @return Array<String>|String
	 */
	public function getToken(): Array | String;
	
	/*
	 * Return if the syntax has a token.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function hasToken(): Bool;
	
	/*
	 * Return if the syntax supports more than one token.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function isMultipleToken(): Bool;
	
	/*
	 * Return if the token matches or is
	 * the same but you don't want to process it.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function isSkip(): Bool;
	
	/*
	 * Return if the token syntax given is supported.
	 * The token must be case insensitive.
	 *
	 * @access Public
	 *
	 * @params String $token
	 *
	 * @return Bool
	 */
	public function isSupportedToken( String $token ): Bool;
	
	/*
	 * Process captured syntax.
	 *
	 * @access Public
	 *
	 * @params Yume\Fure\View\Template\TemplateCaptured $captured
	 *
	 * @return Arraya|String
	 */
	public function process( TemplateCaptured $captured ): Array | String;
	
}

?>