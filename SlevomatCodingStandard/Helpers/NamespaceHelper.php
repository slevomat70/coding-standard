<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use function array_reverse;
use function array_slice;
use function count;
use function explode;
use function implode;
use function in_array;
use function ltrim;
use function sprintf;
use function strpos;
use const T_NAME_FULLY_QUALIFIED;
use const T_NAMESPACE;
use const T_NS_SEPARATOR;

/**
 * Terms "unqualified", "qualified" and "fully qualified" have the same meaning as described here:
 * http://php.net/manual/en/language.namespaces.rules.php
 *
 * "Canonical" is a fully qualified name without the leading backslash.
 *
 * @internal
 */
class NamespaceHelper
{

	const NAMESPACE_SEPARATOR = '\\';

	/**
	 * @return int[]
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 */
	public static function getAllNamespacesPointers($phpcsFile): array
	{
		$lazyValue = static function () use ($phpcsFile): array {
			return TokenHelper::findNextAll($phpcsFile, T_NAMESPACE, 0);
		};

		return SniffLocalCache::getAndSetIfNotCached($phpcsFile, 'namespacePointers', $lazyValue);
	}

	/**
	 * @param string $typeName
	 */
	public static function isFullyQualifiedName($typeName): bool
	{
		return StringHelper::startsWith($typeName, self::NAMESPACE_SEPARATOR);
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 */
	public static function isFullyQualifiedPointer($phpcsFile, $pointer): bool
	{
		return in_array($phpcsFile->getTokens()[$pointer]['code'], [T_NS_SEPARATOR, T_NAME_FULLY_QUALIFIED], true);
	}

	/**
	 * @param string $typeName
	 */
	public static function getFullyQualifiedTypeName($typeName): string
	{
		if (self::isFullyQualifiedName($typeName)) {
			return $typeName;
		}

		return sprintf('%s%s', self::NAMESPACE_SEPARATOR, $typeName);
	}

	/**
	 * @param string $typeName
	 */
	public static function hasNamespace($typeName): bool
	{
		$parts = self::getNameParts($typeName);

		return count($parts) > 1;
	}

	/**
	 * @return string[]
	 * @param string $name
	 */
	public static function getNameParts($name): array
	{
		$name = self::normalizeToCanonicalName($name);

		return explode(self::NAMESPACE_SEPARATOR, $name);
	}

	/**
	 * @param string $name
	 */
	public static function getLastNamePart($name): string
	{
		return array_slice(self::getNameParts($name), -1)[0];
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $namespacePointer
	 */
	public static function getName($phpcsFile, $namespacePointer): string
	{
		/** @var int $namespaceNameStartPointer */
		$namespaceNameStartPointer = TokenHelper::findNextEffective($phpcsFile, $namespacePointer + 1);
		$namespaceNameEndPointer = TokenHelper::findNextExcluding(
			$phpcsFile,
			TokenHelper::getNameTokenCodes(),
			$namespaceNameStartPointer + 1
		) - 1;

		return TokenHelper::getContent($phpcsFile, $namespaceNameStartPointer, $namespaceNameEndPointer);
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 * @return int|null
	 */
	public static function findCurrentNamespacePointer($phpcsFile, $pointer)
	{
		$allNamespacesPointers = array_reverse(self::getAllNamespacesPointers($phpcsFile));
		foreach ($allNamespacesPointers as $namespacesPointer) {
			if ($namespacesPointer < $pointer) {
				return $namespacesPointer;
			}
		}

		return null;
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $anyPointer
	 * @return string|null
	 */
	public static function findCurrentNamespaceName($phpcsFile, $anyPointer)
	{
		$namespacePointer = self::findCurrentNamespacePointer($phpcsFile, $anyPointer);
		if ($namespacePointer === null) {
			return null;
		}

		return self::getName($phpcsFile, $namespacePointer);
	}

	/**
	 * @param string $name
	 */
	public static function getUnqualifiedNameFromFullyQualifiedName($name): string
	{
		$parts = self::getNameParts($name);
		return $parts[count($parts) - 1];
	}

	/**
	 * @param string $name
	 */
	public static function isQualifiedName($name): bool
	{
		return strpos($name, self::NAMESPACE_SEPARATOR) !== false;
	}

	/**
	 * @param string $fullyQualifiedName
	 */
	public static function normalizeToCanonicalName($fullyQualifiedName): string
	{
		return ltrim($fullyQualifiedName, self::NAMESPACE_SEPARATOR);
	}

	/**
	 * @param string $typeName
	 * @param string $namespace
	 */
	public static function isTypeInNamespace($typeName, $namespace): bool
	{
		return StringHelper::startsWith(
			self::normalizeToCanonicalName($typeName) . '\\',
			$namespace . '\\'
		);
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param string $nameAsReferencedInFile
	 * @param int $currentPointer
	 */
	public static function resolveClassName($phpcsFile, $nameAsReferencedInFile, $currentPointer): string
	{
		return self::resolveName($phpcsFile, $nameAsReferencedInFile, ReferencedName::TYPE_CLASS, $currentPointer);
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param string $nameAsReferencedInFile
	 * @param string $type
	 * @param int $currentPointer
	 */
	public static function resolveName($phpcsFile, $nameAsReferencedInFile, $type, $currentPointer): string
	{
		if (self::isFullyQualifiedName($nameAsReferencedInFile)) {
			return $nameAsReferencedInFile;
		}

		$useStatements = UseStatementHelper::getUseStatementsForPointer($phpcsFile, $currentPointer);

		$uniqueId = UseStatement::getUniqueId($type, self::normalizeToCanonicalName($nameAsReferencedInFile));

		if (isset($useStatements[$uniqueId])) {
			return sprintf('%s%s', self::NAMESPACE_SEPARATOR, $useStatements[$uniqueId]->getFullyQualifiedTypeName());
		}

		$nameParts = self::getNameParts($nameAsReferencedInFile);
		$firstPartUniqueId = UseStatement::getUniqueId($type, $nameParts[0]);
		if (count($nameParts) > 1 && isset($useStatements[$firstPartUniqueId])) {
			return sprintf(
				'%s%s%s%s',
				self::NAMESPACE_SEPARATOR,
				$useStatements[$firstPartUniqueId]->getFullyQualifiedTypeName(),
				self::NAMESPACE_SEPARATOR,
				implode(self::NAMESPACE_SEPARATOR, array_slice($nameParts, 1))
			);
		}

		$name = sprintf('%s%s', self::NAMESPACE_SEPARATOR, $nameAsReferencedInFile);
		$namespaceName = self::findCurrentNamespaceName($phpcsFile, $currentPointer);
		if ($namespaceName !== null) {
			$name = sprintf('%s%s%s', self::NAMESPACE_SEPARATOR, $namespaceName, $name);
		}
		return $name;
	}

}
