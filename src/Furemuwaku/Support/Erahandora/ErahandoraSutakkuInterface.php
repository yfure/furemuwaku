<?php

namespace Yume\Fure\Support\Erahandora;

use Throwable;

/*
 * ErahandoraSutakkuInterface (Error Handler Stack Interface)
 *
 * @package Yume\Fure\Support\Erahandora
 */
interface ErahandoraSutakkuInterface
{
	
	/*
	 * Build stack trace.
	 *
	 * @access Public
	 *
	 * @return Yume\Fure\Support\Erahandora\ErahandoraSutakkuInterface
	 */
	public function build(): ErahandoraSutakkuInterface;
	
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