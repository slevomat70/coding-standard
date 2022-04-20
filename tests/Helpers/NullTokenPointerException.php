<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use Exception;
use Throwable;

class NullTokenPointerException extends Exception
{

	/**
	 * @param \Throwable|null $previous
	 */
	public function __construct($previous = null)
	{
		parent::__construct('', 0, $previous);
	}

}
