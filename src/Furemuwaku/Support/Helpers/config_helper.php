<?php

/*
 * Yume Config Helpers.
 *
 * ...
 */

use Yume\Fure\App;

if( function_exists( "config" ) === False )
{
	/*
	 * @inherit Yume\Fure\Config\Config
	 *
	 */
	function config( String $name, Bool $import = False ): Mixed
	{
		return( App\App::config( $name, $import ) );
	}
}

?>