<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers\Annotation;

use function sprintf;

/**
 * @internal
 */
class GenericAnnotation extends Annotation
{

	/** @var string|null */
	private $parameters;

	/**
	 * @param string|null $parameters
	 * @param string|null $content
	 */
	public function __construct(string $name, int $startPointer, int $endPointer, $parameters, $content)
	{
		parent::__construct($name, $startPointer, $endPointer, $content);

		$this->parameters = $parameters;
	}

	/**
	 * @return string|null
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	public function isInvalid(): bool
	{
		return false;
	}

	public function export(): string
	{
		$exported = $this->name;

		if ($this->parameters !== null) {
			$exported .= sprintf('(%s)', $this->parameters);
		}

		if ($this->content !== null) {
			$exported .= sprintf(' %s', $this->content);
		}

		return $exported;
	}

}
