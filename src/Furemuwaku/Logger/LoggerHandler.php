<?php

namespace Yume\Fure\Logger;

use Yume\Fure\Config;

/*
 * LoggerHandler
 *
 * @package Yume\Fure\Logger
 */
abstract class LoggerHandler implements LoggerHandlerInterface
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
	 * Construct menthod of class LoggerHandler.
	 *
	 * @access Public Instance
	 *
	 * @params Yume\Fure\Support\Data $configs
	 *
	 * @return Void
	 */
	public function __construct( Config\Config $configs )
	{
		// Set allowed levels.
		$this->allows = $configs->handles->__toArray();
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerHandlerInterface::allow
	 *
	 */
	public function allow( LoggerLevel $level ): Bool
	{
		return( in_array( $level, $this->allows ) );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerHandlerInterface::setDateTimeFormat
	 *
	 */
	public function setDateTimeFormat( String $format ): LoggerHandlerInterface
	{
		return([ $this, $this->dateTimeFormat = $format ][0]);
	}
	
}

?>