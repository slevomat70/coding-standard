<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use Generator;
use PHP_CodeSniffer\Files\File;
use function array_filter;
use function array_map;
use function array_reverse;
use function iterator_to_array;
use function sprintf;
use const T_CONST;
use const T_NAMESPACE;
use const T_STRING;

/**
 * @internal
 */
class ConstantHelper
{

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $constantPointer
	 */
	public static function getName($phpcsFile, $constantPointer): string
	{
		$tokens = $phpcsFile->getTokens();
		return $tokens[TokenHelper::findNext($phpcsFile, T_STRING, $constantPointer + 1)]['content'];
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $constantPointer
	 */
	public static function getFullyQualifiedName($phpcsFile, $constantPointer): string
	{
		$name = self::getName($phpcsFile, $constantPointer);
		$namespace = NamespaceHelper::findCurrentNamespaceName($phpcsFile, $constantPointer);

		return $namespace !== null
			? sprintf('%s%s%s%s', NamespaceHelper::NAMESPACE_SEPARATOR, $namespace, NamespaceHelper::NAMESPACE_SEPARATOR, $name)
			: $name;
	}

	/**
	 * @return string[]
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 */
	public static function getAllNames($phpcsFile): array
	{
		$previousConstantPointer = 0;

		return array_map(
			static function (int $constantPointer) use ($phpcsFile): string {
				return self::getName($phpcsFile, $constantPointer);
			},
			array_filter(
				iterator_to_array(self::getAllConstantPointers($phpcsFile, $previousConstantPointer)),
				static function (int $constantPointer) use ($phpcsFile): bool {
					foreach (array_reverse($phpcsFile->getTokens()[$constantPointer]['conditions']) as $conditionTokenCode) {
						return $conditionTokenCode === T_NAMESPACE;
					}

					return true;
				}
			)
		);
	}

	/**
	 * @return Generator<int>
	 */
	private static function getAllConstantPointers(File $phpcsFile, int &$previousConstantPointer): Generator
	{
		do {
			$nextConstantPointer = TokenHelper::findNext($phpcsFile, T_CONST, $previousConstantPointer + 1);
			if ($nextConstantPointer === null) {
				break;
			}

			$previousConstantPointer = $nextConstantPointer;

			yield $nextConstantPointer;
		} while (true);
	}

}
