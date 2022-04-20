<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use function sprintf;
use function strtolower;

/**
 * @internal
 */
class UseStatement
{

	const TYPE_CLASS = ReferencedName::TYPE_CLASS;
	const TYPE_FUNCTION = ReferencedName::TYPE_FUNCTION;
	const TYPE_CONSTANT = ReferencedName::TYPE_CONSTANT;

	/** @var string */
	private $nameAsReferencedInFile;

	/** @var string */
	private $normalizedNameAsReferencedInFile;

	/** @var string */
	private $fullyQualifiedTypeName;

	/** @var int */
	private $usePointer;

	/** @var string */
	private $type;

	/** @var string|null */
	private $alias;

	/**
	 * @param string|null $alias
	 */
	public function __construct(
		string $nameAsReferencedInFile,
		string $fullyQualifiedClassName,
		int $usePointer,
		string $type,
		$alias
	)
	{
		$this->nameAsReferencedInFile = $nameAsReferencedInFile;
		$this->normalizedNameAsReferencedInFile = self::normalizedNameAsReferencedInFile($type, $nameAsReferencedInFile);
		$this->fullyQualifiedTypeName = $fullyQualifiedClassName;
		$this->usePointer = $usePointer;
		$this->type = $type;
		$this->alias = $alias;
	}

	public function getNameAsReferencedInFile(): string
	{
		return $this->nameAsReferencedInFile;
	}

	public function getCanonicalNameAsReferencedInFile(): string
	{
		return $this->normalizedNameAsReferencedInFile;
	}

	public function getFullyQualifiedTypeName(): string
	{
		return $this->fullyQualifiedTypeName;
	}

	public function getPointer(): int
	{
		return $this->usePointer;
	}

	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @return string|null
	 */
	public function getAlias()
	{
		return $this->alias;
	}

	public function isClass(): bool
	{
		return $this->type === self::TYPE_CLASS;
	}

	public function isConstant(): bool
	{
		return $this->type === self::TYPE_CONSTANT;
	}

	public function isFunction(): bool
	{
		return $this->type === self::TYPE_FUNCTION;
	}

	/**
	 * @param $this $that
	 */
	public function hasSameType($that): bool
	{
		return $this->type === $that->type;
	}

	/**
	 * @param string $type
	 * @param string $name
	 */
	public static function getUniqueId($type, $name): string
	{
		$normalizedName = self::normalizedNameAsReferencedInFile($type, $name);

		if ($type === self::TYPE_CLASS) {
			return $normalizedName;
		}

		return sprintf('%s %s', $type, $normalizedName);
	}

	/**
	 * @param string $type
	 * @param string $name
	 */
	public static function normalizedNameAsReferencedInFile($type, $name): string
	{
		if ($type === self::TYPE_CONSTANT) {
			return $name;
		}

		return strtolower($name);
	}

	/**
	 * @param string $type
	 * @return string|null
	 */
	public static function getTypeName($type)
	{
		if ($type === self::TYPE_CONSTANT) {
			return 'const';
		}

		if ($type === self::TYPE_FUNCTION) {
			return 'function';
		}

		return null;
	}

}
