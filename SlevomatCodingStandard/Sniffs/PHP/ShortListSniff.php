<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\PHP;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\TokenHelper;
use const T_LIST;
use const T_OPEN_PARENTHESIS;

class ShortListSniff implements Sniff
{

	const CODE_LONG_LIST_USED = 'LongListUsed';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [T_LIST];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param int $pointer
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @return void
	 */
	public function process(File $phpcsFile, $pointer)
	{
		$fix = $phpcsFile->addFixableError('list(...) is forbidden, use [...] instead.', $pointer, self::CODE_LONG_LIST_USED);

		if (!$fix) {
			return;
		}

		$tokens = $phpcsFile->getTokens();

		/** @var int $startPointer */
		$startPointer = TokenHelper::findNext($phpcsFile, [T_OPEN_PARENTHESIS], $pointer + 1);
		$endPointer = $tokens[$startPointer]['parenthesis_closer'];

		$phpcsFile->fixer->beginChangeset();
		for ($i = $pointer; $i < $startPointer; $i++) {
			$phpcsFile->fixer->replaceToken($i, '');
		}
		$phpcsFile->fixer->replaceToken($startPointer, '[');
		$phpcsFile->fixer->replaceToken($endPointer, ']');
		$phpcsFile->fixer->endChangeset();
	}

}
