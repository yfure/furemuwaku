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
	
	use \Yume\Fure\Config\ConfigTrait;
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
		try
		{
			// Create new template instance.
			$this->template = new Template\Template(
				
				// Reading file per-line.
				File\File::readline(
					
					// Get view pathname.
					self::path( $view )
				)
			);
		}
		catch( File\FileError $e )
		{
			throw new ViewError( $view, ViewError::NOT_FOUND_ERROR, $e );
		}
	}
	
	public function hasParsed(): Bool
	{
		return( $this->templateParsed !== Null );
	}
	
	public function render()//: Response\ResponseInterface
	{
		// Checks if the template has not been parsed.
		if( $this->hasParsed() === False )
		{
			try
			{
				// Try to parsing template.
				$this->templateParsed = $this->template->parse(
					$this->template->getTemplate()
				);
			}
			catch( Template\TemplateError $e )
			{
				throw new ViewError( $this->view, ViewError::PARSE_ERROR, $e );
			}
		}
		return( $this )->templateParsed;
	}
	
	public function with( String $name, Mixed $value ): ViewInterface
	{
		return([ $this, $this->data[$name] = $value ][0]);
	}
	
}

?>