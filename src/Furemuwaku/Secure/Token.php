<?php

namespace Yume\Fure\Secure;

use Yume\Fure\App;
use Yume\Fure\Support\Design;
use Yume\Fure\Util;
use Yume\Fure\Util\Random;
use Yume\Fure\Util\Env;

/*
 * Token
 *
 * @package Yume\Fure\Secure
 *
 * @extends Yume\Fure\Support\Design\Singleton
 */
final class Token extends Design\Singleton
{
	
	/*
	 * Default token hash algorithm.
	 *
	 * @access Static Private
	 *
	 * @values String
	 */
	static private String $defaultAlgo = "SHA512";
	
	/*
	 * Default token length.
	 *
	 * @access Static Private
	 *
	 * @values Int
	 */
	static private Int $defaultLength = 64;
	
	/*
	 * Minimum token length.
	 *
	 * @access Static Private
	 *
	 * @values Int
	 */
	static private Int $minimumLength = 64;
	
	/*
	 * Supported hash algorithm for token.
	 *
	 * @access Static Private
	 *
	 * @values Array
	 */
	static private Array $support = [
		
		/*
		 * RIPEMD is a family of cryptographic hash
		 * functions developed in 1992 and 1996.
		 *
		 */
		"RIPEMD160",
		
		/*
		 * SHA stands for Secure Hash Algorithm
		 * and it’s used for cryptographic security.
		 *
		 * Cryptographic hash algorithms produce irreversible
		 * and unique hashes. The larger the number of possible
		 * hashes, the smaller the chance that two values will
		 * create the same hash.
		 *
		 */
		"SHA256",
		"SHA384",
		"SHA512",
		
		/*
		 * Whirlpool is a cryptographic hash function.
		 *
		 */
		"Whirlpool"
		
	];
	
	/*
	 * @inherit Yume\Fure\Support\Design\Singleton
	 *
	 */
	protected function __construct()
	{
		// Check if aplication token is not created.
		if( self::hasGenerated() === False )
		{
			// Check if application is running on cli mode.
			if( App\App::self()->isCli() )
			{
				// Generate new application token.
				self::generate();
			}
			else {
				
				// Check if environment variable TOKEN_REGENERATE exists.
				if( Env\Env::isset( "TOKEN_REGERATE" ) )
				{
					// Check if token is not allowed to regenerate token in the server.
					if( Env\Env::get( "TOKEN_REGERATE" ) )
					{
						return;
					}
				}
			}
			throw new TokenError( 0, TokenError::RUNTIME_ERROR );
		}
	}
	
	/*
	 * Generates a random random string for the app token.
	 *
	 * This method only works according to the
	 * environment variables that have been set.
	 *
	 * When this method is called the generated token will
	 * be automatically set to an environment variable.
	 *
	 * If the TOKEN_ALGORITHM variable in the environment
	 * variable is set to SHA512 then it will generate a
	 * random SHA512 string and the string will be truncated
	 * and adjusted to the length set in TOKEN_LENGTH in the
	 * environment variable.
	 *
	 * @access Public Static
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\Secure\SecureError
	 */
	public static function generate(): Void
	{
		// Check if variable TOKEN_LENGTH is not set.
		if( Env\Env::isset( "TOKEN_LENGTH", False ) )
			Env\Env::set( "TOKEN_LENGTH", static::$defaultLength );
			
		// Check if variable TOKEN_ALGORITHM is not set.
		if( Env\Env::isset( "TOKEN_ALGORITHM", False ) )
			Env\Env::set( "TOKEN_ALGORITHM", static::$defaultAlgo );
		
		// Get token algorithm.
		$algo = Env\Env::get( "TOKEN_ALGORITHM" );
		
		// Get token length.
		$length = Env\Env::get( "TOKEN_LENGTH" );
		
		// Check if token algorithm is supported.
		if( in_array( $algo, static::$support ) >= 0 )
		{
			// Check if token length is smaller than 64.
			if( $length < static::$minimumLength )
			{
				throw new TokenError( $length, TokenError::LENGTH_ERROR );
			}
		}
		else {
			throw new TokenError( $algo, TokenError::ALGORITHM_ERROR );
		}
		
		// Token stacks.
		$tokens = [];
		
		// Looping by token length.
		for( $i = 0; $i < $length; $i++ )
		{
			// Push generated new token.
			$tokens[] = hash( $algo, Random\Random::strings( $length ) );
		}
		
		// Removes duplicate tokens from an array.
		$tokens = array_unique( $tokens );
		
		// Shuffle an array.
		shuffle( $tokens );
		
		// Combining all tokens.
		$tokens = implode( "", $tokens );
		
		// Store token into environment variable.
		Env\Env::set( "TOKEN", substr( $tokens, 0, $length ) );
	}
	
	/*
	 * Get default token hash algorithm.
	 *
	 * @access Public Static
	 *
	 * @return String
	 */
	public static function getDefaultAlgo(): String
	{
		return( static::$defaultAlgo );
	}
	
	/*
	 * Default token length.
	 *
	 * @access Public Static
	 *
	 * @return Int
	 */
	public static function getDefaultLength(): Int
	{
		return( static::$defaultLength );
	}
	
	/*
	 * Minimum token length.
	 *
	 * @access Public Static
	 *
	 * @return Int
	 */
	public static function getMinimumLength(): Int
	{
		return( static::$minimumLength );
	}
	
	/*
	 * Supported hash algorithm for token.
	 *
	 * @access Public Static
	 *
	 * @return Array
	 */
	public static function getSupportedAlgos(): Array
	{
		return( static::$support );
	}
	
	/*
	 * Return application token has generated.
	 *
	 * @access Public Static
	 *
	 * @return Bool
	 */
	public static function hasGenerated(): Bool
	{
		// Check if variable TOKEN is set.
		if( Env\Env::isset( "TOKEN" ) && 
			Env\Env::isset( "TOKEN_LENGTH" ) && 
			Env\Env::isset( "TOKEN_ALGORITHM" ) )
		{
			// Get token value.
			$token = Env\Env::get( "TOKEN" );
			
			// Get token length.
			$length = Env\Env::get( "TOKEN_LENGTH" );
			
			// Return comparation.
			return( valueIsNotEmpty( $token ) && $length >= 64 && strlen( $token ) === $length );
		}
		return( False );
	}
	
}

?>