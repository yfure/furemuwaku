<?php

namespace Yume\Fure\Support\Path;

/*
 * PathName
 *
 * @package Yume\Fure\Support\Path
 */
enum PathName: String
{
	
	/*
	 * List of default paths for applications.
	 *
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
	
	/*
	 * List of default paths for assets.
	 *
	 */
	case ASSET = "assets";
	case ASSET_CACHE = "assets/caches";
	//case ASSET_CACHE_ROUTING = "assets/caches/routing";
	case ASSET_CACHE_VIEW = "assets/caches/views";
	case ASSET_COOKIE = "assets/cookies";
	case ASSET_COOKIE_SESSION = "assets/cookies/session";
	case ASSET_LOGGING = "assets/logging";
	case ASSET_SCRIPT = "assets/scripts";
	case ASSET_STYLE = "assets/styles";
	case ASSET_VIEW = "assets/views";
	case ASSET_VIEW_COMPONENT = "assets/views/components";
	case ASSET_VIEW_TEMPLATE = "assets/views/templates";
	
	/*
	 * List of default paths for system.
	 *
	 */
	case SYSTEM = "system/";
	case SYSTEM_BOOTING = "system/booting";
	case SYSTEM_CONFIG = "system/configs";
	case SYSTEM_ROUTES = "system/routes";
	
	/*
	 * List of default paths for vendor.
	 *
	 */
	case VENDOR = "vendor";
	case VENDOR_FUREMU = "vendor/yfure/framework/src/Furemuwaku/";
	case VENDOR_FUREMU_HELPER = "vendor/yfure/framework/src/Furemuwaku/Helpers";
	case VENDOR_YFURE = "vendor/yfure";
	
}

?>