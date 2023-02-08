<?php

namespace Yume\Fure\View\Component;

use Yume\Fure\View;

/*
 * ComponentError
 * 
 * @package Yume\Fure\View\Component
 * 
 * @extends Yume\Fure\View\ViewError
 */
class ComponentError extends View\ViewError
{
	
	public const NAME_ERROR = 65446;
	
	/*
	 * @inherit Yume\Fure\View\ViewError
	 *
	 */
	protected Array $flags = [
		ComponentError::class => [
			self::NAME_ERROR
		]
	];
	
}

?>