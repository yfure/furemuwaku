<?php

namespace Yume\Fure\Locale\Language;

use Yume\Fure\Support\Data;

/*
 * Language
 *
 * @package Yume\Fure\Locale\Language
 *
 * @extends Yume\Fure\Support\Data\Data
 */
class Language extends Data\Data
{
	
	/*
	 * @inherit Yume\Fure\Support\Data\Data
	 *
	 */
	final public function __construct( public Readonly String $language, Array | Data\DataInterface $translation )
	{
		parent::__construct( $translation );
	}
	
	/*
	 * Get language name.
	 *
	 * @access Public
	 *
	 * @return String
	 */
	final public function getLanguage(): String
	{
		return( $this->language );
	}
	
}

?>