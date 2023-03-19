<?php

/*
 * Yume Config Helpers.
 *
 * @include config
 */

use Yume\Fure\App;

/*
 * @inherit Yume\Fure\App\App::config
 *
 */
function config( String $name, Bool $import = False ): Mixed
{
	return( App\App::config( $name, $import ) );
}

?>