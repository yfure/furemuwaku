<?php

namespace Yume\Fure\Support\Config;

use Yume\Fure\App;

/*
 * ConfigTrait
 *
 * @package Yume\Fure\Support\Config
 */
trait ConfigTrait
{
	
	/*
	 * Class Configuration.
	 *
	 * @access Protected Static
	 *
	 * @values Yume\Fure\Config\Config
	 */
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
	 * @params Callable|String $name
	 *
	 * @return Mixed
	 */
	final public static function config( Callable | Null | String $name = Null ): Mixed
	{
		// If the class configuration has not been imported.
		if( self::$configs Instanceof Config === False )
		{
			// Split class name with backslash character.
			$split = explode( "\\", __CLASS__ );
			
			// Get class name.
			$class = end( $split );
			
			// Get class configuration.
			self::$configs = App\App::config( $class );
		}
		
		// Check if `name` is not Empty value.
		if( valueIsNotEmpty( $name ) )
		{
			// If name is String type.
			if( is_string( $name ) ) return( ify( $name, self::$configs ) );
			
			// Get return value from callback.
			return( call_user_func( $name, self::$configs ) );
		}
		return( self::$configs );
	}
	
}

?>