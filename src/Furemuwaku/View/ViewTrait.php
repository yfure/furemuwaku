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
	
	use \Yume\Fure\Config\ConfigTrait;
	
	/*
	 * Return if views is exists.
	 *
	 * @access Public Static
	 *
	 * @params String $view
	 * @params Bool $cache
	 *
	 * @return Bool
	 */
	public static function exists( String $view, Bool $cache = False ): Bool
	{
		return( File\File::exists( self::path( $view, $cache ) ) );
	}
	
	/*
	 * Return views path name.
	 *
	 * @access Public Static
	 *
	 * @params String $view
	 * @params Bool $cache
	 *
	 * @return String
	 */
	public static function path( String $view, Bool $cache = False ): String
	{
		$params = [
			
			"view" => $view,
			
			// Get view pathname.
			"path" => self::config( $cache ? "[path.cache]" : "path" ),
			
			// Get views extension name.
			"extension" => self::config( $cache ? "[extension.cache]" : "extension" )
		];
		return( Util\Str::fmt( $cache ? "{path}/{view}.{extension}.php" : "{path}/{view}.{extension}", ...$params ) );
	}
	
}

?>