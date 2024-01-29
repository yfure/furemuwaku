<?php

// If PHP run under CLI Server.
if( PHP_SAPI === "cli-server" ) {
	
	// Get URI Request.
	$uri = urldecode( parse_url( "{$_SERVER['HTTP_HOST']}/{$_SERVER['REQUEST_URI']}", PHP_URL_PATH ) ?? "" );
	
	// All request handle by index.php file.
	$_SERVER['SCRIPT_NAME'] = "/index.php";
	
	// If uri is only slash.
	if( $uri === "/" ) {

		// Here all the request lines will be handled by the index file.
		// require_once( sprintf( "%s%sindex.php", $_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR ) );
	}
	else {
		return( False );
	}
}
else {
	return( False );
}

?>