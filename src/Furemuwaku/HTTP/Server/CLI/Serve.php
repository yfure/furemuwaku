<?php

namespace Yume\Fure\HTTP\Server\CLI;

use Yume\Fure\CLI;
use Yume\Fure\CLI\Argument;
use Yume\Fure\CLI\Command;
use Yume\Fure\Logger;
use Yume\Fure\Util\File\Path;
use Yume\Fure\Util\Package;
use Yume\Fure\Util\Type;

/*
 * Serve
 *
 * @package Yume\Fure\HTTP\Server\CLI
 *
 * @extends Yume\Fure\CLI\Command\Command
 */
class Serve extends Command\Command
{
	
	/*
	 * @inherit Yume\Fure\CLI\Command\Command->about
	 *
	 */
	protected ? String $about = "Starting Yume PHP Development Server";
	
	/*
	 * @inherit Yume\Fure\CLI\Command\Command->name
	 *
	 */
	protected String $name = "serve";
	/*
	 * @inherit Yume\Fure\CLI\Command\Command->options
	 *
	 */
	protected Array $options = [
		"host" => [
			"type" => Type\Types::STRING,
			"about" => "Server hostname",
			"required" => False
		],
		"port" => [
			"type" => Type\Types::INTEGER,
			"about" => "Server port number",
			"required" => False
		],
		"phpb" => [
			"type" => Type\Types::STRING,
			"about" => "PHP Binary path",
			"default" => PHP_BINARY,
			"required" => False
		]
	];
	
	protected Array $optionAliases = [
		"host" => "h"
	];
	protected Array | String | Null $usage = [];
	
	/*
	 * @inherit Yume\Fure\CLI\Command\Command::__construct
	 *
	 */
	public function __construct( Command\Commands $commands, Logger\LoggerInterface $logger )
	{
		$this->options['host']['default'] = config( "app" )->server->host;
		$this->options['port']['default'] = config( "app" )->server->port;
		
		// Call parent constructor.
		parent::__construct( $commands, $logger );
	}
	
	/*
	 * @inherit Yume\Fure\CLI\Command\Command::run
	 *
	 */
	public function run( Argument\Argument $argument ): Void
	{
		$host = $argument->host->value ?? $this->options['host']->default;
		$port = $argument->port->value ?? $this->options['port']->default;
		$phpb = $argument->phpb->value ?? $this->options['phpb']->default;
		
		// Escaping Shell Argument.
		$phpb = escapeshellarg( $phpb );
		$droot = escapeshellarg( path( Path\Paths::PUBLIC->path() ) );
		$start = escapeshellarg( Package\Package::path( f( "{}/{}", __NAMESPACE__, "start.php" ) ) );
		
		echo PHP_EOL;
		
		CLI\CLI::puts( "  \x1b[1;48;5;111m\x1b[1;37mYume\x1b[0m\x1b[40m Server running on http://{}:{} {}", "\x1b[1;37m", $host, $port, str_repeat( PHP_EOL, 2 ) );
		CLI\CLI::puts( "  \x1b[1;37mPress Ctrl+C to stop the server {}\x1b[00m", "\x1b[1;37m", str_repeat( PHP_EOL, 2 ) );
		
		// Run server with execute the php binary.
		passthru( f( "{} -S {}:{} -t {} {}", $phpb, $host, $port, $droot, $start ), $status );
		
		if( $status )
		{}
		var_dump( $status );
	}
	
}

?>