<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Namespaces;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use const T_NAMESPACE;
use const T_NS_SEPARATOR;

class RequireOneNamespaceInFileSniff implements Sniff
{

	const CODE_MORE_NAMESPACES_IN_FILE = 'MoreNamespacesInFile';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_NAMESPACE,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param int $namespacePointer
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @return void
	 */
	public function process(File $phpcsFile, $namespacePointer)
	{
		$tokens = $phpcsFile->getTokens();

		$pointerAfterNamespace = TokenHelper::findNextEffective($phpcsFile, $namespacePointer + 1);
		if ($tokens[$pointerAfterNamespace]['code'] === T_NS_SEPARATOR) {
			return;
		}

		$previousNamespacePointer = $namespacePointer;
		do {
			$previousNamespacePointer = TokenHelper::findPrevious($phpcsFile, T_NAMESPACE, $previousNamespacePointer - 1);
			if ($previousNamespacePointer === null) {
				return;
			}

			$pointerAfterPreviousNamespace = TokenHelper::findNextEffective($phpcsFile, $previousNamespacePointer + 1);
			if ($tokens[$pointerAfterPreviousNamespace]['code'] === T_NS_SEPARATOR) {
				continue;
			}

			break;

		} while (true);

		$phpcsFile->addError('Only one namespace in a file is allowed.', $namespacePointer, self::CODE_MORE_NAMESPACES_IN_FILE);
	}

}
