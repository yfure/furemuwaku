<?php

namespace Yume\Fure\CLI;

use Yume\Fure\Support\Data;
use Yume\Fure\Support\Design;

/*
 * CLICommand
 *
 * @package Yume\Fure\CLI
 *
 * @extends Yume\Fure\Support\Design\Singleton
 */
abstract class CLICommand extends Design\Singleton
{
	
	/*
	 * List of available commands.
	 *
	 * @access Private
	 *
	 * @values Yume\Fure\Support\Data\Data
	 */
    private Data\Data $options;
	
	/*
	 * @inherit Yume\Fure\Support\Design\Singleton
	 *
	 */
    protected function __construct( Array $options = [] )
    {
        $this->options = new Data\Data( $options );
    }
	
    public function getOption($name)
    {
        if (array_key_exists($name, $this->options))
        {
            return $this->options[$name];
        }
        return null;
    }
	
    public function has( $name )
    {
        return array_key_exists($name, $this->options);
    }
	
    public function set($name, $value)
    {
        $this->options[$name] = $value;
    }
    
}

?>