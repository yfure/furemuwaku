<?php

namespace Yume\Fure\View\Template;

use Yume\Fure\Support\Data;
use Yume\Fure\Support\Reflect;
use Yume\Fure\Util;

/*
 * TemplateSyntax
 *
 * @package Yume\Fure\View\Template
 */
abstract class TemplateSyntax implements TemplateSyntaxInterface
{
	
	protected Int $line = 0;
	
	/*
	 * Skip process.
	 *
	 * @access Protected
	 *
	 * @values Bool
	 */
	protected Bool $skip = False;
	
	/*
	 * Syntax token name.
	 *
	 * @access Protected
	 *
	 * @values Array|String
	 */
	protected Array | String $token;
	
	/*
	 * Construct method of class TemplateSyntax.
	 *
	 * @access Public Instance
	 *
	 * @params Protected Readonly Yume\Fure\View\Template\TemplateInterface $context
	 *
	 * @return Void
	 *
	 * @throw Yume\Fure\View\Template\TemplateUninitializedTokenError
	 */
	public function __construct( protected Readonly TemplateInterface $context, Array | Data\DataInterface $configs )
	{
		// Checks if the token property has not been initialized.
		if( Reflect\ReflectProperty::isInitialized( $this, "token" ) === False )
		{
			throw new TemplateUninitializedTokenError( $this::class );
		}
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	public function getToken(): Array | String
	{
		return( $this )->token;
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	public function isMultipleToken(): Bool
	{
		return( is_array( $this->token ) );
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	public function isSkip(): Bool
	{
		return( $this )->skip;
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	public function isSupportedToken( String $token ): Bool
	{
		// Normalize token name.
		$token = strtolower( $token );
		
		// Check if syntax is support multiple token.
		if( $this->isMultipleToken() )
		{
			return( in_array( $token, $this->token ) );
		}
		return( $this->token === $token );
	}
	
}

?>