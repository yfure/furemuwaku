<?php

namespace Yume\Fure\Support\Erahandora;

use Throwable;

use Yume\Fure\Error;
use Yume\Fure\Util\Array;
use Yume\Fure\Util\File;
use Yume\Fure\Util\Reflect;

/*
 * ErahandoraSutakku (Error Handler Stack)
 *
 * @package Yume\Fure\Support\Erahandora
 */
class ErahandoraSutakku implements ErahandoraSutakkuInterface
{
	
	/*
	 * Previous exception thrown.
	 *
	 * @access Private
	 *
	 * @values Array
	 */
	private Array $previous = [];
	
	/*
	 * Sutakku exception scheme display.
	 *
	 * @access Protected
	 *
	 * @values Array
	 */
	protected Array $scheme = [
		"Object" => [
			"Code",
			"Type",
			"Class",
			"Trait",
			"Parent",
			"Interface",
			"Previous"
		],
		"File" => [
			"Line",
			"File",
			"Read"
		],
		"Message",
		"Trace"
	];
	
	/*
	 * Instance of exception thrown.
	 *
	 * @access Private
	 *
	 * @values Throwable
	 */
	private Throwable $thrown;
	
	/*
	 * Exception stack trace.
	 *
	 * @access Private
	 *
	 * @values Array
	 */
	private Array $trace = [];
	
	/*
	 * Construct method of class Sutakku.
	 *
	 * @access Public Instance
	 *
	 * @params Array|Throwable $thrown
	 *
	 * @return Void
	 */
	public function __construct( Array | Throwable $thrown )
	{
		// Check if exception thrown is Array type.
		if( is_array( $thrown ) )
		{
			// Get current exception thrown.
			$this->thrown = array_shift( $thrown );
			
			// Mapping previous exceptions.
			Array\Arr::map( $thrown, function( Int $i, String $name, Throwable $class )
			{
				// Create new Suttaku instance.
				$sutakku = new ErahandoraSutakku( $class );
				
				// Building sutakku stack trace.
				$sutakku->build();
				
				// Push previous exception stack trace.
				$this->previous[$name] = $sutakku->getTrace();
			});
		}
		else {
			$this->thrown = $thrown;
		}
	}
	
	/*
	 * @inherit Yume\Fure\Support\Erahandora\ErahandoraSutakkuInterface
	 *
	 */
	public function build(): ErahandoraSutakkuInterface
	{
		return([ $this, $this->trace = $this->stack() ][0]);
	}
	
	/*
	 * Read where exception thrown or trace file.
	 *
	 * @access Private
	 *
	 * @params String $file
	 * @params Int $line
	 *
	 * @return Array
	 */
	private function read( String $file, Int $line ): Array
	{
		try
		{
			$fread = htmlspecialchars( str_replace( [ "\t", "\s\s" ], [ "\xc2\xb7\xc2\xb7\xc2\xb7\xc2\xb7", "\xc2\xb7\xc2\xb7" ], File\File::read( $file ) ) );
		}
		catch( \Throwable $e )
		{
			exit( $e );
		}
		
		// Split file contents with newline.
		$split = explode( "\n", $fread );
		
		// Push.
		$fline = [
			[
				"line" => $line,
				"content" => $split[( $line -1 >= 0 ? $line -1 : $line )] ?? Null
			]
		];
		
		if( $fline[0]['content'] === Null ) return( $fline );
		
		// Looping for previous error line.
		for( $i = 2; $i < 6; $i++ )
		{
			// Get previous line number.
			$back = $line - $i;
			
			// Check if line is not exists.
			if( isset( $split[$back] ) === False ) break;
			
			// Push previous line code.
			$fline[] = [
				"line" => $back +1,
				"content" => $split[$back]
			];
		}
		
		// Reversing array pointer.
		$fline = array_reverse( $fline );
		
		// Looping for next error line.
		for( $i = 0; $i < 4; $i++ )
		{
			// Get next line number.
			$next = $line + $i;
			
			// Check if line is not exists.
			if( isset( $split[$next] ) === False ) break;
			
			// Push next line code.
			$fline[] = [
				"line" => $next +1,
				"content" => $split[$next]
			];
		}
		return( $fline );
	}
	
	/*
	 * @inherit Yume\Fure\Support\Erahandora\ErahandoraSutakkuInterface
	 *
	 */
	public function getPrevious(): Array
	{
		return( $this )->previous;
	}
	
