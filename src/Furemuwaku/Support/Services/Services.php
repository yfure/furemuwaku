<?php

namespace Yume\Fure\Support\Services;

use Yume\Fure\App;
use Yume\Fure\Error;
use Yume\Fure\Support;
use Yume\Fure\Util;

/*
 * Services
 *
 * @extends Yume\Fure\Support\Design\Creational\Singleton
 *
 * @package Yume\Fure\Support\Services
 */
class Services extends Support\Design\Creational\Singleton
{
	
	/*
	 * Application instance.
	 *
	 * @access Static Private
	 *
	 * @values Yume\Fure\App\App
	 */
	static private ? App\App $app = Null;
	
	/*
	 * @inherit Yume\Fure\Support\Design\Creational\Singleton
	 *
	 */
	protected function __construct( App\App $app )
	{
		// Check if application has instanced.
		if( static::$app Instanceof App\App )
		{
			throw new Error\LogicError( f( "Can't duplicate {} instance!", App\App::class ) );
		}
		
		// Set application instance.
		static::$app = $app;
	}
	
	/*
	 * Get application instance.
	 *
	 * @access Public Static
	 *
	 * @return Yume\Fure\App\App
	 */
	public static function app(): ? App\App
	{
		return( static::$app );
	}
	
}

?>