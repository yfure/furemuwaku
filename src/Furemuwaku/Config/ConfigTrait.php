<?php

namespace Yume\Fure\Config;

use Yume\Fure\Main;

/*
 * ConfigTrait
 *
 * @package Yume\Fure\Config
 */
trait ConfigTrait {
	
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
	final public static function config( Callable | Null | String $name = Null ): Mixed {
		if( self::$configs Instanceof Config === False ) {
			$split = explode( "\\", __CLASS__ );
			$class = end( $split );
			self::$configs = Main\Main::config( $class );
		}
		if( valueIsNotEmpty( $name ) ) {
			if( is_string( $name ) ) {
				return( ify( $name, self::$configs ) );
			}
			return( call_user_func( $name, self::$configs ) );
		}
		return( self::$configs );
	}
	
}

?>