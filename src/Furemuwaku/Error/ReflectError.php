<?php

namespace Yume\Fure\Error;

use Yume\Fure\Util\Reflect;

/*
 * ReflectError
 *
 * @extends Yume\Fure\Error\YumeError
 *
 * @package Yume\Fure\Error
 */
class ReflectError extends YumeError
{
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $track = [
		Reflect::class => [
			"classes" => [
				Reflect\ReflectClass::class,
				Reflect\ReflectConstant::class,
				Reflect\ReflectDecorator::class,
				Reflect\ReflectEnum::class,
				Reflect\ReflectEnumBacked::class,
				Reflect\ReflectEnumUnit::class,
				Reflect\ReflectExtension::class,
				Reflect\ReflectFiber::class,
				Reflect\ReflectFunction::class,
				Reflect\ReflectGenerator::class,
				Reflect\ReflectMethod::class,
				Reflect\ReflectParameter::class,
				Reflect\ReflectProperty::class,
				Reflect\ReflectType::class
			]
		]
	];
	
}

?>