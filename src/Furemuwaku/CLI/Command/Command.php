<?php

namespace Yume\Fure\CLI\Command;

use Yume\Fure\CLI\Argument;
use Yume\Fure\Logger;
use Yume\Fure\Support\Reflect;
use Yume\Fure\Util;

/*
 * Command
 *
 * @package Yume\Fure\CLI\Command
 */
abstract class Command implements CommandInterface
{
	
	/*
	 * Command abouts/ descriptions.
	 *
	 * @access Protected
	 *
	 * @values Array
	 */
	protected ? String $about = Null;
	
	/*
	 * Command group name.
	 *
	 * @access Protected
	 *
	 * @values String
	 */
	protected String $group = "Yume";
	
	/*
	 * Command name.
	 *
	 * @access Protected
	 *
	 * @values String
	 */
	protected String $name;
	
	/*
	 * Command options.
	 *
	 * @access Protected
	 *
	 * @values Array
	 */
	protected Array $options = [];
	
	/*
	 * Command option aliases.
	 *
	 * @access Protected
	 *
	 * @values Array
	 */
	protected Array $optionAliases = [];
	
	/*
	 * Command usage.
	 *
	 * @access Protected
	 *
	 * @values Array|String
	 */
	protected Array | Null | String $usage = Null;
	
	/*
	 * Construct method of class Command.
	 *
	 * @access Public Instance
	 *
	 * @params Protected Readonly Yume\Fure\CLI\Command\Commands $commands
	 * @params Protected Readonly Yume\Fure\Logger\LoggerInterface $logger
	 *
	 * @return Void
	 *
	 * @throws Yume\Fure\CLI\Command\CommandUnitializedNameError
	 */
	public function __construct( protected Readonly Commands $commands, protected Readonly Logger\LoggerInterface $logger )
	{
		// If command name has Initialized.
		if( Reflect\ReflectProperty::isInitialized( $this, "name" ) )
		{
			$options = [];
			
			foreach( $this->options As $name => $option )
			{
				$type = Util\Types::MIXED;
				$default = Null;
				$required = False;
				$requiredWith = [];
				
				if( is_string( $name ) )
				{
					// If option have configuration.
					if( is_array( $option ) )
					{
						$type = $option['type'] ?? $type;
						$default = $option['default'] ?? $default;
						$required = $option['required'] ?? $required;
						$requiredWith = $option['required.with'] ?? $requiredWith;
					}
					
					// If option is enum Types.
					if( $option Instanceof Util\Types ) $type = $option;
				}
				
				// If option is String type.
				if( is_string( $option ) ) $name = $option;
				
				// Make option.
				$options[$name] = new CommandOption( name: $name, type: $type, long: strlen( $name ) > 1, alias: $this->optionAliases[$name] ?? Null, command: $this, default: $default, required: $required, requiredWith: $requiredWith );
			}
			$this->options = $options;
		}
		else {
			throw new CommandUnitializedNameError( $this::class );
		}
	}
	
	/*
	 * Get command abouts/ descriptions.
	 *
	 * @access Public
	 *
	 * @values Array
	 */
	public function getAbout(): ? String
	{
		return( $this )->about;
	}
	
	/*
	 * Get command group name.
	 *
	 * @access Public
	 *
	 * @values String
	 */
	public function getGroup(): String
	{
		return( $this )->group;
	}
	
	/*
	 * Get command name.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	public function getName(): String
	{
		return( $this )->name;
	}
	
	/*
	 * Get command options.
	 *
	 * @access Public
	 *
	 * @return Array
	 */
	public function getOptions(): Array
	{
		return( $this )->options;
	}
	
	/*
	 * Get command option aliases.
	 *
	 * @access Public
	 *
	 * @return Array
	 */
	public function getOptionAliases(): Array
	{
		return( $this )->optionAliases;
	}
	
	public function getRequiredOptions(): Array | False
	{
		foreach( $this->options As $name => $option )
		{
			if( $option->isRequired() ) $options[$name] = $option;
		}
		return( $options ?? False );
	}
	
	/*
	 * Get command usage.
	 *
	 * @access Public
	 *
	 * @return Array|String
	 */
	public function getUsage(): Array | Null | String
	{
		return( $this )->usage;
	}
	
	public function hasOptions(): Bool
	{
		return( count( $this->options ) > 0 );
	}
	
	public function hasRequiredOptions(): Bool
	{
		foreach( $this->options As $option )
		{
			if( $option->isRequired() ) return( True );
		}
		return( False );
	}
	
	/*
	 * @inherit Yume\Fure\CLI\Command\CommandInterface::run
	 *
	 */
	abstract public function run( Argument\Argument $args ): Void;
	
}

?>