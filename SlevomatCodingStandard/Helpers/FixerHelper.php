<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use function count;
use const T_WHITESPACE;

/**
 * @internal
 */
class FixerHelper
{

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 * @return void
	 */
	public static function cleanWhitespaceBefore($phpcsFile, $pointer)
	{
		$tokens = $phpcsFile->getTokens();

		for ($i = $pointer - 1; $i > 0; $i--) {
			if ($tokens[$i]['code'] !== T_WHITESPACE) {
				break;
			}

			$phpcsFile->fixer->replaceToken($i, '');
		}
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 * @return void
	 */
	public static function cleanWhitespaceAfter($phpcsFile, $pointer)
	{
		$tokens = $phpcsFile->getTokens();

		for ($i = $pointer + 1; $i < count($tokens); $i++) {
			if ($tokens[$i]['code'] !== T_WHITESPACE) {
				break;
			}

			$phpcsFile->fixer->replaceToken($i, '');
		}
	}

}
