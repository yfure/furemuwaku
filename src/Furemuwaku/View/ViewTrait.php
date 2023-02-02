<?php

namespace Yume\Fure\View;

use Yume\Fure\Support\File;
use Yume\Fure\Util;

/*
 * ViewTrait
 *
 * @package Yume\Fure\View
 */
trait ViewTrait
{
	
	/*
	 * Return if views is exists.
	 *
	 * @access Public Static
	 *
	 * @params String $view
	 *
	 * @return Bool
	 */
	public static function exists( String $view ): Bool
	{
		return( File\File::exists( self::path( $view ) ) );
	}
	
	/*
	 * Return views path name.
	 *
	 * @access Public Static
	 *
	 * @params String $view
	 *
	 * @return String
	 */
	public static function path( String $view ): String
	{
		return(
			Util\Str::fmt( "{p}/{v}.{e}.php", 
				
				// View filename.
				v: $view,
				
				// Get views pathname.
				p: self::config( "path" ),
				
				// Get views extension name.
				e: self::config( "extension" )
			)
		);
	}
	
}

?>