	/*
	 * @inherit Yume\Fure\Support\Erahandora\ErahandoraSutakkuInterface
	 *
	 */
	public function getScheme(): Array
	{
		return( $this )->scheme;
	}
	
	/*
	 * @inherit Yume\Fure\Support\Erahandora\ErahandoraSutakkuInterface
	 *
	 */
	public function getThrown(): Throwable
	{
		return( $this )->thrown;
	}
	
	/*
	 * @inherit Yume\Fure\Support\Erahandora\ErahandoraSutakkuInterface
	 *
	 */
	public function getTrace(): Array
	{
		return( $this )->trace;
	}
	
	/*
	 * Building stack trace.
	 *
	 * @access Private
	 *
	 * @params Array $trace
	 *
	 * @return Mixed
	 */
	private function stack( ? Array $trace = Null ): Mixed
	{
		// Scheme stack.
		$scheme = [];
		
		// Copy object instance.
		$self = $this;
		
		// Check if trace is Null type.
		if( $trace === Null )
			$trace = $this->scheme;
		
		// Mapping trace scheme.
		Array\Arr::map( $trace, static function( Int $i, Int | String $key, Array | String $value ) use( &$scheme, $self )
		{
			// Check if value is array.
			if( is_array( $value ) )
			{
				// Push scheme.
				$scheme[strtolower( $key )] = $self->stack( $value );
			}
			else {
				
				// Check if value is String type.
				if( is_string( $value ) )
				{
					// Maching scheme key.
					$scheme[strtolower( $value )] = match( $value )
					{
						// Throwable class name.
						"Class" => $self->thrown::class,
						
						// The source of the thrown Throwable.
						"File" => path( $self->thrown->getFile(), True ),
						
						// List of Interfaces implemented.
						"Interface" => array_keys( Reflect\ReflectClass::getInterfaces( $self->thrown ) ),
						
						// Throwable message.
						"Message" => path( $self->thrown->getMessage(), True ),
						
						// Throwable parent class name.
						"Parent" => array_reverse( Reflect\ReflectClass::getParentClasses( $self->thrown ) ),
						
						// Get the previous exception.
						"Previous" => $self->previous,
						
						// Read the file that threw the exception.
						"Read" => $self->read( $self->thrown->getFile(), $self->thrown->getLine() ),
						
						// List of Traits used.
						"Trait" => Reflect\ReflectClass::getTraits( $self->thrown ),
						
						// Handle traces.
						"Trace" => function( ErahandoraSutakkuInterface $self )
						{
							// Get all traces.
							$traces = $self->thrown->getTrace();
							
							// Checks if the exception thrown is of class Error Trigger Exception.
							if( $self->thrown Instanceof Error\TriggerError )
							{
								/*
								 * Unset the first trace.
								 *
								 * If you are thinking why is this done because, the first trace will contain
								 * the information where the TriggerError class exception was thrown whereas
								 * the second trace contains the information where the Error Handler Function
								 * was called because, basically the error handled has been re-thrown by the
								 * Error Handler Function and not necessarily the first and second traces
								 * in the track list.
								 */
								array_shift( $traces );
							}
							
							// Mapping error traces.
							Array\Arr::map( $traces, function( Int $i, Int $idx, Array $trace ) use( $self, &$traces )
							{
								// Check if file name is exists.
								if( isset( $trace['file'] ) )
								{
									// Read trace error file.
									//$trace['read'] = $self->read( $trace['file'], $trace['line'] );
									
									// Clear field names from BASE PATH.
									$trace['file'] = path( $trace['file'], True );
								}
								
								// Push trace.
								$traces[$idx] = $trace;
							});
							
							// Return traces.
							return( $traces );
						},
						
						// For default scheme.
						default => function( ErahandoraSutakkuInterface $self, String $value ) use( $key )
						{
							// Check if the method is available.
							if( method_exists( $self->thrown, $method = f( "get{}", $value ) ) )
							{
								// Return method value.
								return( $self->thrown->{ $method }() );
							}
							return( "UNKNOWN" );
						}
					};
					
					// Check if matched scheme is callable type.
					if( is_callable( $scheme[strtolower( $value )] ) )
					{
						// Get return from callback function.
						$scheme[strtolower( $value )] = call_user_func( $scheme[strtolower( $value )], $self, $value );
					}
				}
			}
		});
		
		// Return scheme.
		return( $scheme );
	}
	
}

?>