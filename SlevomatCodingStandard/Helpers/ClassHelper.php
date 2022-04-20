<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use function array_merge;
use function array_reverse;
use function sprintf;
use const T_ANON_CLASS;
use const T_FINAL;
use const T_STRING;
use const T_USE;

/**
 * @internal
 */
class ClassHelper
{

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 * @return int|null
	 */
	public static function getClassPointer($phpcsFile, $pointer)
	{
		$classPointers = array_reverse(self::getAllClassPointers($phpcsFile));
		foreach ($classPointers as $classPointer) {
			if ($classPointer < $pointer && ScopeHelper::isInSameScope($phpcsFile, $classPointer, $pointer)) {
				return $classPointer;
			}
		}

		return null;
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $classPointer
	 */
	public static function isFinal($phpcsFile, $classPointer): bool
	{
		return $phpcsFile->getTokens()[TokenHelper::findPreviousEffective($phpcsFile, $classPointer - 1)]['code'] === T_FINAL;
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $classPointer
	 */
	public static function getFullyQualifiedName($phpcsFile, $classPointer): string
	{
		$className = self::getName($phpcsFile, $classPointer);

		$tokens = $phpcsFile->getTokens();
		if ($tokens[$classPointer]['code'] === T_ANON_CLASS) {
			return $className;
		}

		$name = sprintf('%s%s', NamespaceHelper::NAMESPACE_SEPARATOR, $className);
		$namespace = NamespaceHelper::findCurrentNamespaceName($phpcsFile, $classPointer);
		return $namespace !== null ? sprintf('%s%s%s', NamespaceHelper::NAMESPACE_SEPARATOR, $namespace, $name) : $name;
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $classPointer
	 */
	public static function getName($phpcsFile, $classPointer): string
	{
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$classPointer]['code'] === T_ANON_CLASS) {
			return 'class@anonymous';
		}

		return $tokens[TokenHelper::findNext($phpcsFile, T_STRING, $classPointer + 1, $tokens[$classPointer]['scope_opener'])]['content'];
	}

	/**
	 * @return array<int, string>
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 */
	public static function getAllNames($phpcsFile): array
	{
		$tokens = $phpcsFile->getTokens();

		$names = [];
		/** @var int $classPointer */
		foreach (self::getAllClassPointers($phpcsFile) as $classPointer) {
			if ($tokens[$classPointer]['code'] === T_ANON_CLASS) {
				continue;
			}

			$names[$classPointer] = self::getName($phpcsFile, $classPointer);
		}

		return $names;
	}

	/**
	 * @return int[]
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $classPointer
	 */
	public static function getTraitUsePointers($phpcsFile, $classPointer): array
	{
		$useStatements = [];

		$tokens = $phpcsFile->getTokens();

		$scopeLevel = $tokens[$classPointer]['level'] + 1;
		for ($i = $tokens[$classPointer]['scope_opener'] + 1; $i < $tokens[$classPointer]['scope_closer']; $i++) {
			if ($tokens[$i]['code'] !== T_USE) {
				continue;
			}

			if ($tokens[$i]['level'] !== $scopeLevel) {
				continue;
			}

			$useStatements[] = $i;
		}

		return $useStatements;
	}

	/**
	 * @return array<int>
	 */
	private static function getAllClassPointers(File $phpcsFile): array
	{
		$lazyValue = static function () use ($phpcsFile): array {
			return TokenHelper::findNextAll($phpcsFile, array_merge(TokenHelper::$typeKeywordTokenCodes, [T_ANON_CLASS]), 0);
		};

		return SniffLocalCache::getAndSetIfNotCached($phpcsFile, 'classPointers', $lazyValue);
	}

}
