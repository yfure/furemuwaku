<?php

namespace Yume\Fure\View;

use Yume\Fure\Cache;
use Yume\Fure\HTTP\Response;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\File;
use Yume\Fure\Support\Package;
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
	
	/*
	 * Instance of class Template.
	 *
	 * @access Protected Readonly
	 *
	 * @values Yume\Fure\View\Template\TemplateInterface
	 */
	protected Readonly Template\TemplateInterface $template;
	
	/*
	 * Template parsed.
	 *
	 * @access Protected
	 *
	 * @values String
	 */
	protected ? String $templateParsed = Null;
	
	use \Yume\Fure\View\ViewTrait;
	
	/*
	 * Construct method of class View.
	 *
	 * @access Public Instance
	 *
	 * @params Protected Readonly String $view
	 *
	 * @return Void
	 */
	public function __construct( protected Readonly String $view )
	{
		// Check if template file doesn't exists.
		if( self::exists( $view ) === False )
		{
			throw new ViewError( $view, code: ViewError::NOT_FOUND_ERROR );
		}
	}
	
	public function hasCached(): Bool
	{
		
	}
	
	public function hasParsed(): Bool
	{
		return( $this->templateParsed !== Null );
	}
	
	public function render()//: Response\ResponseInterface
	{
		
	}
	
	public function with( String $name, Mixed $value ): ViewInterface
	{
		return([ $this, $this->data[$name] = $value ][0]);
	}
	
}

?>