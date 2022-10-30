<?php

namespace Yume\Fure\App\Config;

use Yume\Fure\Support;

/*
 * ConfigTrait
 *
 * @package Yume\Fure\App\Config
 */
trait ConfigTrait
{
	
	protected static ? Config $configs = Null;
	
	/*
	 * Take one configuration value or take all
	 * configuration values, the configuration in
	 * question is configuration by class name.
	 *
	 * @class Yume\Fure\App\App
	 * @usage Yume\Fure\App\App::config( test.case )
	 *
	 * @access Public Static
	 *
	 * @params String $name
	 *
	 * @return Mixed
	 */
	public static function config( ? String $name = Null ): Mixed
	{
		// If the class configuration has not been imported.
		if( self::$configs Instanceof Config === False )
		{
			// Split class name with backslash character.
			$split = explode( "\\", __CLASS__ );
			
			// Get class name.
			$class = end( $split );
			
			// Get class configuration.
			self::$configs = Support\Services\Services::app()->config( $class );
		}
		
		// Check if `name` is not null type.
		if( $name !== Null )
		{
			return( ify( $name, self::$configs ) );
		}
		return( self::$configs );
	}
	
}

?>