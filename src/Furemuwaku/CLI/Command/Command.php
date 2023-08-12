<?php

namespace Yume\Fure\CLI\Command;

use Yume\Fure\Logger;

/*
 * Command
 *
 * @package Yume\Fure\CLI\Command
 */
abstract class Command implements CommandInterface
{
	
	/*
	 * About of command.
	 * 
	 * @access Protected
	 * 
	 * @values String
	 */
	protected ? String $about = Null;

	/*
	 * Goup name of command.
	 * 
	 * @access Protected
	 * 
	 * @values String
	 */
	protected String $group = "Yume";

	/*
	 * Name of command.
	 * 
	 * @access Protected
	 * 
	 * @values String
	 */
	protected String $name;

	/*
	 * Command options.
	 * 
	 * @access Protected
	 * 
	 * #[option-name: Option name]
	 * String "test" => [
	 *    #[option-explain: Explain option usage]
	 *    "explain" => Array|String "Explain option",
	 *    #[option-example: Example usage command with option]
	 *    "example" => Array|String "--test=Test",
	 *    #[option-require: Another option required when use this option]
	 *    "require" => Array<String>,
	 *    #[option-default: Default option value]
	 *    "default" => Mixed "Default",
	 *    #[option-type: Option value type]
	 *    "type" => Yume\Fure\Util\Type
	 * ]
	 * 
	 * @values Array
	 */
	protected Array $options = [];
	
}

?>