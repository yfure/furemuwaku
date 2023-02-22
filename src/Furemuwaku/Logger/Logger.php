<?php

namespace Yume\Fure\Logger;

use Stringable;

use Yume\Fure\Error;
use Yume\Fure\Logger\Handler;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\Reflect;
use Yume\Fure\Util;

/*
 * Logger
 *
 * @package Yume\Fure\Logger
 */
class Logger implements LoggerInterface
{
	
	/*
	 * Levels to be logged.
	 *
	 * @access Protected
	 *
	 * @values Array<String[Yume\Fure\Logger\LoggerLevel]>
	 */
	protected Array $allows = [];
	
	/*
	 * DateTime format.
	 *
	 * @access Protected
	 *
	 * @values String
	 */
	protected String $dateTimeFormat;
	
	/*
	 * Logger handlers.
	 *
	 * @access Protected
	 *
	 * @values Yume\Fure\Support\Data\DataInterface
	 */
	protected Array | Data\DataInterface $handlers;
	
	/*
	 * Log level availables.
	 *
	 * @access Protected
	 *
	 * @values Array
	 */
	protected Array $levels = [
		"alert" => LoggerLevel::ALERT,
		"critical" => LoggerLevel::CRITICAL,
		"debug" => LoggerLevel::DEBUG,
		"emergency" => LoggerLevel::EMERGENCY,
		"error" => LoggerLevel::ERROR,
		"info" => LoggerLevel::INFO,
		"notice" => LoggerLevel::NOTICE,
		"warning" => LoggerLevel::WARNING
	];
	
	use \Yume\Fure\Config\ConfigTrait;
	
	/*
	 * Construct method of class Logger.
	 *
	 * @access Public Instance
	 *
	 * @params Bool $debug
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\Error\AssertionError
	 * @throws Yume\Fure\Logger\LoggerError
	 */
	public function __construct( Bool $debug = YUME_DEBUG )
	{
		self::config( function( $config )
		{
			// Check if threshold is Array type.
			if( $config->threshold Instanceof Data\DataInterface )
			{
				// Mapping thresholds.
				return( $config->threshold->map( function( Int $i, $idx, $val )
				{
					// If level is String type.
					if( is_string( $val ) )
					{
						// Check if exists.
						if( isset( $this->levels[$val] ) )
						{
							$val = $this->levels[$val];
						}
					}
					
					// If level is Int type.
					if( is_int( $val ) )
					{
						// Get array keys.
						$keys = array_keys( $this->levels );
						
						// Check if index is exists.
						if( isset( $keys[$val] ) )
						{
							$val = $this->levels[$keys[$val]];
						}
					}
					
					// If level is Enum LoggerLevel.
					if( $val Instanceof LoggerLevel )
					{
						$this->allows[strtolower( $val->value )] = $val;
					}
					else {
						throw new LoggerError( is_object( $val ) ? $val::class : gettype( $val ), LoggerError::LEVEL_ERROR );
					}
				}));
			}
			throw new Error\AssertionError( [ "threshold", "Array", type( $config->threshold ) ], Error\AssertionError::VALUE_ERROR );
		});
		
		// Set date time format.
		$this->dateTimeFormat = self::$configs->date->format;
		
		// Check if logger has handler.
		if( self::$configs->handlers->count() > 0 )
		{
			// Copy handler class names.
			$this->handlers = self::$configs->handlers->copy();
		}
		else {
			throw new LoggerError( 0, LoggerError::HANDLER_REQUIRED );
		}
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerInterface
	 *
	 */
	public function alert( String | Stringable $message, Array $context = [] ): Void
	{
		$this->log( LoggerLevel::ALERT, $message, $context );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerInterface
	 *
	 */
	public function critical( String | Stringable $message, Array $context = [] ): Void
	{
		$this->log( LoggerLevel::CRITICAL, $message, $context );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerInterface
	 *
	 */
	public function debug( String | Stringable $message, Array $context = [] ): Void
	{
		$this->log( LoggerLevel::DEBUG, $message, $context );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerInterface
	 *
	 */
	public function emergency( String | Stringable $message, Array $context = [] ): Void
	{
		$this->log( LoggerLevel::EMERGENCY, $message, $context );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerInterface
	 *
	 */
	public function error( String | Stringable $message, Array $context = [] ): Void
	{
		$this->log( LoggerLevel::ERROR, $message, $context );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerInterface
	 *
	 */
	public function info( String | Stringable $message, Array $context = [] ): Void
	{
		$this->log( LoggerLevel::INFO, $message, $context );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerInterface
	 *
	 */
	public function notice( String | Stringable $message, Array $context = [] ): Void
	{
		$this->log( LoggerLevel::NOTICE, $message, $context );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerInterface
	 *
	 */
	public function warning( String | Stringable $message, Array $context = [] ): Void
	{
		$this->log( LoggerLevel::WARNING, $message, $context );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerInterface
	 *
	 */
	public function log( Int | String | LoggerLevel $level, String | Stringable $message, Array $context = [] ): Void
	{
		try
		{
			switch( True )
			{
				// If level is Numeric type.
				case is_numeric( $level ):
					
					// Parse level into Int.
					$level = ( Int ) $level;
					
					// Get array level keys.
					$levels = array_keys( $this->levels );
					
					// Check if index is out of range.
					if( isset( $levels[$level] ) === False )
					{
						throw new Error\IndexError( Util\Str::fmt( "Index {} out of range on Array from {}::\$levels", $level, __CLASS__ ), 0 );
					}
					else {
						$level = $this->levels[$levels[$level]];
					}
					break;
				
				// If level is String type.
				case is_string( $level ):
					
					// Convert level to lowercase.
					$level = strtolower( $level );
					
					// Check if key is not exists.
					if( isset( $this->levels[$level] ) === False )
					{
						throw new Error\KeyError( Util\Str::fmt( "Undefined key {} on Array from {}::\$levels", $level, __CLASS__ ), 0 );
					}
					else {
						$level = $this->levels[$level];
					}
					break;
					
				// If level is Enum.
				case $level Instanceof LoggerLevel: break;
					
				// None
				default: throw new LoggerError( gettype( $level ), LoggerError::LEVEL_ERROR ); break;
			}
		}
		catch( Error\LookupError $e )
		{
			throw new LoggerError( $level, LoggerError::LEVEL_ERROR, $e );
		}
		
		// Check if level is allowed for log.
		if( in_array( $level, $this->allows ) )
		{
			// Format message.
			$message = Util\Str::fmt( $message, ...$context );
			
			// Mapping handlers.
			$this->handlers->map( function( Int $i, Int | String $name, Data\DataInterface | Handler\HandlerInterface $handler ) use( $level, $message )
			{
				// Check if handler is Yume\Fure\Support\Data\DataInterface.
				if( $handler Instanceof Data\DataInterface )
				{
					// Create handler instance.
					$handler = $this->handlers[$name] = Reflect\ReflectClass::instance( $name, [$handler] );
				}
				
				// Check if handle allow level.
				if( $handler->allow( $level ) )
				{
					// Set handler datetime format.
					$handler->setDateTimeFormat( $this->dateTimeFormat );
					
					/*
					 * Checks whether the handler does not
					 * allow other handlers to be executed.
					 *
					 */
					if( $handler->handle( $level, $message ) === False )
					{
						return( STOP_ITERATION );
					}
				}
			});
		}
	}
	
}

?>