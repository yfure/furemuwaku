<?php

namespace Yume\Fure\View\Component;

use Yume\Fure\Support\Data;

/*
 * Component
 * 
 * @package Yume\Fure\View\Component
 */
abstract class Component implements ComponentInterface
{
	
	public function __construct( Data\DataInterface $props )
	{}
	
	public function render(): Void
	{}
	
}

?>