<?php

namespace Yume\Fure\View;

use Throwable;

use Yume\Fure\Cache;
use Yume\Fure\HTTP\Response;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\File;
use Yume\Fure\Support\Services;
use Yume\Fure\Util;
use Yume\Fure\Util\RegExp;
use Yume\Fure\View\Template;

/*
 * View
 *
 * @package Yume\Fure\View
 */
class View implements ViewInterface
{
	
	protected Readonly Data\DataInterface $data;
	
	public function __construct( public Readonly String $view, Array | Data\DataInterface $data )
	{
		$this->data = new Data\Data( $data );
	}
	
	public function render()//: String
	{
		// Compile view contents.
		Services\Services::get( "template" )->compile( $this->view );
		
	}
	
	/*
	 * Set var.
	 *
	 * @access Public
	 *
	 * @params String $name
	 * @params Mixed $value
	 *
	 * @return Yume\Fure\View\ViewInterface
	 */
	public function with( String $name, Mixed $value ): ViewInterface
	{
		return([ $this, $this->data[$name] = $value ][0]);
	}
	
}

?>