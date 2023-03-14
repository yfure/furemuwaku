<?php

/*
 * Yume Environment Helpers.
 *
 * @include env
 */

use Yume\Fure\Util\Env;

if( function_exists( "env" ) === False )
{
	/*
	 * @inherit Yume\Fure\Support\Env\Env
	 *
	 */
	function env( String $env, Mixed $optional = Null )
	{
		try
		{
			return( Env\Env::get( $env, $optional ) );
		}
		catch( Env\EnvError $e )
		{
			// Check if optional value is Callable type.
			if( is_callable( $optional ) )
			{
				return( call_user_func( $optional ) );
			}
			return( $optional );
		}
	}
}

?>