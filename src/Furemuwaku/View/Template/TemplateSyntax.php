<?php

namespace Yume\Fure\View\Template;

use Yume\Fure\Error;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\Reflect;
use Yume\Fure\Util;
use Yume\Fure\Util\RegExp;

/*
 * TemplateSyntax
 *
 * @package Yume\Fure\View\Template
 */
abstract class TemplateSyntax implements TemplateSyntaxInterface
{
	
	/*
	 * Current view line.
	 *
	 * @access Protected
	 *
	 * @values False|Int
	 */
	protected False | Int $line = False;
	
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
		if( $this->hasToken() === False )
		{
			throw new TemplateUninitializedTokenError( $this::class );
		}
	}
	
	final public function clear( String $value ): String
	{
		return( RegExp\RegExp::replace( "/^\s+|\s+$/", $value, "" ) );
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	final public function getToken(): Array | String
	{
		// Check if syntax is support multiple token.
		if( $this->isMultipleToken() )
		{
			// Check if array is not List type.
			if( array_is_list( $this->token ) === False )
			{
				return( array_keys( $this->token ) );
			}
		}
		return( $this )->token;
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	final public function hasToken(): Bool
	{
		return( Reflect\ReflectProperty::isInitialized( $this, "token" ) );
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	final public function isMultipleToken(): Bool
	{
		return( is_array( $this->token ) );
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	final public function isSkip(): Bool
	{
		return( $this )->skip;
	}
	
	/*
	 * @inherit Yume\Fure\View\Template\TemplateSyntaxInterface
	 *
	 */
	final public function isSupportedToken( String $token ): Bool
	{
		// Normalize token name.
		$token = strtolower( $token );
		
		// Check if syntax is support multiple token.
		if( $this->isMultipleToken() )
		{
			// Check if array is List type.
			if( array_is_list( $this->token ) )
			{
				return( in_array( $token, $this->token ) );
			}
			return( isset( $this->token[$token] ) );
		}
		return( $this->token === $token );
	}
	
	/*
	 * Normalize captured content.
	 * This will remove any backslash before the given characters.
	 *
	 * @access Public
	 *
	 * @params Bool|String $subject
	 * @params Array $chars
	 *
	 * @return Bool|String
	 *
	 * @throws Yume\Fure\Error\ValueError
	 */
	final protected function normalize( Bool | String $subject, Array $chars ): Bool | String
	{
		// Check if subject is Boolean type.
		if( is_bool( $subject ) ) return( $subject );
		
		// Check if character is empty.
		if( count( $chars ) === 0 ) throw new Error\ValueError( "The character to be replaced cannot be empty" );
		
		// Returns replaced subject.
		return( RegExp\RegExp::replace( f( "/\\\({implode(+)})/", [ "|", $chars ] ), $subject, "$1" ) );
	}
	
	protected function removeFirstLine( Array $content ): Array
	{
		if( isset( $content[0] ) )
		{
			// Get first content value.
			$first = $content[0];
			
			// Check if first deep content is empty
			if( $first === "" || $first === "\n" )
			{
				// Unset first content.
				unset( $content[0] );
				
				// Looping!
				$content = $this->removeFirstLine( $content );
			}
		}
		return( $content );
	}
	
}

?>