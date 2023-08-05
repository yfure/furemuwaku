<?php

namespace Yume\Fure\IO\Buffer;

use Yume\Fure\IO\Stream;
use Yume\Fure\Support;
use Yume\Fure\Util\Array;

/*
 * Buffer
 *
 * @extends Yume\Fure\Support\Singleton
 *
 * @package Yume\Fure\IO\Buffer
 */
class Buffer extends Support\Singleton
{
	
	/*
	 * Output buffer container.
	 *
	 * @access Private
	 *
	 * @values Array<>
	 */
	private Array\Lists $buffer;
	
	/*
	 * Current output buffer level.
	 *
	 * @access Private
	 *
	 * @values Int
	 */
	private ? Int $level = Null;
	
	/*
	 * @inherit Yume\Fure\Support\Singleton
	 *
	 */
	protected function __construct( ? Callable $callback = Null )
	{
		// Get all output buffer statuses.
		$this->buffer = new Array\Lists( ob_get_status( True ) );
		$this->buffer->map(
			
			/*
			 * Mapping all buffers.
			 *
			 * @params Int $i
			 * @params Int $index
			 * @params Yume\Fure\Util\Array\Associative $buffer
			 *
			 * @return Void
			 */
			function( Int $i, Int $index, Array\Associative $buffer ): Void
			{
				// Set current buffer level status.
				$this->level = $index;
				
				// Get buffer contents.
				$buffer->contents = ob_get_clean();
			}
		);
	}
	
}

?>