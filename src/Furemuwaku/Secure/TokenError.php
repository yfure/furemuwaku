<?php

namespace Yume\Fure\Secure;

/*
 * TokenError
 *
 * @package Yume\Fure\Secure
 *
 * @extends Yume\Fure\Secure\SecureError
 */
class TokenError extends SecureError
{
	
	/*
	 * Error constant for unsupported hash algorithm.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const ALGORITHM_ERROR = 76112;
	
	/*
	 * Error constant for invalid token length.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const LENGTH_ERROR = 92622;
	
	/*
	 * Error constant for applications running on
	 * the server when the token has not been generated.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const RUNTIME_ERROR = 92638;
	
	/*
	 * @inherit Yume\Fure\Secure\SecureError
	 *
	 */
	protected Array $flags = [
		TokenError::class => [
			self::ALGORITHM_ERROR,
			self::LENGTH_ERROR,
			self::RUNTIME_ERROR
		]
	];
	
}

?>