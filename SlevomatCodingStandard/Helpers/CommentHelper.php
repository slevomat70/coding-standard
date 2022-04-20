<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use function array_key_exists;
use function preg_match;
use function strpos;
use const T_COMMENT;

/**
 * @internal
 */
class CommentHelper
{

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $commentPointer
	 */
	public static function isLineComment($phpcsFile, $commentPointer): bool
	{
		return (bool) preg_match('~^(?://|#)(.*)~', $phpcsFile->getTokens()[$commentPointer]['content']);
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $commentStartPointer
	 * @return int|null
	 */
	public static function getCommentEndPointer($phpcsFile, $commentStartPointer)
	{
		$tokens = $phpcsFile->getTokens();

		if (array_key_exists('comment_closer', $tokens[$commentStartPointer])) {
			return $tokens[$commentStartPointer]['comment_closer'];
		}

		if (self::isLineComment($phpcsFile, $commentStartPointer)) {
			return $commentStartPointer;
		}

		if (strpos($tokens[$commentStartPointer]['content'], '/*') !== 0) {
			// Part of block comment
			return null;
		}

		$nextPointerAfterComment = TokenHelper::findNextExcluding($phpcsFile, T_COMMENT, $commentStartPointer + 1);
		return $nextPointerAfterComment - 1;
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $commentEndPointer
	 */
	public static function getMultilineCommentStartPointer($phpcsFile, $commentEndPointer): int
	{
		$tokens = $phpcsFile->getTokens();

		$commentStartPointer = $commentEndPointer;
		do {
			$commentBefore = TokenHelper::findPrevious($phpcsFile, TokenHelper::$inlineCommentTokenCodes, $commentStartPointer - 1);
			if ($commentBefore === null) {
				break;
			}
			if ($tokens[$commentBefore]['line'] + 1 !== $tokens[$commentStartPointer]['line']) {
				break;
			}

			/** @var int $commentStartPointer */
			$commentStartPointer = $commentBefore;
		} while (true);

		return $commentStartPointer;
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $commentStartPointer
	 */
	public static function getMultilineCommentEndPointer($phpcsFile, $commentStartPointer): int
	{
		$tokens = $phpcsFile->getTokens();

		$commentEndPointer = $commentStartPointer;
		do {
			$commentAfter = TokenHelper::findNext($phpcsFile, TokenHelper::$inlineCommentTokenCodes, $commentEndPointer + 1);
			if ($commentAfter === null) {
				break;
			}
			if ($tokens[$commentAfter]['line'] - 1 !== $tokens[$commentEndPointer]['line']) {
				break;
			}

			/** @var int $commentEndPointer */
			$commentEndPointer = $commentAfter;
		} while (true);

		return $commentEndPointer;
	}

}
