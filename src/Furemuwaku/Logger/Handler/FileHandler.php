<?php

namespace Yume\Fure\Logger\Handler;

use Yume\Fure\Config;
use Yume\Fure\IO\File;
use Yume\Fure\IO\Path;
use Yume\Fure\Locale\DateTime;
use Yume\Fure\Logger;
use Yume\Fure\Util;

/*
 * FileHandler
 *
 * @package Yume\Fure\Logger\Handler
 *
 * @extends Yume\Fure\Logger\LoggerHandler
 */
class FileHandler extends Logger\LoggerHandler {
	
	/*
	 * File log extension name.
	 *
	 * @access Protected Readonly
	 *
	 * @values String
	 */
	protected Readonly String $extension;
	
	/*
	 * File log name.
	 *
	 * @access Protected Readonly
	 *
	 * @values String
	 */
	protected Readonly String $name;
	
	/*
	 * File log path name.
	 *
	 * @access Protected Readonly
	 *
	 * @values String
	 */
	protected Readonly String $path;
	
	/*
	 * File log permission.
	 *
	 * @access Protected Readonly
	 *
	 * @values Int
	 */
	protected Readonly Int $permission;
	
	/*
	 * @inherit Yume\Fure\Logger\LoggerHandler::__construct
	 *
	 */
	public function __construct( Config\Config $configs ) {
		$this->path = $configs->path;
		$this->extension = ltrim( $configs->extension, "." );
		$this->permission = $configs->permission;
		$this->name = Util\Strings::format( "{1}/{0:lower}-log-{3}.{2}", ...[ env( "APP_NAME", "Yume" ), $this->path, $this->extension, datetime()->format( "d-m-Y" ) ]);
		if( Path\Path::exists( $this->path, False ) ) {
			Path\Path::make( $this->path );
		}
		parent::__construct( $configs );
	}
	
	/*
	 * @inherit Yume\Fure\Logger\Handler\HandlerInterface
	 *
	 */
	public function handle( Logger\LoggerLevel $level, String $message ): Bool {
		$fnew = False;
		$stack = "";
		if( File\File::exists( $this->name ) === False ) {
			$fnew = True;
			if( strtolower( $this->extension ) === "php" ) {
				$stack = "<?php defined( \"BASE_PATH\" ) || exit( \"No direct script access allowed!\" ) ?>\n\n";
			}
		}
		if( $fopen = File\File::open( $this->name, "ab+" ) ) {
			
			$date = new DateTime\DateTime( "now" );
			$dates = $date->getTimestamp();
			$datef = $date->format( $this->dateTimeFormat );
			$datez = $date->getTimezone()->getName();
			$stack = Util\Strings::format( "{+#stack}[{+#level}][{+#timestamp}][{+#timezone}] {+#dateformat} - {+#message}\n", $stack, $level->value, $dates, $datez, $datef, $message );
			$fwrite = File\File::write( $this->name, fdata: $stack, context: $fopen );
			if( $fnew ) {
				chmod( Path\Path::path( $this->name ), $this->permission );
			}
			return( $fwrite );
		}
		return( False );
	}
	
}

?>