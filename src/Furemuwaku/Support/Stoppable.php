<?php

namespace Yume\Fure\Support;

use Yume\Fure\Error;

/*
 * Stoppable
 *
 * Throw for stop execution program (Inspirated by Python StopIteration exception).
 *
 * @extends Yume\Fure\Error\YumeError
 *
 * @package Yume\Fure\Support
 */
final class Stoppable extends Error\YumeError {
	
	/*
	 * Stopped program.
	 *
	 * @access Public Static
	 *
	 * @values Int
	 */
	public const STOPPED = -63622;
	
	/*
	 * @inherit Yume\Fure\Error\YumeError
	 *
	 */
	protected static Array $flags = [
		Stoppable::class => [
			self::STOPPED => "Program execution has stopped on {}"
		]
	];
	
	/*
	 * Construct method of class Stoppable.
	 *
	 * @access Public Initialize
	 *
	 * @params Mixed $value
	 *
	 * @return Void
	 */
	public function __construct( public Readonly Mixed $value = Null ) {
		$called = $this->getTrace()[0];
		parent::__construct( $called['function'] ?? $called['class'] ?? "Unknown source", self::STOPPED );
	}
	
	/*
	 * Returns the return value from the program.
	 *
	 * @access Public
	 *
	 * @return Mixed
	 */
	public function getValue(): Mixed {
		return( $this )->value;
	}
	
	/*
	 * Return if program has returned value.
	 *
	 * @access Public
	 *
	 * @params Bool $optional
	 *
	 * @return Bool
	 */
	public function hasValue( ? Bool $optional = Null ): Bool {
		return( $optional !== Null ? $this->hasValue() === $optional : $this->value !== Null );
	}
	
}

?>