<?php

namespace Yume\Fure\CLI\Command;

use Generator;
use Yume\Fure\CLI\Argument;
use Yume\Fure\Logger;
use Yume\Fure\Util;
use Yume\Fure\Util\Reflect;

/*
 * Command
 *
 * @package Yume\Fure\CLI\Command
 */
abstract class Command implements CommandInterface
{
	
	/*
	 * About of command.
	 * 
	 * @access Protected
	 * 
	 * @values String
	 */
	protected ? String $about = Null;

	/*
	 * Goup name of command.
	 * 
	 * @access Protected
	 * 
	 * @values String
	 */
	protected String $group = "Yume";

	/*
	 * Name of command.
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
	 * #[option-name: Option name]
	 * String "test" => [
	 *    #[option-explain: Explain option usage]
	 *    "explain" => Array|String "Explain option",
	 *    #[option-example: Example usage command with option]
	 *    "example" => Array|String "--test=Test",
	 *    #[option-required: If option is required]
	 *    "required" => Bool True|False,
	 *    #[option-requires: Another option required when use this option]
	 *    "requires" => Array<String>,
	 *    #[option-default: Default option value]
	 *    "default" => Mixed "Default",
	 *    #[option-alias: Option alias name]
	 *    "alias" => String "t",
	 *    #[option-type: Option value type]
	 *    "type" => Yume\Fure\Util\Type
	 * ]
	 * 
	 * @values Array<String,Yume\Fure\CLI\Command\CommandOption>
	 */
	protected Array $options = [];

	/*
	 * Construct method of class Command.
	 * 
	 * @access Public Initialize
	 * 
	 * @params Yume\Fure\CLI\Command\Commands $commands
	 * @params Yume\Fure\Logger\LoggerInterface $logger
	 * 
	 * @return Void
	 * 
	 * @throws Yume\Fure\CLI\Command\CommandUnitializeNameError
	 */
	public function __construct( protected Commands $commands, protected Logger\LoggerInterface $logger )
	{
		// If command name has Initialized.
		if( Reflect\ReflectProperty::isInitialized( $this, "name" ) )
		{
			// If command has options.
			if( count( $this->options ) >= 1 )
			{
				foreach( $this->options As $name => $option )
				{
					// If option is not instance of class CommandOption.
					if( $option Instanceof CommandOption === False )
					{
						$option = new CommandOption(
							explain: type( $option['explain'] ?? Null, "String" ) ? [$option['explain']] : $option['explain'] ?? [],
							example: type( $option['example'] ?? Null, "String" ) ? [$option['example']] : $option['example'] ?? [],
							required: $option['required'] ?? False,
							requires: $option['requires'] ?? [],
							default: $option['default'] ?? Null,
							alias: $option['alias'] ?? Null,
							name: $name,
							type: isset( $option['type'] ) && $option['type'] Instanceof Util\Type ? $option['type'] : Util\Type::Mixed
						);
					}
					$this->options[$name] = $option;
				}
			}
		}
		else {
			throw new CommandUnitializeNameError( $this::class );
		}
	}

	/*
	 * @inherit Yume\Fure\CLI\Command\CommandInterface::getAbout
	 * 
	 */
	public function getAbout(): ? String
	{
		return( $this )->about;
	}
	
	/*
	 * @inherit Yume\Fure\CLI\Command\CommandInterface::getGroup
	 * 
	 */
	public function getGroup(): String
	{
		return( $this )->group;
	}
	
	/*
	 * @inherit Yume\Fure\CLI\Command\CommandInterface::getName
	 * 
	 */
	public function getName(): String
	{
		return( $this )->name;
	}

	protected function getOptionValue( Argument\Argument $argument, CommandOption $option, Mixed $default = Null ): Mixed
	{
		if( $argument->has( $name = $option->name, True ) ||
			$argument->has( $alias = $option->alias ?? "", True ) )
		{
			return( $argument[$name] ?? $argument[$alias] )->value;
		}
		return( $default ?? $option->default );
	}
	
	/*
	 * @inherit Yume\Fure\CLI\Command\CommandInterface::getOptions
	 * 
	 */
	public function getOptions(): Array
	{
		return( $this )->options;
	}

	/*
	 * @inherit Yume\Fure\CLI\Command\CommandInterface::getOptionAliases
	 * 
	 */
	public function getOptionAliases(): Generator
	{
		foreach( $this->options As $option )
		{
			if( $option->hasAlias() ) yield $option->alias;
		}
	}

	/*
	 * @inherit Yume\Fure\CLI\Command\CommandInterface::getOptionRequires
	 * 
	 */
	public function getOptionRequires(): Generator
	{
		foreach( $this->options As $option )
		{
			if( $option->isRequired() ) yield $option;
		}
	}

	/*
	 * @inherit Yume\Fure\CLI\Command\CommandInterface::hasOption
	 * 
	 */
	public function hasOption( String $option, ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $this->hasOption( $option ) === $optional : isset( $this->options[$option] ) );
	}

	/*
	 * @inherit Yume\Fure\CLI\Command\CommandInterface::hasOptions
	 * 
	 */
	public function hasOptions( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $this->hasOptions() === $optional : count( $this->options ) >= 1 );
	}

	/*
	 * @inherit Yume\Fure\CLI\Command\CommandInterface::hasOptionRequires
	 * 
	 */
	public function hasOptionRequires( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $this->hasOptionRequires() === $optional : count( iterator_to_array( $this->getOptionRequires() ) ) >= 1 );
	}

	/*
	 * @inherit Yume\Fure\CLI\Command\CommandInterface::isOptionRequired
	 * 
	 */
	public function isOptionRequired( String $option, ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $this->isOptionRequired( $option ) === $optional : ( $this->hasOption( $option ) ? $this->options[$option]->isRequired() : False ) );
	}
	
}

?>