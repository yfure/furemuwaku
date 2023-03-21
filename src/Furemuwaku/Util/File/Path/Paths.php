<?php

namespace Yume\Fure\Util\File\Path;

/*
 * PathName
 *
 * @package Yume\Fure\Util\File\Path
 */
enum Paths: String
{
	
	/*
	 * List of default paths for applications.
	 *
	 * @include App
	 * @include App Controllers
	 * @include App Helpers
	 * @include App Lang
	 * @include App Models
	 * @include App Providers
	 * @include App Tests
	 * @include App Views
	 */
	case AppController = "app/Http/Controllers";
	case AppHelper = "app/Helpers";
	case AppLang = "app/Lang";
	case AppModel = "app/Models";
	case AppProvider = "app/Providers";
	case AppTest = "app/Tests";
	case AppViewComponent = "app/Views/Components";
	case AppViewTemplate = "app/Views/Templates";
	case AppView = "app/Views";
	case App = "app";
	
	// List of default paths for storage.
	case StorageCacheView = "storage/caches/views";
	case StorageCache = "storage/caches";
	case StorageCookieSession = "storage/cookies/session";
	case StorageCookie = "storage/cookies";
	case StorageLogging = "storage/logging";
	case StorageViewComponent = "storage/views/components";
	case StorageViewTemplate = "storage/views/templates";
	case StorageView = "storage/views";
	case Storage = "storage";
	
	/*
	 * Front controller path.
	 *
	 * @include Public
	 */
	case Public = "public";
	
	/*
	 * List of default paths for system.
	 *
	 * @include System
	 * @include System Booting
	 * @include System Configs
	 * @include System Routes
	 */
	case SystemBooting = "system/booting";
	case SystemConfig = "system/configs";
	case SystemRoutes = "system/routes";
	case System = "system/";
	
	/*
	 * List of default paths for vendor.
	 *
	 * @include Vendor
	 * @include Vendor Composer
	 * @include Vendor Furemuwaku
	 * @include Vendor Furemuwaku/Helper
	 * @include Vendor YFure
	 */
	case VendorInstalled = "vendor/composer/installed.json";
	case VendorFuremuHelper = "vendor/yfure/framework/src/Furemuwaku/Support/Helpers";
	case VendorFuremu = "vendor/yfure/framework/src/Furemuwaku";
	case VendorYFure = "vendor/yfure";
	case Vendor = "vendor";
	
	use \Yume\Fure\Util\Enum\EnumDecoratorTrait;
	
	/*
	 * ...
	 *
	 * @access Public
	 *
	 * @params String $path
	 *
	 * @return Bool
	 */
	public function is( String $path ): Bool
	{
		$paths = [
			preg_replace( "/\//", DIRECTORY_SEPARATOR, $this->value ),
			preg_replace( "/\//", DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR . $this->value )
		];
		foreach( $paths As $prefix )
		{
			if( strpos( $path, $prefix ) === 0 )
			{
				return( True );
			}
		}
		return( False );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Path\Path::path
	 *
	 */
	public function path( ? String $path = Null ): String
	{
		if( $path )
		{
			return( Path::path( sprintf( "%s/%s", $this->value, $path ) ) );
		}
		return( Path::path( $this->value ) );
	}
	
}

?>