<?php

namespace Yume\Fure\Error\Handler\Sutakku;

use Throwable;

/*
 * SutakkuInterface
 *
 * @package Yume\Fure\Error\Handler\Sutakku
 */
interface SutakkuInterface
{
	
	/*
	 * Build stack trace.
	 *
	 * @access Public
	 *
	 * @return Yume\Fure\Error\Handler\Sutakku\SutakkuInterface
	 */
	public function build(): SutakkuInterface;
	
	/*
	 * Get previous exceptions.
	 *
	 * @access Public
	 *
	 * @return Array
	 */
	public function getPrevious(): Array;
	
	/*
	 * Get stack trace scheme.
	 *
	 * @access Public
	 *
	 * @return Array
	 */
	public function getScheme(): Array;
	
	/*
	 * Get exception thrown.
	 *
	 * @access Public
	 *
	 * @return Throwable
	 */
	public function getThrown(): Throwable;
	
	/*
	 * Get stack trace.
	 *
	 * @access Public
	 *
	 * @return Array
	 */
	public function getTrace(): Array;
	
}
		
?>