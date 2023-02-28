<?php

namespace Yume\Fure\HTTP\Server\CLI;

use Yume\Fure\CLI\{ Argument, Command };
use Yume\Fure\Logger;
use Yume\Fure\Support\Package;
use Yume\Fure\Support\Path;
use Yume\Fure\Util;

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
	protected ? String $about = "Starting Yume CLI Web Server";
	
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
			"type" => Util\Types::STRING,
			"required" => False
		],
		"port" => [
			"type" => Util\Types::INTEGER,
			"required" => False
		],
		"phpb" => [
			"type" => Util\Types::STRING,
			"default" => PHP_BINARY,
			"required" => False
		]
	];
	
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
		$droot = escapeshellarg( path( Path\PathName::PUBLIC->path() ) );
		$start = escapeshellarg( Package\Package::path( f( "{}/{}", __NAMESPACE__, "start.php" ) ) );
		
		// Run server with execute the php binary.
		passthru( f( "{} -S {}:{} -t {} {}", $phpb, $host, $port, $droot, $start ), $status );
		
		if( $status )
		{}
		var_dump( $status );
	}
	
}

?>