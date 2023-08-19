<?php

namespace Yume\Fure\CLI\Command\Helper;

use Throwable;

use Yume\Fure\CLI\Argument;
use Yume\Fure\CLI\Command;
use Yume\Fure\IO\File;
use Yume\Fure\IO\Path;
use Yume\Fure\Util;
use Yume\Fure\Util\Reflect;

/*
 * Helper
 * 
 * @extends Yume\Fure\CLI\Command\Command
 * 
 * @package Yume\Fure\CLI\Command\Helper
 */
final class Helper extends Command\Command implements Command\CommandInterface
{

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
		"help" => [
			"type" => Util\Type::Bool,
			"alias" => "h",
			"explain" => "Display help",
			"example" => [
				"--help",
				"-h"
			]
		],
		"list" => [
			"type" => Util\Type::Bool,
			"alias" => "l",
			"explain" => "List of helpers",
			"example" => [
				"--list",
				"-l"
			]
		],
		"register" => [
			"type" => Util\Type::String,
			"alias" => "r",
			"explain" => "Register helper",
			"example" => "--register=helper"
		],
		"remove" => [
			"type" => Util\Type::String,
			"explain" => "Remove helper",
			"example" => "--remove=helper"
		]
	];

	/*
	 * @inherit Yume\Fure\CLI\Command\CommandInterface::exec
	 * 
	 */
	public function exec( Argument\Argument $argument ): Void
	{
		foreach( $this->options As $option )
		{
			try
			{
				if( $argument->has( $option->name, False ) &&
					$argument->has( $option->alias ?? "", False ) )
				{
					continue;
				}
				Reflect\ReflectMethod::invoke( $this, $option->name, [
					$this->getOptionValue( $argument, $option )
				]);
			}
			catch( Throwable $e )
			{
				echo  $e;
			}
		}
		\Yume\Fure\CLI\Stdout::Error->out( "*", "x" );
	}

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
	 */
	private function register( String $helper ): Void
	{
		$target = Util\Strings::format( "\x7b\x7d\x2e\x70\x68\x70", $helper );
		$target = Path\Paths::AppHelper->path( $target );
		$target = path( $target, True );
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
	private function remove( String $helper ): Void
	{}

}

?>