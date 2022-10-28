<?php

namespace Yume\Fure\App;

use Throwable;

use Yume\Fure\Error;
use Yume\Fure\IO;
use Yume\Fure\Support;
use Yume\Fure\Util;
use Yume\Fure\View;

/*
 * App
 *
 * @extends Yume\Fure\Support\Design\Singleton
 *
 * @package Yume\Fure\App
 */
final class App extends Support\Design\Creational\Singleton
{
	
	/*
	 * @inherit Yume\Fure\App\Design\Singleton
	 *
	 */
	protected function __construct()
	{
		try
		{
			Support\Env\Env::self()->load();
		}
		catch( Throwable $e )
		{
			exit( path( remove: True, path: f( "<pre>{}: {} in file {} on line {}\n{}", ...[
				$e::class,
				$e->getMessage(),
				$e->getFile(),
				$e->getLine(),
				$e->getTrace()
			])));
		}
	}
	
	/*
	 * Launch the application.
	 *
	 * @access Public
	 *
	 * @return Void
	 */
	public function run(): Void
	{
		// Check if application context has ben defined.
		if( defined( "YUME_CONTEXT" ) )
		{
			// Define application environment.
			define( "ENVIRONMENT", env( "ENVIRONMENT", "development" ) );
			
			// If appication environment name is invalid.
			if( ENVIRONMENT !== "development" && 
				ENVIRONMENT !== "prpduction" )
			{
				throw new Error\LogicError( "Unknown application environment named." );
			}
			
			try
			{
				// Import application configuration based environment.
				Support\Package\Package::import( f( "app/Runtime/{}.php", ENVIRONMENT ) );
			}
			catch( Error\ModuleError $e )
			{
				// Check if Throwable thrown is ModuleError instance.
				if( $e Instanceof Error\ImportError )
				{
					throw new Error\RuntimeError( f( "Something error when import app/Runtime/{}.php", ENVIRONMENT ), 0, $e );
				}
				throw new Error\RuntimeError( "Unknown application environment.", 0, $e );
			}
			
			// Run the entire service provider class.
			Support\Services\Services::self();
			
			// Testing.
			Support\Package\Package::import( "app/Tests/Test.php" );
		}
		else {
			throw new Error\LogicError( "Unknown application context." );
		}
	}
	
}

?>