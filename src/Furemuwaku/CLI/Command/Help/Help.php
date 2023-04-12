<?php

namespace Yume\Fure\CLI\Command\Help;

use Yume\Fure\CLI;
use Yume\Fure\CLI\Argument;
use Yume\Fure\CLI\Command;

/*
 * Help
 *
 * @package Yume\Fure\CLI\Command\Help
 *
 * @extends Yume\Fure\CLI\Command\Command
 */
final class Help extends Command\Command
{
	
	/*
	 * @inherit Yume\Fure\CLI\Command\Command->about
	 *
	 */
	protected ? String $about = "Show this info";
	
	/*
	 * @inherit Yume\Fure\CLI\Command\Command->name
	 *
	 */
	protected String $name = "help";
	
	/*
	 * Output formats.
	 *
	 * @access Private
	 *
	 * @values Array
	 */
	private Array $format = [
		"command.group-name" => "\x20\x20\x1b[1;35m\xc2\xb7\x20\x1b[1;32m\x7b\x7d\x1b[0m\x7b\x73\x74\x72\x5f\x72\x65\x70\x65\x61\x74\x28\x2b\x29\x7d",
		"command.name" => "\x20\x20\x20\x20\x1b[2;37m\xc2\xb7\x1b[00m\x20\x1b[1;37m\x7b\x7d\x20\x1b[0m\x7b\x7d",
		"command.option-name" => "\x20\x20\x20\x20\x20\x20\x7b\x7d\x1b[1;32m\x7b\x7d",
		"command.option-vtype" => "\x20\x20\x20\x20\x20\x20\x20\x20\x7b\x7d\x1b[1;32m\x7b\x7d\x1b[1;38;5;214m\x3a\x20\x1b[1;38;5;193mtype\x1b[1;38;5;214m\x3a\x20\x1b[1;38;5;117m\x7b\x7d",
		"command.option-value" => "\x20\x20\x20\x20\x20\x20\x20\x20\x7b\x7d\x1b[1;32m\x7b\x7d\x1b[1;38;5;214m\x3a\x20\x1b[1;38;5;193mvalue\x1b[1;38;5;214m\x3a\x20\x1b[1;38;5;111m\x7b\x7d",
		"command.option-about" => "\x20\x20\x20\x20\x20\x20\x20\x20\x7b\x7d\x1b[1;32m\x7b\x7d\x1b[1;38;5;214m\x3a\x20\x1b[1;38;5;193mabout\x1b[1;38;5;214m\x3a\x20\x1b[1;37m\x7b\x7d",
	];
	
	/*
	 * @inherit Yume\Fure\CLI\Command\Command::run
	 *
	 */
	public function run( Argument\Argument $argument ): Void
	{
		puts( "\x1b[1;37mThe Yume Framework \x1b[1;32mv{0}{1}\x1b[1;37mThe Sasayaki Command Line Interface Tool\x1b[00m{1}{1}", env( "APP_VERSION", "3.0.6" ), PHP_EOL );
		
		// { Yume: { help: {}, serve: {} } }
		$group = [];
		
		$this->commands->getAll()->map(
			
			/*
			 * Group commands by group name.
			 *
			 * @params Int $i
			 * @params Int|String $name
			 * @params Yume\Fure\CLI\Command\CommandInterface $command
			 *
			 * @return Void
			 */
			function( Int $i, Int | String $name, Command\CommandInterface $command ) use( &$group ): Void
			{
				// Get command group name.
				$gname = $command->getGroup();
				
				$group[$gname] ??= [];
				$group[$gname][$name] = [];
				
				// Set command name.
				$group[$gname][$name][] = f( $this->format['command.name'], $command->getName(), $command->getAbout() ?? "" );
				
				// If command has Options.
				if( $options = $command->getOptions() )
				{
					// Mapping command options.
					foreach( $options As $option )
					{
						// Shortoption.
						$long = "\x1b[1;38;5;190m\x2d";
						
						// If command option is long option.
						if( $option->long ) $long .= "\x2d";
						
						// Command option name.
						$group[$gname][$name][] = f( $this->format['command.option-name'], $long, $option->name );
						
						// Command option value type.
						$group[$gname][$name][] = f( $this->format['command.option-vtype'], $long, $option->name, $option->type->name );
						
						// Command option default value.
						$group[$gname][$name][] = f( $this->format['command.option-value'], $long, $option->name, $option->default ?? "None" );
						
						// Command option about.
						$group[$gname][$name][] = f( $this->format['command.option-about'], $long, $option->name, $option->about ?? "Unknown option usage" );
					}
				}
				if( $usage = $command->getUsage() )
				{}
				$group[$gname][$name][] = "";
				$group[$gname][$name] = implode( PHP_EOL, $group[$gname][$name] );
			}
		);
		
		// Mapping grouped commands.
		foreach( $group As $gname => $commands )
		{
			// Display group name.
			puts( $this->format['command.group-name'], $gname, [ PHP_EOL, 1 ] );
			
			// Display commands and command options.
			puts( "\x7b\x7d\x7b\x7d", implode( array_values( $commands ) ), str_repeat( PHP_EOL, 2 ) );
		}
		CLI\CLI::puts( "usage: sasayaki command --option arguments {str_repeat(+)}", "\x1b[1;37m", [ PHP_EOL, 2 ] );
	}
	
}

?>