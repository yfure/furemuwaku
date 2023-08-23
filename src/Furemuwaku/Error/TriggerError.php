<?php

namespace Yume\Fure\Error;

use PhpParser\Lexer\TokenEmulator\ReadonlyTokenEmulator;
use PhpParser\Node\Expr\Cast\String_;
use Throwable;

/*
 * TriggerError
 *
 * @extends Yume\Fure\Error\YumeError
 *
 * @package Yume\Fure\Error
 */
class TriggerError extends YumeError
{

	/*
	 * @inherit Yume\Fure\Error\YumeError::$type
	 * 
	 */
	protected Readonly String $type;
	
	/*
	 * Construct method of class TriggerError.
	 *
	 * @access Public Initialize
	 *
	 * @params String $message
	 * @params String $type
	 * @params String $file
	 * @params Int $code
	 * @params Throwable $previous
	 *
	 * @return Void
	 */
	public function __construct( String $message, String $level, String $file, Int $line, Int $code, ? Throwable $previous = Null )
	{
		try
		{
			$this->type = $level;
		}
		catch( Throwable )
		{}
		parent::__construct( $message, $code, $previous, $file, $line );
	}
	
}

?>