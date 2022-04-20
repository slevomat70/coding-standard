<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers\Annotation;

use LogicException;
use function sprintf;
use function strlen;
use function substr;

/**
 * @internal
 */
abstract class Annotation
{

	/** @var string */
	protected $name;

	/** @var int */
	protected $startPointer;

	/** @var int */
	protected $endPointer;

	/** @var string|null */
	protected $content;

	abstract public function isInvalid(): bool;

	abstract public function export(): string;

	/**
	 * @param string|null $content
	 */
	public function __construct(string $name, int $startPointer, int $endPointer, $content)
	{
		$this->name = $name;
		$this->startPointer = $startPointer;
		$this->endPointer = $endPointer;
		$this->content = $content;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getStartPointer(): int
	{
		return $this->startPointer;
	}

	public function getEndPointer(): int
	{
		return $this->endPointer;
	}

	/**
	 * @return string|null
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @param string $description
	 */
	protected function fixDescription($description): string
	{
		return substr($this->getContent(), -strlen($description));
	}

	/**
	 * @return void
	 */
	protected function errorWhenInvalid()
	{
		if ($this->isInvalid()) {
			throw new LogicException(sprintf('Invalid %s annotation.', $this->name));
		}
	}

}
