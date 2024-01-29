<?php

namespace Yume\Fure\Logger;

use Stringable;

use Yume\Fure\Config;
use Yume\Fure\Error;
use Yume\Fure\Support;
use Yume\Fure\Util\Arr;
use Yume\Fure\Util\Reflect;

/*
 * Logger
 *
 * @package Yume\Fure\Logger
 */
class Logger implements LoggerInterface {
	
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
	 * @values Yume\Fure\Util\Arr\Arrayable
	 */
	protected Arr\Arrayable $handlers;
	
	/*
	 * Log level availables.
	 *
	 * @access Protected
	 *
	 * @values Array
	 */
	protected Array $levels = [
		"alert" => LoggerLevel::Alert,
		"critical" => LoggerLevel::Critical,
		"debug" => LoggerLevel::Debug,
		"emergency" => LoggerLevel::Emergency,
		"error" => LoggerLevel::Error,
		"info" => LoggerLevel::Info,
		"notice" => LoggerLevel::Notice,
		"warning" => LoggerLevel::Warning
	];
	
	use \Yume\Fure\Config\ConfigTrait;
	
	/*
	 * Construct method of class Logger.
	 *
	 * @access Public Initialize
	 *
	 * @return Void
	 */
	public function __construct() {
		$this->dateTimeFormat = config( "logger" )->datetime->format;
		$this->handlers = new Arr\Associative;
		$this->prepare();
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerInterface::alert
	 *
	 */
	public function alert( String | Stringable $message, Array $context = [] ): Void {
		$this->log( LoggerLevel::Alert, $message, $context );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerInterface::critical
	 *
	 */
	public function critical( String | Stringable $message, Array $context = [] ): Void {
		$this->log( LoggerLevel::Critical, $message, $context );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerInterface::debug
	 *
	 */
	public function debug( String | Stringable $message, Array $context = [] ): Void {
		$this->log( LoggerLevel::Debug, $message, $context );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerInterface::emergency
	 *
	 */
	public function emergency( String | Stringable $message, Array $context = [] ): Void {
		$this->log( LoggerLevel::Emergency, $message, $context );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerInterface::error
	 *
	 */
	public function error( String | Stringable $message, Array $context = [] ): Void {
		$this->log( LoggerLevel::Error, $message, $context );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerInterface::info
	 *
	 */
	public function info( String | Stringable $message, Array $context = [] ): Void {
		$this->log( LoggerLevel::Info, $message, $context );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerInterface::notice
	 *
	 */
	public function notice( String | Stringable $message, Array $context = [] ): Void {
		$this->log( LoggerLevel::Notice, $message, $context );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerInterface::warning
	 *
	 */
	public function warning( String | Stringable $message, Array $context = [] ): Void {
		$this->log( LoggerLevel::Warning, $message, $context );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerInterface::log
	 *
	 */
	public function log( Int | String | LoggerLevel $level, String | Stringable $message, Array $context = [] ): Void {
		try {
			switch( True ) {
				// If level is Numeric type.
				case is_numeric( $level ):
					
					// Parse level into Int.
					$level = ( Int ) $level;
					
					// Get array level keys.
					$levels = array_keys( $this->levels );
					
					// Check if index is out of range.
					if( isset( $levels[$level] ) === False ) {
						throw new Error\IndexError( f( "Index {} out of range on Array from {}::\$levels", $level, __CLASS__ ), 0 );
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
					if( isset( $this->levels[$level] ) === False ) {
						throw new Error\KeyError( f( "Undefined key {} on Array from {}::\$levels", $level, __CLASS__ ), 0 );
					}
					else {
						$level = $this->levels[$level];
					}
					break;
					
				// If level is Enum.
				case $level Instanceof LoggerLevel: break;
					
				// None
				default: throw new LoggerError( gettype( $level ), LoggerError::LEVEL_ERROR );
			}
		}
		catch( Error\LookupError $e ) {
			throw new LoggerError( $level, LoggerError::LEVEL_ERROR, $e );
		}
		
		// Check if level is allowed for log.
		if( in_array( $level, $this->allows ) ) {
			// Format message.
			$message = f( $message, ...$context );
			
			// Mapping handlers.
			$this->handlers->map( function( Int $i, Int | String $name, Config\Config | LoggerHandlerInterface $handler ) use( $level, $message ) {
				// Check if handler allowed the level.
				if( $handler->allow( $level ) ) {
					return( $handler )

						// Set handler date time format.
						->setDateTimeFormat( $this->dateTimeFormat )

						/*
						 * Handle log message.
						 * 
						 * When the handle return False after handle log message,
						 * it will never call the another handler after this handler.
						 * 
						 */
						->handle( $level, $message ) ?: new Support\Stoppable;
				}
				else {
				}
			});
		}
		else {
		}
	}
	
	/*
	 * Preparing logger.
	 *
	 * @access Private
	 *
	 * @return Void
	 */
	private function prepare(): Void {
		// Get logger configuration.
		$config = Logger::config();
		
		// Check if threshold is Array type.
		if( $config->threshold Instanceof Arr\Arrayable ) {
			// Mapping thresholds.
			$config->threshold->map( function( Int $i, $idx, $val ) {
				// If level is String type.
				if( is_string( $val ) ) {
					// Check if exists.
					if( isset( $this->levels[$val] ) ) {
						$val = $this->levels[$val];
					}
				}
				
				// If level is Int type.
				if( is_int( $val ) ) {
					// Get array keys.
					$keys = array_keys( $this->levels );
					
					// Check if index is exists.
					if( isset( $keys[$val] ) ) {
						$val = $this->levels[$keys[$val]];
					}
				}
				
				// If level is Enum LoggerLevel.
				if( $val Instanceof LoggerLevel ) {
					$this->allows[strtolower( $val->value )] = $val;
				}
				else {
					throw new LoggerError( is_object( $val ) ? $val::class : gettype( $val ), LoggerError::LEVEL_ERROR );
				}
			});
		}
		else {
			throw new Error\AssertionError( [ "threshold", "Array", type( $config->threshold ) ], Error\AssertionError::VALUE_ERROR );
		}

		// Check if logger has handler.
		if( self::$configs->handlers->count() >= 1 ) {
			$handlers = self::$configs->handlers->copy();
			$handlers->map( function( Int $i, Int | String $name, Config\Config | LoggerHandlerInterface $handler ): Void {
				if( $handler Instanceof Arr\Arrayable ) {
					// Check if logger handler name is invalid name.
					if( is_string( $name ) === False ) throw new Error\AssertionError( [ "logger handler name", "String", type( $name ) ], Error\AssertionError::VALUE_ERROR );

					// Check if logger handler class is Exist.
					if( Support\Package::exists( $name, False ) ) throw new Error\ModuleNotFoundError( $name );

					// Check if logger handler does not implement LoggerHandlerInterface.
					if( Reflect\ReflectClass::isImplements( $name, LoggerHandlerInterface::class, $reflect ) === False ) throw new Error\ClassImplementationError([ $name, LoggerHandlerInterface::class ]);

					// Create new Logger Handler Instance.
					$handler = Reflect\ReflectClass::instance( $name, [$handler], $reflect );
				}
				$this->handlers[$handler::class] = $handler;
			});
		}
		else {
			throw new LoggerError( 0, LoggerError::HANDLER_ERROR );
		}
	}
	
}

?>