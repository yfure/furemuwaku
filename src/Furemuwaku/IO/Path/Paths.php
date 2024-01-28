<?php

namespace Yume\Fure\IO\Path;

/*
 * Paths
 *
 * @package Yume\Fure\IO\Path
 */
enum Paths: String {
	
	/*
	 * List of default paths for applications.
	 *
	 * @include App
	 * @include App Controllers
	 * @include App Helpers
	 * @include App Language
	 * @include App Models
	 * @include App Providers
	 * @include App Tests
	 * @include App Views
	 */
	case AppController = "app/Http/Controllers";
	case AppHelper = "app/Helpers";
	case AppLanguage = "app/Languages";
	case AppModel = "app/Models";
	case AppProvider = "app/Providers";
	case AppTest = "app/Tests";
	case AppViewComponent = "app/Views/Components";
	case AppView = "app/Views";
	case App = "app";
	
	/*
	 * Front controller path.
	 *
	 * @include Public
	 */
	case Public = "public";
	
	/*
	 * List of default paths for storage.
	 *
	 */
	case StorageCacheView = "storage/caches/views";
	case StorageCache = "storage/caches";
	case StorageCookieSession = "storage/cookies/session";
	case StorageCookie = "storage/cookies";
	case StorageFuremu = "storage/furemu";
	case StorageLogging = "storage/logging";
	case Storage = "storage";
	
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
	
	use \Yume\Fure\Util\Backed;
	
	/*
	 * Return if path has prefix pathname.
	 *
	 * @access Public
	 *
	 * @params String $path
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public function is( String $path, ? Bool $optional = Null ): Bool {
		if( $optional === Null ) {
			$paths = [
				preg_replace( "/\//", DIRECTORY_SEPARATOR, $this->value ),
				preg_replace( "/\//", DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR . $this->value )
			];
			foreach( $paths As $prefix ) {
				if( strpos( $path, $prefix ) === 0 ) return( True );
			}
			return( False );
		}
		return( $this->is( $path ) === $optional );
	}
	
	/*
	 * @inherit Yume\Fure\Util\Path\Path::path
	 *
	 */
	public function path( ? String $path = Null ): String {
		if( $path ) {
			return( Path::path( sprintf( "%s/%s", $this->value, $path ) ) );
		}
		return( Path::path( $this->value ) );
	}
	
}

?>