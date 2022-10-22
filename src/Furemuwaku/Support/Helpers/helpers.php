<?php

use Yume\App\Models;
use Yume\App\Views;

use Yume\Fure\App;
use Yume\Fure\HTTP;
use Yume\Fure\IO;
use Yume\Fure\Seclib;
use Yume\Fure\Support;
use Yume\Fure\Util;
use Yume\Fure\View;

function config( String $name, Bool $reImport = False ): App\Config\Config
{
	
}

function f( String $string, Mixed ...$format ): String
{
	return( Util\Str::fmt( $string, ...$format ) );
}

function puts( String $string, Mixed ...$format ): Void
{
	echo( Util\Str::fmt( $string, ...$format ) );
}

?>