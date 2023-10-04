<?php

namespace Yume\Fure\CLI\Command\Helper;

use Yume\Fure\CLI\Argument;
use Yume\Fure\CLI\Command;
use Yume\Fure\CLI\Command\CommandInterface;
use Yume\Fure\Util;

/*
 * Help
 * 
 * @extends Yume\Fure\CLI\Command\Command
 * 
 * @package Yume\Fure\CLI\Command\Helper
 */
final class Help extends Command\Command implements Command\CommandInterface
{

	/*
	 * @inherit Yume\Fure\CLI\Command\Command::$about
	 * 
	 */
	protected ? String $about = "Display help";

	/*
	 * @inherit Yume\Fure\CLI\Command\Command::$name
	 * 
	 */
	protected String $name = "help";

	/*
	 * @inherit Yume\Fure\CLI\Command\Command::$options
	 * 
	 */
	protected Array $options = [
		"all" => [
			"type" => Util\Type::Bool,
			"alias" => "a",
			"explain" => "Display command with another info"
		],
		"command" => [
			"type" => Util\Type::Bool,
			"alias" => "c",
			"explain" => "Display command only"
		]
	];

	/*
	 * @inherit Yume\Fure\CLI\Command\CommandInterface::exec
	 * 
	 */
	public function exec( Argument\Argument $argument ): Void
	{
		// Command group container.
		$groups = [];

		// Grouping commands.
		$this->commands->getAll()->map(
			
			/*
			* Grouping command by command group name.
			* 
			* @params Int $i
			* @params String $name
			* @params Yume\Fure\CLI\Command\CommandInterface $command
			* 
			* @return Void
			*/
			function( Int $i, String $name, CommandInterface $command ) use( &$groups )
			{
				$groups[$command->getGroup()] ??= [];
				$groups[$command->getGroup()][$name] = implode( PHP_EOL, $this->builder( $command ) );
			}
		);

		putcln( $this->configs->welcome[0] );
		putcln( $this->configs->welcome[1] );
		putcln( $this->configs->usage, PHP_EOL );
		putcln( $this->configs->command, PHP_EOL );

		$disable = $this->getOptionValue( $argument, $this->options['command'], False );
		
		// Re-iterating grouping command.
		foreach( $groups As $group => $commands )
		{
			// Display command group name.
			putcln( $this->configs->formats['command.group-name'], $group );

			// Re-iterating commands.
			foreach( $commands As $name => $command )
			{
				// If only display command names.
				if( $disable )
				{
					putcln( $this->configs->formats['command.info-name'], $name ); 
					continue;
				}
				putcln( $this->configs->formats['command.info-list'], $name, $command );
			}
		}
		putln( "" );
	}

	/*
	 * ...
	 * 
	 * @access Private
	 * 
	 * @params Yume\Fure\CLI\Command\CommandInterface $command
	 * 
	 * @return Array<String,Mixed>
	 */
	private function builder( CommandInterface $command ): Array
	{
		$docs = [];

		// Build command description.
		if( $command->hasAbout() ) $docs[] = f( $this->configs->formats['command.about'], $command->getAbout() );

		foreach( $command->getOptions() As $option )
		{
			$name = [];
			$name[] = $this->buildOptionName( $option->name );

			// Append alias name if option has alias name.
			if( $option->alias !== Null ) $name[] = $this->buildOptionName( $option->alias );

			// Build option name and alias name.
			$docs[] = $this->buildJoinOptionName( $name, $option->type );

			foreach( $option->explain As $explain )
			{
				// Build option description.
				$docs[] = $this->buildOptionDescription( $explain );
			}

			// Build option is required.
			$docs[] = $this->buildRequireOption( $option->required );

			// Build option default value.
			if( $option->default !== Null ) $docs[] = $this->buildDefaultValue( $option->default );

			// Build option requirements if option has require another options.
			if( count( $option->requires ) >= 1 ) $docs[] = $this->buildRequirementOptions( $option->requires );

			// Build option example usage.
			if( count( $option->example ) >= 1 ) $docs[] = $this->buildExampleUsage( $option->example );
		}
		return( $docs );
	}

	/*
	 * Return formated of default option.
	 * 
	 * @access Private
	 * 
	 * @params Mixed $value
	 * 
	 * @return String
	 */
	private function buildDefaultValue( Mixed $value ): String
	{
		return( f( $this->configs->formats['command.option.single-info'], "default", $value ) );
	}

	/*
	 * Return formated of example usage of option.
	 * 
	 * @access Private
	 * 
	 * @params Array<String> $example
	 * 
	 * @return String
	 */
	private function buildExampleUsage( Array $example ): String
	{
		return( f( $this->configs->formats['command.option.multi-info'], "example", join( "\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20", $example ) ) );
	}

	/*
	 * Return format of joined option name.
	 * 
	 * @access Private
	 * 
	 * @params Array<String> $names
	 * 
	 * @return String
	 */
	private function buildJoinOptionName( Array $names, Util\Type $type ): String
	{
		return( f( $this->configs->formats['command.option.name-join'], join( "\x7c", $names ), $type->name ) );
	}

	/*
	 * Return formated of option description.
	 * 
	 * @access Private
	 * 
	 * @params String $description
	 * 
	 * @return String
	 */
	private function buildOptionDescription( String $decsription ): String
	{
		return( f( $this->configs->formats['command.option.explain'], $decsription ) );
	}

	/*
	 * Return formated of option name.
	 * 
	 * @access Private
	 * 
	 * @params String $name
	 * 
	 * @return String
	 */
	private function buildOptionName( String $name ): String
	{
		return( join( "", [ strlen( $name ) >= 2 ? "\x2d\x2d" : "\x2d", $name ] ) );
	}

	/*
	 * Return formated of required option.
	 * 
	 * @access Private
	 * 
	 * @params Bool $required
	 * 
	 * @return String
	 */
	private function buildRequireOption( Bool $required ): String
	{
		return( f( $this->configs->formats['command.option.single-info'], "require", $required ) );
	}

	/*
	 * Return formated of requirement options.
	 * 
	 * @access Private
	 * 
	 * @params Array<String> $requirements
	 * 
	 * @return String
	 */
	private function buildRequirementOptions( Array $requirements ): String
	{
		return( f( $this->configs->formats['command.option.single-info'], "linkeds", join( "\x2c\x20", $requirements ) ) );
	}

	/*
	 * Display command info for single command.
	 * 
	 * @access Public
	 * 
	 * @params CommandInterface $command
	 * 
	 * @return Void
	 */
	public function single( CommandInterface $command ): Void
	{
		// Generate command info.
		$docs = $this->builder( $command );

		// Display command group name.
		putcln( "" );
		putcln( $this->configs->formats['command.group-name'], $command->getGroup() );

		// Display command info.
		putcln( $this->configs->formats['command.info'], $command->getName(), implode( PHP_EOL, $docs ) );
		putcln( "" );
	}

}

?>
