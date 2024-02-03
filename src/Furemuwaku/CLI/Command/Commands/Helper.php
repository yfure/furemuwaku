<?php

namespace Yume\Fure\CLI\Command\Commands;

use Yume\Fure\CLI;
use Yume\Fure\CLI\Argument;
use Yume\Fure\CLI\Command;
use Yume\Fure\Error;
use Yume\Fure\IO\File;
use Yume\Fure\IO\Path;
use Yume\Fure\Util;
use Yume\Fure\Util\Json;
use Yume\Fure\Util\Reflect;

/*
 * Helper
 * 
 * @extends Yume\Fure\CLI\Command\Command
 * 
 * @package Yume\Fure\CLI\Command\Commands
 */
final class Helper extends Command\Command implements Command\CommandInterface {

	/*
	 * @inherit Yume\Fure\CLI\Command\Command::$about
	 * 
	 */
	protected ? String $about = "Manage application helpers";

	/*
	 * @inherit Yume\Fure\CLI\Command\Command::$name
	 * 
	 */
	protected String $name = "helper";

	/*
	 * @inherit Yume\Fure\CLI\Command\Command::$options
	 * 
	 */
	protected Array $options = [
		"list" => [
			"type" => Util\Type::Bool,
			"alias" => "l",
			"explain" => "List of helpers",
			"example" => [],
			"implement" => "list"
		],
		"register" => [
			"type" => Util\Type::String,
			"alias" => "r",
			"explain" => "Register helper",
			"example" => "--register=helper",
			"implement" => "register"
		],
		"remove" => [
			"type" => Util\Type::String,
			"explain" => "Remove helper",
			"example" => "--remove=helper",
			"implement" => "remove"
		]
	];

	public function list(): Void {}

	/*
	 * Register new helper.
	 * 
	 * @access Private
	 * 
	 * @params String $helper
	 * 
	 * @return Void
	 * 
	 * @throws Yume\Fure\Error\ModuleNotFoundError
	 *  When the helper is not found.
	 */
	private function register( String $helper ): Void {
		$target = Util\Strings::format( "\x7b\x7d\x2e\x70\x68\x70", $helper );
		$target = Path\Paths::AppHelper->path( $target );
		$target = path( $target, True );

		if( File\File::exists( $target ) ) {
			$json = File\File::json( "composer.json", True );
			if( in_array( $target, $json['autoload']['files'] ??= [] ) ) {
				CLI\Console::info( "has registered previously", $helper );
			}
			else {
				$json['autoload']['files'][] = $target;
				File\File::write( "composer.json", Json\Json::encode( $json, JSON_PRETTY_PRINT ) );
				CLI\Console::success( "successfull registered", $helper );
				CLI\Console::warning( "please dumping your composer" );
			}
		}
		else {
			throw new Error\ModuleNotFoundError( $helper );
		}
	}

	/*
	 * Remove helper.
	 * 
	 * @access Private
	 * 
	 * @params String $helper
	 * 
	 * @return Void
	 */
	private function remove( String $helper ): Void {
		$target = Util\Strings::format( "\x7b\x7d\x2e\x70\x68\x70", $helper );
		$target = Path\Paths::AppHelper->path( $target );
		$target = path( $target, True );
		$json = File\File::json( "composer.json", True );
		
		if( in_array( $target, $json['autoload']['files'] ??= [] ) ) {
			$index = array_search( $target, $json['autoload']['files'] );
			unset( $json['autoload']['files'][$index] );
			File\File::write( "composer.json", Json\Json::encode( $json, JSON_PRETTY_PRINT ) );
			CLI\Console::success( "has removed", $helper );
			CLI\Console::warning( "please dumping your composer" );
		}
		else {
			CLI\Console::success( "no such helper registered", $helper );
		}
	}

}

?>
