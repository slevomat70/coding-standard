<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use Exception;
use Throwable;
use function sprintf;

class UnsupportedKeywordException extends Exception
{

	/**
	 * @param \Throwable|null $previous
	 */
	public function __construct(string $keyword, $previous = null)
	{
		parent::__construct(sprintf('"%s" is not supported.', $keyword), 0, $previous);
	}

}
