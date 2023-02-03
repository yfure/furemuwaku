<?php

namespace Yume\Fure\View\Template;

use Yume\Fure\Util\RegExp;

/*
 * TemplateInterface
 *
 * @package Yume\Fure\View\Template
 */
interface TemplateInterface
{
	
	/*
	 * Get iteration count.
	 *
	 * @access Public
	 *
	 * @return Int
	 */
	public function getIteration(): ? Int;
	
	/*
	 * Get line by coptured content.
	 *
	 * @access Public
	 *
	 * @params String $content
	 *
	 * @return False|Int
	 */
	public function getLine( String $content ): False | Int;
	
	/*
	 * Get pattern for capture syntax.
	 *
	 * @access Public
	 *
	 * @return Yume\Fure\Util\RegExp\Pattern
	 */
	public function getPattern(): RegExp\Pattern;
	
	/*
	 * Get pattern as string for capture syntax.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getPatternAsString(): String;
	
	/*
	 * Get raw template.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getTemplate(): String;
	
	/*
	 * Get template line length.
	 *
	 * @access Public
	 *
	 * @return Int
	 */
	public function getTemplateLength(): Int;
	
	/*
	 * Get template splited with newline.
	 *
	 * @access Public
	 *
	 * @return Array
	 */
	public function getTemplateSplit(): Array;
	
}

?>