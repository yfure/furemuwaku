<?php

namespace Yume\Fure\Util\Reflect;

use ReflectionEnum;
use ReflectionEnumUnitCase;
use UnitEnum;

/*
 * ReflectEnumUnit
 *
 * @package Yume\Fure\Util\Reflect
 * 
 * @extends Yume\Fure\Util\Reflect\ReflectConstant
 */
abstract class ReflectEnumUnit extends ReflectConstant
{
	
	/*
	 * Gets the reflection of the enum of this case.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $constant
	 * @params Mixed &$reflect
	 *
	 * @return ReflectionEnum
	 */
	public static function getEnum( Object | String $class, String $constant, Mixed &$reflect = Null ): ReflectionEnum
	{
		return( $reflect = self::reflect( $class, $constant, $reflect ) )->getEnum();
	}
	
	/*
	 * Gets the enum case object described by this reflection object.
	 *
	 * @access Public Static
	 *
	 * @params Object|String $class
	 * @params String $constant
	 * @params Mixed &$reflect
	 *
	 * @return UnitEnum
	 */
	public static function getValue( Object | String $class, String $constant, Mixed &$reflect = Null ): UnitEnum
	{
		return( $reflect = self::reflect( $class, $constant, $reflect ) )->getValue();
	}
	
	/*
	 * Create ReflectionEnumUnitCase instance.
	 *
	 * @access Private Static
	 *
	 * @params Object|String $class
	 * @params String $constant
	 * @params Mixed $reflect
	 *
	 * @return ReflectionEnumUnitCase
	 */
	private static function reflect( Object | String $class, String $constant, Mixed $reflect ): ReflectionEnumUnitCase
	{
		if( $reflect Instanceof ReflectionEnumUnitCase )
		{
			if( $reflect->name === $constant &&
				$reflect->class === is_object( $class ) ? $class::class : $class )
			{
				return( $reflect );
			}
		}
		return( new ReflectionEnumUnitCase( $class, $constant ) );
	}
	
}

?>