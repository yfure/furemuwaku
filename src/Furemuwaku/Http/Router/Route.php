<?php

namespace Yume\Fure\Http\Router;

use Yume\Fure\Error;
use Yume\Fure\Http\Request;
use Yume\Fure\Main;
use Yume\Fure\Support;
use Yume\Fure\Util\Arr;
use Yume\Fure\Util\RegExp;

/*
 * Route
 * 
 * @package Yume\Fure\Http\Router
 */
class Route extends Support\Singleton {

	/*
	 * Instance of class Pattern.
	 * 
	 * @access Protected Readonly
	 * 
	 * @values Yume\Fure\Util\RegExp\Pattern
	 */
	protected Readonly RegExp\Pattern $pattern;
	protected Readonly Request\Request $request;

	/*
	 * @inherit Yume\Fure\Support\Singleton::__construct
	 * 
	 */
	protected function __construct( Request\Request $request ) {
		$this->pattern = new RegExp\Pattern( "(?:(?<Segment>\:(?<SegmentName>([a-z]+))(\((?<SegmentRegExp>[^\)]*)\))*))" );
		$this->request = $request;
	}

	public static function __callStatic( String $method, Mixed ...$args ): RouterInterface {
		if( Request\RequestMethod::tryFrom( strtoupper( $method ) ) ) {
			return self::request( $method, ...$args );
		}
		throw new Error\MethodError( $method, Error\MethodError::NAME_ERROR );
	}

	public static function exists( String $pathname, ?RouterInterface $parent = Null ): Bool|RouterInterface {
		return False;
	}

	public static function pathname( String $pathname ): String {
	}

	/*
	 * Register Http Request Route.
	 * 
	 * @access Public Static
	 * 
	 * @params Array|String $method
	 *  Array or String of allowed request methods
	 * @params String $path
	 *  String of pathname, the Regular Expression is allowed
	 * @params Array|String|Callable $handler
	 *  The handle for request
	 * @params Callable $children
	 *  The children requests
	 * 
	 * @return Yume\Fure\Http\Router\RouterInterface
	 */
	public static function request( Array|String $method, String $path, Array|String|Callable $handler, ?Callable $children = Null ): RouterInterface {
		if( self::exists( $path ) ) {
			throw new RouterError( self::pathname( $path ), RouteError::DUPLICATE_PATH );
		}
		if( is_callable( $children ) ) {

			$global = Route::self()->routes;
			$parent = Route::self()->parent;

			// Change current routing scope.
			$childs = new Routes;
			Route::self()->routes = $childs;
			Route::self()->parent = self::pathname( $path );

			// Issolate callback route children function.
			isolation( $children, Route::self()->request );

			// Return previous global routes.
			Route::self()->routes = $global;
			Route::self()->parent = $parent;

			return Route::self()->routes[] = new Route( $method, $path, $handler, $childs );
		}
		return Route::self()->routes[] = new Route( $method, $path, $handler );
	}


}

?>