<?php

namespace Yume\Fure\Util\Array;

use ArrayAccess;
use Countable;
use Iterator;
use Serializable;
use Stringable;

/*
 * Arrayable
 *
 * @package Yume\Fure\Util\Arrayable
 *
 * @extends ArrayAccess
 */
interface Arrayable extends ArrayAccess, Countable, Iterator, Serializable, Serializable, Stringable
{
	
}

?>