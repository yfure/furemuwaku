<?php

namespace Yume\Fure\Util\Reflect;

use Fiber;
use Generator;

/*
 * ReflectDecorator
 *
 * @package Yume\Fure\Util\Reflect
 */
trait ReflectDecorator
{
	
	/*
	 * Get the file name of the current execution point.
	 *
	 * @access Public Static
	 *
	 * @params Fiber|Generator $target
	 * @params Mixed &$reflect
	 *
	 * @return String
	 */
	public static function getExecutingFile( Fiber | Generator $target, Mixed &$reflect = Null ): String
	{
		return( $reflect = self::reflect( $target, $reflect ) )->getExecutingFile();
	}
	
	/*
	 * Get the line number of the current execution point.
	 *
	 * @access Public Static
	 *
	 * @params Fiber|Generator $target
	 * @params Mixed &$reflect
	 *
	 * @return Int
	 */
	public static function getExecutingLine( Fiber | Generator $target, Mixed &$reflect = Null ): Int
	{
		return( $reflect = self::reflect( $target, $reflect ) )->getExecutingLine();
	}
	
	/*
	 * Get the backtrace of the current execution point.
	 *
	 * @access Public Static
	 *
	 * @params Fiber|Generator $target
	 * @params Int $options
	 * @params Mixed &$reflect
	 *
	 * @return Array
	 */
	public static function getTrace( Fiber | Generator $target, Int $options = DEBUG_BACKTRACE_PROVIDE_OBJECT, Mixed &$reflect = Null ): Array
	{
		return( $reflect = self::reflect( $target, $reflect ) )->getTrace( $options );
	}
	
}

?>