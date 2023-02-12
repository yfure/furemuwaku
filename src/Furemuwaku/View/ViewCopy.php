<?php

namespace Yume\Fure\View;

use Throwable;

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
	
	protected Data\DataInterface $data;
	
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
	public function __construct( protected Readonly String $view, Array $data = [] )
	{
		// Check if views file is exists.
		if( View::exists( $view ) )
		{
			$this->data = new Data\Data( $data );
		}
		else {
			throw new ViewError( View::path( $view ), code: ViewError::NOT_FOUND_ERROR );
		}
	}
	
	/*
	 * Return output buffering from views.
	 *
	 * @access Private
	 *
	 * @return String
	 */
	private function buffer(): String
	{
		return( call_user_func( function(): String
		{
			try
			{
				// Starting output buffering.
				ob_start();
				
				// Extract all variables.
				extract( $this->data->__toArray() );
				
				// Importing view file.
				include path( $this->fpath( True ) );
				
				// Get and clean output buffering.
				return( ob_get_clean() ?? "" );
			}
			catch( Throwable $e )
			{
				// Removing previous output buffering.
				// When some throwable class thrown.
				ob_clean();
				
				// Just return throwable info.
				return( path( sprintf( "%s: %s in file %s on line %d", $e::class, $e->getMessage(), $e->getFile(), $e->getLine() ), True ) );
			}
		}));
	}
	
	/*
	 * Return if view has cached.
	 *
	 * @access Public
	 *
	 * @return Bool
	 */
	public function cached(): Bool
	{
		return( False );
		return( View::exists( $this->view, True ) );
	}
	
	private function compile()
	{
		// Create new Template instance.
		$template = new Template\Template(
			
			// Get views full pathname.
			$this->fpath(),
			
			// Read a line-by-line file can also be used,
			// basically Template accepts two types of
			// arguments for the $template parameter.
			File\File::read( $this->fpath() )
			
		);
		
		// Parsing template.
		$parsed = $template->clean(
			$template->parse()
		);
		
		// Save parsed template as cache.
		File\File::write( $this->fpath( True ), $parsed );
		//echo htmlspecialchars( $parsed );
		return( $this );
	}
	
	/*
	 * Return view full pathname.
	 *
	 * @access Public
	 *
	 * @params Bool $cache
	 *
	 * @return String
	 */
	public function fpath( Bool $cache = False ): String
	{
		return( View::path( $this->view, $cache ) );
	}
	
	public function render( ? String $view = Null )//: Response\ResponseInterface
	{
		$this->data->compile = [];
		$this->data->compile->start = microtime( True );
		
		if( File\File::modified( $this->fpath(), 60 ) )
		{
			$this->compile();
		}
		else {
			if( $this->cached() === False )
			{
				$this->compile();
			}
		}
		
		$this->data->compile->finish = microtime( True );
		
		return( $this )->buffer();
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