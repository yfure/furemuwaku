<?php

namespace Yume\Fure\Util\Env;

use Throwable;

use Yume\Fure\Error;

/*
 * EnvError
 *
 * @extends Yume\Fure\Error\YumeError
 *
 * @package Yume\Fure\Util\Env
 */
class EnvError extends Error\YumeError
{
	
	public const ASSIGNMENT_ERROR = 49028;
	public const COMMENT_ERROR = 49103;
	public const JSON_ERROR = 49189;
	public const NAME_ERROR = 49199;
	public const OVERRIDE_ERROR = 49197;
	public const SYNTAX_ERROR = 49272;
	public const TYPEDEF_ERROR = 49356;
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $flags = [
		EnvError::class => [
			self::ASSIGNMENT_ERROR => "Unable to determine value \"{}\", with type \"{}\" in variable \"{}\"",
			self::COMMENT_ERROR => "Unterminated \"{}\" variable starting, this usually happens because there is a comment symbol inside the variable value",
			self::JSON_ERROR => "Invalid JSON string value; {} in variable {}",
			self::NAME_ERROR => "Environment named \"{}\" is undefined",
			self::OVERRIDE_ERROR => "Cannot override variable \"{}\", variable has been defined in line {}",
			self::SYNTAX_ERROR => "Syntax error, unexpected token \"{trim(0)}\"",
			self::TYPEDEF_ERROR => "Value type \"{}\" is not supported, maybe you meant ({})?"
		]
	];
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected Array $track = [
		__NAMESPACE__ => [
			"classes" => [
				Env::class
			]
		]
	];
	
	/*
	 * @inherit Yume\Fure\Error\YumeError::__construct
	 *
	 */
	public function __construct( Array | Int | String $message, Int $code = 0, ? Throwable $previous = Null, ? String $file = Null, ? Int $line = Null )
	{
		parent::__construct( $message, $code, $previous, $file, $line );
	}
	
}

?>