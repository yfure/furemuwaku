<?php

namespace Yume\Fure\Logger\Handler;

use Yume\Fure\Logger;
use Yume\Fure\Support\Data;

/*
 * BaseHandler
 *
 * @package Yume\Fure\Logger\Handler
 */
abstract class BaseHandler implements HandlerInterface
{
	
	/*
	 * Hander allowed levels.
	 *
	 * @access Protected Readonly
	 *
	 * @values Array<Yume\Fure\Logger\LoggerLevel>
	 */
	protected Readonly Array $allows;
	
	/*
	 * Log DateTime format.
	 *
	 * @access Protected
	 *
	 * @values String
	 */
	protected String $dateTimeFormat;
	
	/*
	 * Construct menthod of class BaseHandler.
	 *
	 * @access Public Instance
	 *
	 * @params Yume\Fure\Support\Data\DataInterface $configs
	 *
	 * @return Void
	 */
	public function __construct( Data\DataInterface $configs )
	{
		// Set allowed levels.
		$this->allows = $configs->handles->__toArray();
	}
	
	/*
	 * @inherit Yume\Fure\Logger\Handler\HandlerInterface
	 *
	 */
	public function allow( Logger\LoggerLevel $level ): Bool
	{
		return( in_array( $level, $this->allows ) );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\Handler\HandlerInterface
	 *
	 */
	public function setDateTimeFormat( String $format ): HandlerInterface
	{
		return([ $this, $this->dateTimeFormat = $format ][0]);
	}
	
}

?>