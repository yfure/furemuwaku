<?php

namespace Yume\Fure\View\Template;

use Yume\Fure\Error;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\File;
use Yume\Fure\Support\Reflect;
use Yume\Fure\Util;
use Yume\Fure\Util\Json;
use Yume\Fure\Util\RegExp;

/*
 * Template
 *
 * @package Yume\Fure\View\Template
 */
class Template implements TemplateInterface
{
	
	/*
	 * Instanceof class TemplateEngine.
	 *
	 * @access Protected Readonly
	 *
	 * @values Yume\Fure\View\Template\TemplateEngineInterface
	 */
	protected Readonly TemplateEngineInterface $engine;
	
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
	 * Construct method of class Template.
	 *
	 * @access Public Instance
	 *
	 * @params String|Yume\Fure\View\Template\TemplateEngineInterface $engine
	 * @params String $extension
	 * @params String $extensionCache
	 * @params String $path
	 * @params String $pathCache
	 * @params Int $modify
	 *
	 * @return Void
	 */
	public function __construct( Null | String | TemplateEngineInterface $engine = Null, ? String $extension = Null, ? String $extensionCache = Null, ? String $path = Null, ? String $pathCache = Null, Int $modify = 60 )
	{
		// Use default configurations when the value is unexpected.
		$this->extension = $extension ?? config( "view.extension" );
		$this->extensionCache = $extensionCache ?? config( "view[extension.cache]" );
		$this->path = $path ?? config( "view.path" );
		$this->pathCache = $pathCache ?? config( "view[path.cache]" );
		$this->modify = $modify ?: config( "view.modify" ) ?: 60;
		
		// Use default engine when engine is not available.
		$engine ??= config( "view" )->engine->default;
		
		if( is_string( $engine ) )
		{
			// Check if engine has implements TemplateEngineInterface.
			if( Reflect\ReflectClass::isImplements( $engine, TemplateEngineInterface::class, $reflect ) )
			{
				// Create new Template Engine instance.
				$engine = $reflect->newInstance( config( "view" )->engine->configs[$engine] );
			}
			else {
				throw new Error\ClassImplementationError([ $engine, TemplateEngineInterface::class ]);
			}
		}
		
		// Set Template Engine.
		$this->engine = $engine;
	}
	
	/*
	 * Compile view template.
	 *
	 * @access Public
	 *
	 * @params String $view
	 *
	 * @return Void
	 */
	public function compile( String $view ): Void
	{
		if( $this->hasCached( $view ) )
		{
			if( $this->hasModified( $view ) )
			{
				$this->reCompile( $view );
			}
		}
		else {
			$this->reCompile( $view );
		}
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
	public function fcName( String $view ): String
	{
		return( sprintf( "%s/%s.%s", $this->pathCache, $view, $this->extensionCache ) );
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
	public function fpName( String $view ): String
	{
		return( sprintf( "%s/%s.%s", $this->path, $view, $this->extension ) );
	}
	
	public function fvRead( String $view ): String
	{
		return( File\File::read( $this->fpName( $view ) ) );
	}
	
	public function hasCached( String $view ): Bool
	{
		return( File\File::exists( $this->fcName( $view ) ) );
	}
	
	public function hasModify( String $view ): Bool
	{
		return( File\File::modify( $this->fpName( $view ), $this->modify ?: 60 ) );
	}
	
	private function reCompile( String $view ): Void
	{
		$compiled = $this->engine->parse(
			$this->fpName( $view ),
			$this->fvRead( $view )
		);
		echo htmlspecialchars( $compiled ?? "*" );
	}
	
}

?>