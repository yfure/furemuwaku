<?php

namespace Yume\Fure\CLI\Command\Help;

use Yume\Fure\CLI;
use Yume\Fure\CLI\Argument;
use Yume\Fure\CLI\Command;
use Yume\Fure\Util\File;
use Yume\Fure\Util\File\Path;
use Yume\Fure\Util\Json;
use Yume\Fure\Util\Type;

/*
 * Helper
 *
 * @package Yume\Fure\CLI\Command\Help
 *
 * @extends Yume\Fure\CLI\Command\Command
 */
final class Helper extends Command\Command
{
	
	/*
	 * @inherit Yume\Fure\CLI\Command\Command->about
	 *
	 */
	protected ? String $about = "Manage application helper";
	
	/*
	 * @inherit Yume\Fure\CLI\Command\Command->name
	 *
	 */
	protected String $name = "helper";
	
	/*
	 * @inherit Yume\Fure\CLI\Command\Command->name
	 *
	 */
	protected Array $options = [
		"list" => [
			"type" => Type\Types::BOOLEAN,
			"about" => "List all helpers"
		],
		"register" => [
			"type" => Type\Types::STRING,
			"about" => "Register helper"
		],
		"remove" => [
			"type" => Type\Types::STRING,
			"about" => "Remove helper"
		]
	];
	
	public function register( Argument\ArgumentValue $helper ): Void
	{
		// Create helper filename.
		$file = Path\Paths::AppHelper->path( f( "{}.php", $helper->value ) );
		$load = path( $file, True );
		
		// Check if helper is exists.
		if( File\File::exists( $file ) )
		{
			// Read composer file.
			$json = File\File::json( "composer.json", True );
			
			// Check if helper has registered previously.
			if( in_array( $load, $json['autoload']['files'] ??= [] ) )
			{
				CLI\CLI::puts( "\e[1;37mHelper \"{}\" has registered previously{}", "\e[1;37m", $helper->value, PHP_EOL );
			}
			else {
				
				// Added helper into file autoloads.
				$json['autoload']['files'][] = $load;
				
				// Saving composer file.
				File\File::write( "composer.json", Json\Json::encode( $json, JSON_PRETTY_PRINT ) );
				
				// Dumping composer file.
				system( "composer dump" );
				
				CLI\CLI::puts( "\e[1;37mHelper \"{}\" successfull registered{}", "\e[1;37m", $helper->value, PHP_EOL );
			}
		}
		else {
			CLI\CLI::puts( "\e[1;37mNo helper named \"{}\"{}", "\e[1;37m", $helper->value, PHP_EOL );
		}
	}
	
	public function remove( Argument\ArgumentValue $helper ): Void
	{
		// Create helper filename.
		$file = Path\Paths::AppHelper->path( f( "{}.php", $helper->value ) );
		$load = path( $file, True );
	}
	
	/*
	 * @inherit Yume\Fure\CLI\Command\Command::run
	 *
	 */
	public function run( Argument\Argument $argument ): Void
	{
		if( $argument->register ) $this->register( $argument->register );
		if( $argument->remove ) $this->register( $argument->remove );
		if( $argument->list )
		{
			// Read composer file.
			$json = File\File::json( "composer.json", True );
			$files = $json['autoload']['files'] ?? [];
			
			// Mapping autoload files.
			foreach( $files As $i => $file )
			{
				if( Path\Paths::AppHelper->is( $file ) )
				{
					echo $file;
					echo PHP_EOL;
				}
			}
			echo PHP_EOL;
		}
	}
	
}

?>