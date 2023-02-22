<?php

namespace Yume\Fure\Logger\Handler;

use Yume\Fure\Locale;
use Yume\Fure\Locale\DateTime;
use Yume\Fure\Logger;
use Yume\Fure\Support\Data;
use Yume\Fure\Support\File;
use Yume\Fure\Util;

/*
 * FileHandler
 *
 * @package Yume\Fure\Logger\Handler
 *
 * @extends Yume\Fure\Logger\Handler\BaseHandler
 */
class FileHandler extends BaseHandler
{
	
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
	 * @inherit Yume\Fure\Logger\Handler\BaseHandler
	 *
	 */
	public function __construct( Data\DataInterface $configs )
	{
		// Call parent constructor.
		parent::__construct( $configs );
		
		// Set file path.
		$this->path = $configs->path;
		
		// Set file extension.
		$this->extension = ltrim( $configs->extension, "." );
		
		// Set file permission.
		$this->permission = $configs->permission;
		
		// Set file name.
		$this->name = Util\Str::fmt( "{1}/{0:lower}-log-{3}.{2}", ...[
			
			// Get application name.
			env( "APP_NAME", "Yume" ),
			
			$this->path,
			$this->extension,
			Locale\Locale::getDateTime()->format( "d-m-Y" )
		]);
	}
	
	/*
	 * @inherit Yume\Fure\Logger\Handler\HandlerInterface
	 *
	 */
	public function handle( Logger\LoggerLevel $level, String $message ): Bool
	{
		$fnew = False;
		$stack = "";
		
		// Check if file is not exists.
		if( File\File::exists( $this->name ) === False )
		{
			// Set new file.
			$fnew = True;
			
			// Check if file extension is php.
			if( strtolower( $this->extension ) === "php" )
			{
				// Add protection for php file.
				$stack = "<?php defined( \"BASE_PATH\" ) || exit( \"No direct script access allowed!\" ) ?>\n\n";
			}
		}
		
		// Create file open stream.
		if( $fopen = File\File::open( $this->name, "ab+" ) )
		{
			// Create new DateTime instance.
			$date = new DateTime\DateTime( "now" );
			
			// Get date timestamp.
			$dates = $date->getTimestamp();
			
			// Get datetime format.
			$datef = $date->format( $this->dateTimeFormat );
			
			// Get date timezone.
			$datez = $date->getTimezone()->getName();
			
			// Format stack.
			$stack = Util\Str::fmt( "{+#stack}[{+#level}][{+#timezone}][{+#timestamp}] {+#dateformat} - {+#message}\n", $stack, $level->value, $datez, $dates, $datef, $message );
			
			// Write file.
			$fwrite = File\File::write( $this->name, fdata: $stack, context: $fopen );
			
			// Check if file is new.
			if( $fnew )
			{
				// Changes file mode.
				File\File::chmod( $this->name, $this->permission );
			}
			
			// Return result from file write.
			return( $fwrite );
		}
		return( False );
	}
	
}

?>