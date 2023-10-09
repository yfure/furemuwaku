<?php

namespace Yume\Fure\CLI\Command;

use Generator;

use Yume\Fure\CLI\Argument;
use Yume\Fure\CLI;
use Yume\Fure\Config;
use Yume\Fure\Config\Config as ConfigConfig;
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
	 *    #[option-implement: Method implementation of option]
	 *    "implement" => String "test",
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
	 * @params Yume\Fure\Config\Config $configs
	 * @params Yume\Fure\Logger\LoggerInterface $logger
	 * 
	 * @return Void
	 * 
	 * @throws Yume\Fure\CLI\Command\CommandUnitializeNameError
	 */
	public function __construct( protected Commands $commands, protected Config\Config $configs, protected Logger\LoggerInterface $logger )
	{
		// If command name has Initialized.
		if( Reflect\ReflectProperty::isInitialized( $this, "name" ) )
		{
			// If command has options.
			if( count( $this->options ) >= 1 )
			{
				// If command has no help option.
				if( isset( $this->options['help'] ) === False )
				{
					$this->options['help'] = [
						"type" => Util\Type::Bool,
						"alias" => "h",
						"explain" => "Display help of command",
						"example" => [],
						"implement" => "help"
					];
				}
				foreach( $this->options As $name => $option )
				{ 
					// If option is not instance of class CommandOption.
					if( $option Instanceof CommandOption === False )
					{
						$option = new CommandOption(
							explain: type( $option['explain'] ?? Null, "String" ) ? [$option['explain']] : $option['explain'] ?? [],
							example: type( $option['example'] ?? Null, "String" ) ? [$option['example']] : $option['example'] ?? [],
							implement: $option['implement'] ?? Null,
							include: $option['include'] ?? False,
							required: $option['required'] ?? False,
							requires: $option['requires'] ?? [],
							default: $option['default'] ?? Null,
							alias: $option['alias'] ?? Null,
							name: $name,
							type: isset( $option['type'] ) && $option['type'] Instanceof Util\Type ? $option['type'] : Util\Type::Mixed
						);
					}

					// If option has defined method implementation
					// but the option implementation is not implemented.
					if( $option->implement !== Null && method_exists( $this, $option->implement ) === False )
					{
						CLI\Console::exit( 1, CLI\Stdout::Error, "Action of option not implemented", $this::class, $this->name, $option->name );
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
	 * @inherit Yume\Fure\CLI\Command\CommandInterface::exec
	 * 
	 */
	public function exec( Argument\Argument $argument ): Void
	{
		if( $argument->count() >= 1 )
		{
			foreach( $this->options As $option )
			{
				if( $option->name === "help" ) continue;
				if( $option->hasImplementation() &&
					$argument->has( $option->name ) )
				{
					// Execute command by argument given.
					Reflect\ReflectMethod::invoke( $this, $option->name, $argument );

					// Break next argument iteration.
					return;
				}
			}
		}
		$this->help();
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

	/*
	 * Return command option value by option.
	 * 
	 * This will also use option alias name if option has alias name.
	 * 
	 * @access Protected
	 * 
	 * @params Yume\Fure\CLI\Argument\Argument $argument
	 * @params Yume\Fure\CLI\Command\CommandOption $option
	 * @params Mixed $default
	 * 
	 * @return Mixed
	 */
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
	 * @inherit Yume\Fure\CLI\Command\CommandInterface::hasAbout
	 * 
	 */
	public function hasAbout( ? Bool $optional = Null ): Bool
	{
		return( $optional !== Null ? $this->hasAbout() === $optional : valueIsNotEmpty( $this->about ) );
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
	 * @inherit Yume\Fure\CLI\Command\CommandInterface::help
	 * 
	 */
	public function help(): Void
	{
		if( $this->commands->has( "help", False ) )
		{
			$this->commands->set( new Commands\Help( 
				configs: new Config\Config( "app", True, [] ),
				commands: $this->commands,
				logger: $this->logger
			));
		}
		$help = $this->commands->get( "help" );
		$help Instanceof Commands\Help ? $help->single( $this ) : False;
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
