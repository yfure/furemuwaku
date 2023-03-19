<?php

/*
 * Yume Environment Helpers.
 *
 * @include env
 * @include envAll
 * @include envIsset
 * @include envSet
 * @include envUnset
 */

use Yume\Fure\Util\Env;

/*
 * @inherit Yume\Fure\Support\Env\Env::get
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

/*
 * @inherit Yume\Fure\Util\Env\Env::getAll
 *
 */
function envAll(): Env\EnvVariables
{
	return( Env\Env::getAll() );
}

/*
 * @inherit Yume\Fure\Util\Env\Env::isset
 *
 */
function envIsset( String $name ): Bool
{
	return( Env\Env::isset( $name ) );
}

/*
 * @inherit Yume\Fure\Util\Env\Env::set
 *
 */
function envSet( String $name, Array | Bool | Int | Null | String $value = Null, ? EnvTypes $type = Null, False | Null | String $comment = False, Array | False | Null $comments = False, Bool $commented = False, Bool $quoted = False ): Void
{
	Env\Env::set( $name, $value, $type, $comment, $comments, $commented, $quoted );
}

/*
 * @inherit Yume\Fure\Util\Env\Env::unset
 *
 */
function envUnset( String $name ): Void
{
	Env\Env::unset( $name );
}

?>