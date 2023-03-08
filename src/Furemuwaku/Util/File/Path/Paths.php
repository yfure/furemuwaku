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
	case APP = "app";
	case APP_CONTROLLER = "app/Http/Controllers";
	case APP_HELPER = "app/Helpers";
	case APP_LANG = "app/Lang";
	case APP_MODEL = "app/Models";
	case APP_PROVIDER = "app/Providers";
	case APP_TEST = "app/Tests";
	case APP_VIEW = "app/Views";
	case APP_VIEW_COMPONENT = "app/Views/Components";
	case APP_VIEW_TEMPLATE = "app/Views/Templates";
	
	// List of default paths for assets.
	case ASSET = "assets";
	case ASSET_CACHE = "assets/caches";
	case ASSET_CACHE_VIEW = "assets/caches/views";
	case ASSET_COOKIE = "assets/cookies";
	case ASSET_COOKIE_SESSION = "assets/cookies/session";
	case ASSET_LOGGING = "assets/logging";
	case ASSET_VIEW = "assets/views";
	case ASSET_VIEW_COMPONENT = "assets/views/components";
	case ASSET_VIEW_TEMPLATE = "assets/views/templates";
	
	/*
	 * Front controller path.
	 *
	 * @include Public
	 */
	case PUBLIC = "public";
	
	/*
	 * List of default paths for system.
	 *
	 * @include System
	 * @include System Booting
	 * @include System Configs
	 * @include System Routes
	 */
	case SYSTEM = "system/";
	case SYSTEM_BOOTING = "system/booting";
	case SYSTEM_CONFIG = "system/configs";
	case SYSTEM_ROUTES = "system/routes";
	
	/*
	 * List of default paths for vendor.
	 *
	 * @include Vendor
	 * @include Vendor Composer
	 * @include Vendor Furemuwaku
	 * @include Vendor Furemuwaku/Helper
	 * @include Vendor YFure
	 */
	case VENDOR = "vendor";
	case VENDOR_INSTALLED = "vendor/composer/installed.json";
	case VENDOR_FUREMU = "vendor/yfure/framework/src/Furemuwaku/";
	case VENDOR_FUREMU_HELPER = "vendor/yfure/framework/src/Furemuwaku/Helpers";
	case VENDOR_YFURE = "vendor/yfure";
	
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
			if( strpos( $path, $prefix ) !== False )
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