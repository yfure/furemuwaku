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
	 * A collection of classes to bind to parameters.
	 *
	 * @access Static Private
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	static private Support\Data\DataInterface $binding;
	
	/*
	 * A collection of configurations that have been loaded.
	 *
	 * @access Static Private
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	static private Support\Data\DataInterface $configs;
	
	/*
	 * Service class instance collection.
	 *
	 * @access Static Private
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	static private Support\Data\DataInterface $services;
	
	/*
	 * @inherit Yume\Fure\Support\Design\Creational\Singleton
	 *
	 */
	protected function __construct()
	{
		// New Data instance.
		static::$binding = new Support\Data\Data;
		static::$configs = new Support\Data\Data;
		
		// Mapping services class.
		static::$services = self::config( "services", True )->map( function( Object | String $service )
		{
			// ReflectionClass instance.
			$reflect = Null;
			
			// Check if service provider class is not implement ServiceProviderInterface.
			if( Support\Reflect\ReflectClass::isImplements( $service, ServicesProviderInterface::class, $reflect ) === False )
			{
				throw new Error\InterfaceError(
					code: Error\InterfaceError::IMPLEMENTS_ERROR,
					message: [
						$service,
						ServicesProviderInterface::class
					]
				);
			}
			else {
				
				$reflect
				
				// Get method reflection class.
				->getMethod( "boot" )
				
				// Invoke service bootstrap.
				->invoke( $service = $reflect->newInstance(), "boot" );
			}
			return( $service );
		});
	}
	
	public static function binding( String $name ): ? Object
	{
		
	}
	
	/*
	 * Return or import configuration.
	 *
	 * @access Public Static
	 *
	 * @params String $name
	 * @params Bool $import
	 *
	 * @return Mixed
	 */
	public static function config( String $name, Bool $import = False ): Mixed
	{
		// Check if config is not empty.
		if( valueIsNotEmpty( $name ) )
		{
			// Split config name.
			$split = Util\Arr::ifySplit( $name );
			
			// If the configuration has not been registered or if re-import is allowed.
			if( self::$configs->__isset( $split[0] ) === False || $import )
			{
				self::$configs->__set( $split[0], Support\Package\Package::import( f( "/system/configs/{}", $split[0] ) ) );
			}
		}
		else {
			throw new Error\ArgumentError(
				code: Error\ArgumentError::VALUE_ERROR,
				message: "Configuration name can't be empty."
			);
		}
		return( Util\Arr::ify( $split, self::$configs ) );
	}
	
	public static function response()
	{
		
	}
	
}

?>