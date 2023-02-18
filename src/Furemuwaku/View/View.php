<?php

namespace Yume\Fure\View;

use Throwable;

use Yume\Fure\App;
use Yume\Fure\Config;
use Yume\Fure\HTTP\Response;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\File;
use Yume\Fure\Util;
use Yume\Fure\View\Template;

/*
 * View
 *
 * @package Yume\Fure\View
 */
class View implements ViewInterface
{
	
	/*
	 * View data.
	 *
	 * @access Protected Readonly
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	protected Readonly Data\DataInterface $data;
	
	/*
	 * Template extension name.
	 *
	 * @access Protected Readonly
	 *
	 * @values String
	 */
	protected Readonly String $extension;
	
	/*
	 * Template cache extension name.
	 *
	 * @access Protected Readonly
	 *
	 * @values String
	 */
	protected Readonly String $extensionCache;
	
	/*
	 * Template pathname.
	 *
	 * @access Protected Readonly
	 *
	 * @values String
	 */
	protected Readonly String $path;
	
	/*
	 * Template cache pathname.
	 *
	 * @access Protected Readonly
	 *
	 * @values String
	 */
	protected Readonly String $pathCache;
	
	/*
	 * Minimum compiled cache.
	 *
	 * @access Protected Readonly
	 *
	 * @values Int
	 */
	protected Readonly Int $modify;
	
	/*
	 * Instanceof class Template.
	 *
	 * @access Protected
	 *
	 * @values Yume\Fure\View\Template\Template
	 */
	protected Readonly Template\Template $template;
	
	use \Yume\Fure\Config\ConfigTrait;
	
	/*
	 * Construct method of class View.
	 *
	 * @access Public Instance
	 *
	 * @params String View
	 * @params Array|Yume\Fure\Support\Data\DataInterface $data
	 *
	 * @return Void
	 */
	public function __construct( private String $view, Array | Data\DataInterface $data = [] )
	{
		$this->data = new Data\Data( $data );
		$this->template = new Template\Template;
		
		// Set view configuration.
		self::config( function( Config\Config $configs )
		{
			$this->extension = $configs['extension'];
			$this->extensionCache = $configs['extension.cache'];
			$this->path = $configs['path'];
			$this->pathCache = $configs['path.cache'];
			$this->modify = $configs['modify'];
		});
	}
	
	/*
	 * Parse class into string.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function __toString(): String
	{
		return( $this )->render();
	}
	
	/*
	 * Compile view template.
	 *
	 * @access Private
	 *
	 * @return Void
	 */
	private function compile(): Void
	{
		try
		{
			$this->fcSave( $this->view,
				$this->template->clean(
					$this->template->parse(
						$this->fpName( $this->view ),
						$this->fvRead( $this->view )
					)
				)
			);
		}
		catch( Template\TemplateError $e )
		{
			throw new ViewError( $this->view, code: ViewError::PARSE_ERROR, previous: $e );
		}
	}
	
	/*
	 * Return current view name.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getView(): String
	{
		return( $this )->view;
	}
	
	/*
	 * Return view cache pathname.
	 *
	 * @access Public
	 *
	 * @params String $view
	 *
	 * @return String
	 */
	public function fcName( ? String $view = Null ): String
	{
		return( sprintf( "%s/%s.%s", $this->pathCache, $view ?? $this->view, $this->extensionCache ) );
	}
	
	/*
	 * Return view pathname.
	 *
	 * @access Public
	 *
	 * @params String $view
	 *
	 * @return String
	 */
	public function fpName( ? String $view = Null ): String
	{
		return( sprintf( "%s/%s.%s", $this->path, $view ?? $this->view, $this->extension ) );
	}
	
	/*
	 * Read views file.
	 *
	 * @access Public
	 *
	 * @params String $view
	 *
	 * @return String
	 */
	public function fvRead( ? String $view = Null ): String
	{
		if( File\File::exists( $name = $this->fpName( $view ??= $this->view ) ) )
		{
			return( File\File::read( $name ) );
		}
		throw new ViewError( $view, ViewError::NOT_FOUND, new File\FileNotFoundError( $name ) );
	}
	
	/*
	 * Save view content as cache.
	 *
	 * @access Public
	 *
	 * @params String $view
	 * @params String $contents
	 *
	 * @return Void
	 */
	public function fcSave( ? String $view = Null, ? String $contents = Null ): Void
	{
		File\File::write( $this->fcName( $view ), $contents ?? "" );
	}
	
	/*
	 * Return if views has cached.
	 *
	 * @access Public
	 *
	 * @params String $view
	 *
	 * @return Bool
	 */
	public function hasCached( ? String $view = Null ): Bool
	{
		return( File\File::exists( $this->fcName( $view ?? $this->view ) ) );
	}
	
	/*
	 * Return if views has modified.
	 *
	 * @access Public
	 *
	 * @params String $view
	 *
	 * @return Bool
	 */
	public function hasModify( ? String $view = Null ): Bool
	{
		return( File\File::modified( $this->fpName( $view ?? $this->view ), $this->modify ?: 60 ) );
	}
	
	/*
	 * Render view contents.
	 *
	 * @access Public
	 *
	 * @params Array $data
	 *
	 * @return String
	 */
	public function render( Array | Data\DataInterface $data = [] ): String
	{
		// Mapping data passed.
		Util\Arr::map( $data, fn( Int $i, Int | String $key, Mixed $value ) => $this->with( $key, $value ) );
		Util\Timer::calculate( "render", function()
		{
			// If view has cached.
			if( $this->hasCached() )
			{
				// If view file has modifiy under time set.
				if( $this->hasModify() )
				{
					$this->compile();
				}
			}
			else {
				$this->compile();
			}
		});
		
		// Set times.
		$this->data->times = Util\Timer::get();
		
		// Return output buffering.
		return( $this )->output();
	}
	
	/*
	 * Return output buffering.
	 *
	 * @access Private
	 *
	 * @return String
	 */
	private function output(): String
	{
		return( call_user_func( function(): String
		{
			// Data stack.
			$data = [];
			
			// Mapping all data.
			$this->data->map( function( Int $i, String $key, Mixed $value ) use( &$data ) { $data[$key] = $value; });
			
			try
			{
				// Starting output buffering.
				ob_start();
				
				// Extract all variables.
				extract( $data );
				
				// Importing view file.
				include path( $this->fcName( $this->view ) );
				
				// Return and clean output buffering.
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
	 * Set view name.
	 *
	 * @access Public
	 *
	 * @params String $view
	 *
	 * @return Yume\Fure\View\ViewInterface
	 */
	public function setView( String $view ): ViewInterface
	{
		if( File\File::exists( $name = $this->fpName( $view ) ) )
		{
			$this->view = $view;
		}
		else {
			throw new ViewError( $view, ViewError::NOT_FOUND, new File\FileNotFoundError( $name ) );
		}
		return( $this );
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