<?php

namespace Yume\Fure\CLI\Command\Commands;

use Yume\Fure\CLI\Argument;
use Yume\Fure\CLI\Command;
use Yume\Fure\Config;
use Yume\Fure\Error;
use Yume\Fure\IO\Path;
use Yume\Fure\Logger;
use Yume\Fure\Support;
use Yume\Fure\Util;

/*
 * Server
 * 
 * Server Command Line Utility
 * 
 * @extends Yume\Fure\CLI\Command\Command
 * 
 * @package Yume\Fure\CLI\Command\Commands
 */
final class Server extends Command\Command implements Command\CommandInterface
{

	/*
	 * @inherit Yume\Fure\CLI\Command\Command::$about
	 * 
	 */
	protected ? String $about = "Command Line Interface Server utility";

	/*
	 * @inherit Yume\Fure\CLI\Command\Command::$name
	 * 
	 */
	protected String $name = "server";

	/*
	 * @inherit Yume\Fure\CLI\Command\Command::__construct
	 * 
	 */
	public function __construct( Command\Commands $commands, Config\Config $configs, Logger\LoggerInterface $logger )
	{
		$this->options = [
			"binary" => [
				"type" => Util\Type::String,
				"default" => $configs->binary ?? PHP_BINARY,
				"include" => True,
				"requires" => [
					"host",
					"port",
					"serve"
				]
			],
			"host" => [
				"type" => Util\Type::String,
				"default" => $configs->server->host ?? "127.0.0.1",
				"include" => True,
				"requires" => [
					"binary",
					"port",
					"serve"
				]
			],
			"port" => [
				"type" => Util\Type::Int,
				"default" => $configs->server->port ?? 8004,
				"include" => True,
				"requires" => [
					"binary",
					"host",
					"serve"
				]
			],
			"serve" => [
				"type" => Util\Type::String,
				"default" => "cli-server",
				"include" => False,
				"example" => [
					"server --serve=openswoole #[@require ext:openswoole]",
					"server --serve=cli-server #[@default]"
				],
				"requires" => [
					"binary",
					"host",
					"port"
				],
				"implement" => "serve"
			]
		];
		parent::__construct( $commands, $configs, $logger );
	}

	/*
	 * @inherit Yume\Fure\CLI\Command\CommandInterface::exec
	 * 
	 */
	public function exec( Argument\Argument $argument ): Void
	{
		if( $argument->has( "serve", False ) )
		{
			foreach( [ "binary", "host", "port" ] As $option )
			{
				if( $argument->has( $option ) && $argument->has( "serve", False ) ) throw new Command\CommandOptionRequireError( "Option {$option} require option serve", 0 );
			}
		}
		parent::exec( $argument );
	}

	/*
	 * Run application server.
	 * 
	 * @access Private
	 * 
	 * @params String $serve
	 * @params String $host
	 * @params Int $port
	 * 
	 * @return Void
	 */
	private function serve( String $serve, ? String $binary, String $host, Int $port ): Void
	{
		switch( strtolower( $serve ) )
		{
			case "cli-server": $this->builtin( $binary, $host, $port ); break;
			case "openswoole": break;
			default:
				throw new Error\UnexpectedError( "Unsupported web server \"{$serve}\"" );
		}
	}

	/*
	 * Starting built-in php web server.
	 * 
	 * @access Private
	 * 
	 * @params String $binary
	 * @params String $host
	 * @params Int $port
	 * 
	 * @return Void
	 */
	private function builtin( ? String $binary, String $host, Int $port ): Void
	{
		// Escaping Shell Argument.
		$phpb = escapeshellarg( $binary ?? $this->options['binary']->default );
		$droot = escapeshellarg( path( Path\Paths::Public->path() ) );
		$start = escapeshellarg( Support\Package::path( $this->configs->start ) );

		putcln( "Yume Server started on http:/\e[0m/{}:{}", $host, $port );
		putcln( "Press CTRL+C to stop the server{}", PHP_EOL );

		// Run server with execute the php binary.
		passthru( f( "{} -S {}:{} -t {} {}", $phpb, $host, $port, $droot, $start ), $status );
	}

}

?>