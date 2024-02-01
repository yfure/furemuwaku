<?php

namespace Yume\Fure\CLI;

use Yume\Fure\Util;

/*
 * Stdout
 * 
 * @backeds String
 * 
 * @package Yume\Fure\CLI
 */
enum Stdout: String {

	use \Yume\Fure\Util\Backed;

	/*
	 * Indicate if output is on level error.
	 * 
	 * @access Public Static
	 * 
	 * @values String
	 */
	case Error = "\e[1;37m\e[1;48;5;196m";

	/*
	 * Indicate if output is on level info.
	 * 
	 * @access Public Static
	 * 
	 * @values String
	 */
	case Info = "\e[1;37m\e[1;48;5;33m";

	/*
	 * Indicate if output is default output.
	 * 
	 * @access Public Static
	 * 
	 * @values String
	 */
	case Log = "\e[1;37m\e[1;48;5;250m";

	/*
	 * Indicate if output is on level success.
	 * 
	 * @access Public Static
	 * 
	 * @values String
	 */
	case Success = "\e[1;37m\e[1;48;5;76m";

	/*
	 * Indicate if output is on level warning.
	 * 
	 * @access Public Static
	 * 
	 * @values String
	 */
	case Warning = "\e[1;37m\e[1;48;5;178m";

}

?>