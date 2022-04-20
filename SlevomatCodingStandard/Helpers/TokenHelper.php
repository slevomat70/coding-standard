<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use function array_key_exists;
use function array_merge;
use function count;
use const T_ARRAY_HINT;
use const T_BREAK;
use const T_CALLABLE;
use const T_CLASS;
use const T_CLOSURE;
use const T_COMMENT;
use const T_CONTINUE;
use const T_DOC_COMMENT;
use const T_DOC_COMMENT_CLOSE_TAG;
use const T_DOC_COMMENT_OPEN_TAG;
use const T_DOC_COMMENT_STAR;
use const T_DOC_COMMENT_STRING;
use const T_DOC_COMMENT_TAG;
use const T_DOC_COMMENT_WHITESPACE;
use const T_EXIT;
use const T_FALSE;
use const T_FN;
use const T_FUNCTION;
use const T_INTERFACE;
use const T_NAME_FULLY_QUALIFIED;
use const T_NAME_QUALIFIED;
use const T_NAME_RELATIVE;
use const T_NS_SEPARATOR;
use const T_NULL;
use const T_PARENT;
use const T_PHPCS_DISABLE;
use const T_PHPCS_ENABLE;
use const T_PHPCS_IGNORE;
use const T_PHPCS_IGNORE_FILE;
use const T_PHPCS_SET;
use const T_RETURN;
use const T_SELF;
use const T_STRING;
use const T_THROW;
use const T_TRAIT;
use const T_TYPE_UNION;
use const T_WHITESPACE;

/**
 * @internal
 */
class TokenHelper
{

	/** @var (int|string)[] */
	public static $typeKeywordTokenCodes = [
		T_CLASS,
		T_TRAIT,
		T_INTERFACE,
	];

	/** @var (int|string)[] */
	public static $ineffectiveTokenCodes = [
		T_WHITESPACE,
		T_COMMENT,
		T_DOC_COMMENT,
		T_DOC_COMMENT_OPEN_TAG,
		T_DOC_COMMENT_CLOSE_TAG,
		T_DOC_COMMENT_STAR,
		T_DOC_COMMENT_STRING,
		T_DOC_COMMENT_TAG,
		T_DOC_COMMENT_WHITESPACE,
		T_PHPCS_DISABLE,
		T_PHPCS_ENABLE,
		T_PHPCS_IGNORE,
		T_PHPCS_IGNORE_FILE,
		T_PHPCS_SET,
	];

	/** @var (int|string)[] */
	public static $inlineCommentTokenCodes = [
		T_COMMENT,
		T_PHPCS_DISABLE,
		T_PHPCS_ENABLE,
		T_PHPCS_IGNORE,
		T_PHPCS_IGNORE_FILE,
		T_PHPCS_SET,
	];

	/** @var (int|string)[] */
	public static $earlyExitTokenCodes = [
		T_RETURN,
		T_CONTINUE,
		T_BREAK,
		T_THROW,
		T_EXIT,
	];

	/** @var (int|string)[] */
	public static $functionTokenCodes = [
		T_FUNCTION,
		T_CLOSURE,
		T_FN,
	];

	/**
	 * @param (int|string)|(int|string)[] $types
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $startPointer
	 * @param int|null $endPointer
	 * @return int|null
	 */
	public static function findNext($phpcsFile, $types, $startPointer, $endPointer = null)
	{
		/** @var int|false $token */
		$token = $phpcsFile->findNext($types, $startPointer, $endPointer, false);
		return $token === false ? null : $token;
	}

	/**
	 * @param (int|string)|(int|string)[] $types
	 * @return int[]
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $startPointer
	 * @param int|null $endPointer
	 */
	public static function findNextAll($phpcsFile, $types, $startPointer, $endPointer = null): array
	{
		$pointers = [];

		$actualStartPointer = $startPointer;
		while (true) {
			$pointer = self::findNext($phpcsFile, $types, $actualStartPointer, $endPointer);
			if ($pointer === null) {
				break;
			}

			$pointers[] = $pointer;
			$actualStartPointer = $pointer + 1;
		}

		return $pointers;
	}

	/**
	 * @param (int|string)|(int|string)[] $types
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param string $content
	 * @param int $startPointer
	 * @param int|null $endPointer
	 * @return int|null
	 */
	public static function findNextContent($phpcsFile, $types, $content, $startPointer, $endPointer = null)
	{
		/** @var int|false $token */
		$token = $phpcsFile->findNext($types, $startPointer, $endPointer, false, $content);
		return $token === false ? null : $token;
	}

	/**
	 * @param int $startPointer Search starts at this token, inclusive
	 * @param int|null $endPointer Search ends at this token, exclusive
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @return int|null
	 */
	public static function findNextEffective($phpcsFile, $startPointer, $endPointer = null)
	{
		return self::findNextExcluding($phpcsFile, self::$ineffectiveTokenCodes, $startPointer, $endPointer);
	}

	/**
	 * @param (int|string)|(int|string)[] $types
	 * @param int $startPointer Search starts at this token, inclusive
	 * @param int|null $endPointer Search ends at this token, exclusive
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @return int|null
	 */
	public static function findNextExcluding($phpcsFile, $types, $startPointer, $endPointer = null)
	{
		/** @var int|false $token */
		$token = $phpcsFile->findNext($types, $startPointer, $endPointer, true);
		return $token === false ? null : $token;
	}

	/**
	 * @param (int|string)|(int|string)[] $types
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $startPointer
	 * @param int|null $endPointer
	 * @return int|null
	 */
	public static function findNextLocal($phpcsFile, $types, $startPointer, $endPointer = null)
	{
		/** @var int|false $token */
		$token = $phpcsFile->findNext($types, $startPointer, $endPointer, false, null, true);
		return $token === false ? null : $token;
	}

	/**
	 * @param int $startPointer Search starts at this token, inclusive
	 * @param int|null $endPointer Search ends at this token, exclusive
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @return int|null
	 */
	public static function findNextAnyToken($phpcsFile, $startPointer, $endPointer = null)
	{
		return self::findNextExcluding($phpcsFile, [], $startPointer, $endPointer);
	}

	/**
	 * @param (int|string)|(int|string)[] $types
	 * @param int $startPointer Search starts at this token, inclusive
	 * @param int|null $endPointer Search ends at this token, exclusive
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @return int|null
	 */
	public static function findPrevious($phpcsFile, $types, $startPointer, $endPointer = null)
	{
		/** @var int|false $token */
		$token = $phpcsFile->findPrevious($types, $startPointer, $endPointer, false);
		return $token === false ? null : $token;
	}

	/**
	 * @param (int|string)|(int|string)[] $types
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param string $content
	 * @param int $startPointer
	 * @param int|null $endPointer
	 * @return int|null
	 */
	public static function findPreviousContent($phpcsFile, $types, $content, $startPointer, $endPointer = null)
	{
		/** @var int|false $token */
		$token = $phpcsFile->findPrevious($types, $startPointer, $endPointer, false, $content);
		return $token === false ? null : $token;
	}

	/**
	 * @param int $startPointer Search starts at this token, inclusive
	 * @param int|null $endPointer Search ends at this token, exclusive
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @return int|null
	 */
	public static function findPreviousEffective($phpcsFile, $startPointer, $endPointer = null)
	{
		return self::findPreviousExcluding($phpcsFile, self::$ineffectiveTokenCodes, $startPointer, $endPointer);
	}

	/**
	 * @param (int|string)|(int|string)[] $types
	 * @param int $startPointer Search starts at this token, inclusive
	 * @param int|null $endPointer Search ends at this token, exclusive
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @return int|null
	 */
	public static function findPreviousExcluding($phpcsFile, $types, $startPointer, $endPointer = null)
	{
		/** @var int|false $token */
		$token = $phpcsFile->findPrevious($types, $startPointer, $endPointer, true);
		return $token === false ? null : $token;
	}

	/**
	 * @param (int|string)|(int|string)[] $types
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $startPointer
	 * @param int|null $endPointer
	 * @return int|null
	 */
	public static function findPreviousLocal($phpcsFile, $types, $startPointer, $endPointer = null)
	{
		/** @var int|false $token */
		$token = $phpcsFile->findPrevious($types, $startPointer, $endPointer, false, null, true);
		return $token === false ? null : $token;
	}

	/**
	 * @param int $pointer Search starts at this token, inclusive
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 */
	public static function findFirstTokenOnLine($phpcsFile, $pointer): int
	{
		if ($pointer === 0) {
			return $pointer;
		}

		$tokens = $phpcsFile->getTokens();

		$line = $tokens[$pointer]['line'];

		do {
			$pointer--;
		} while ($tokens[$pointer]['line'] === $line);

		return $pointer + 1;
	}

	/**
	 * @param int $pointer Search starts at this token, inclusive
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 */
	public static function findLastTokenOnLine($phpcsFile, $pointer): int
	{
		$tokens = $phpcsFile->getTokens();

		$line = $tokens[$pointer]['line'];

		do {
			$pointer++;
		} while (array_key_exists($pointer, $tokens) && $tokens[$pointer]['line'] === $line);

		return $pointer - 1;
	}

	/**
	 * @param int $pointer Search starts at this token, inclusive
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @return int|null
	 */
	public static function findFirstTokenOnNextLine($phpcsFile, $pointer)
	{
		$tokens = $phpcsFile->getTokens();
		if ($pointer >= count($tokens)) {
			return null;
		}

		$line = $tokens[$pointer]['line'];

		do {
			$pointer++;
			if (!array_key_exists($pointer, $tokens)) {
				return null;
			}
		} while ($tokens[$pointer]['line'] === $line);

		return $pointer;
	}

	/**
	 * @param int $pointer Search starts at this token, inclusive
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 */
	public static function findFirstNonWhitespaceOnLine($phpcsFile, $pointer): int
	{
		if ($pointer === 0) {
			return $pointer;
		}

		$tokens = $phpcsFile->getTokens();

		$line = $tokens[$pointer]['line'];

		do {
			$pointer--;
		} while ($tokens[$pointer]['line'] === $line);

		return self::findNextExcluding($phpcsFile, [T_WHITESPACE, T_DOC_COMMENT_WHITESPACE], $pointer + 1);
	}

	/**
	 * @param int $pointer Search starts at this token, inclusive
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @return int|null
	 */
	public static function findFirstNonWhitespaceOnNextLine($phpcsFile, $pointer)
	{
		$newLinePointer = self::findNextContent($phpcsFile, [T_WHITESPACE, T_DOC_COMMENT_WHITESPACE], $phpcsFile->eolChar, $pointer);
		if ($newLinePointer === null) {
			return null;
		}

		$nextPointer = self::findNextExcluding($phpcsFile, [T_WHITESPACE, T_DOC_COMMENT_WHITESPACE], $newLinePointer + 1);

		$tokens = $phpcsFile->getTokens();
		if ($nextPointer !== null && $tokens[$pointer]['line'] === $tokens[$nextPointer]['line'] - 1) {
			return $nextPointer;
		}

		return null;
	}

	/**
	 * @param int $pointer Search starts at this token, inclusive
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @return int|null
	 */
	public static function findFirstNonWhitespaceOnPreviousLine($phpcsFile, $pointer)
	{
		$newLinePointerOnPreviousLine = self::findPreviousContent(
			$phpcsFile,
			[T_WHITESPACE, T_DOC_COMMENT_WHITESPACE],
			$phpcsFile->eolChar,
			$pointer
		);
		if ($newLinePointerOnPreviousLine === null) {
			return null;
		}

		$newLinePointerBeforePreviousLine = self::findPreviousContent(
			$phpcsFile,
			[T_WHITESPACE, T_DOC_COMMENT_WHITESPACE],
			$phpcsFile->eolChar,
			$newLinePointerOnPreviousLine - 1
		);
		if ($newLinePointerBeforePreviousLine === null) {
			return null;
		}

		$nextPointer = self::findNextExcluding($phpcsFile, [T_WHITESPACE, T_DOC_COMMENT_WHITESPACE], $newLinePointerBeforePreviousLine + 1);

		$tokens = $phpcsFile->getTokens();
		if ($nextPointer !== null && $tokens[$pointer]['line'] === $tokens[$nextPointer]['line'] + 1) {
			return $nextPointer;
		}

		return null;
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $startPointer
	 * @param int|null $endPointer
	 */
	public static function getContent($phpcsFile, $startPointer, $endPointer = null): string
	{
		$tokens = $phpcsFile->getTokens();
		$endPointer = $endPointer ?? self::getLastTokenPointer($phpcsFile);

		$content = '';
		for ($i = $startPointer; $i <= $endPointer; $i++) {
			$content .= $tokens[$i]['content'];
		}

		return $content;
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 */
	public static function getLastTokenPointer($phpcsFile): int
	{
		$tokenCount = count($phpcsFile->getTokens());
		if ($tokenCount === 0) {
			throw new EmptyFileException($phpcsFile->getFilename());
		}
		return $tokenCount - 1;
	}

	/**
	 * @internal
	 * @return array<int, (int|string)>
	 */
	public static function getNameTokenCodes(): array
	{
		return [T_STRING, T_NS_SEPARATOR, T_NAME_FULLY_QUALIFIED, T_NAME_QUALIFIED, T_NAME_RELATIVE];
	}

	/**
	 * @internal
	 * @return array<int, (int|string)>
	 */
	public static function getOnlyNameTokenCodes(): array
	{
		return [T_STRING, T_NAME_FULLY_QUALIFIED, T_NAME_QUALIFIED, T_NAME_RELATIVE];
	}

	/**
	 * @return array<int, (int|string)>
	 */
	public static function getOnlyTypeHintTokenCodes(): array
	{
		static $typeHintTokenCodes = null;

		if ($typeHintTokenCodes === null) {
			$typeHintTokenCodes = array_merge(
				self::getNameTokenCodes(),
				[
					T_SELF,
					T_PARENT,
					T_ARRAY_HINT,
					T_CALLABLE,
					T_FALSE,
					T_NULL,
				]
			);
		}

		return $typeHintTokenCodes;
	}

	/**
	 * @return array<int, (int|string)>
	 */
	public static function getTypeHintTokenCodes(): array
	{
		static $typeHintTokenCodes = null;

		if ($typeHintTokenCodes === null) {
			$typeHintTokenCodes = array_merge(
				self::getOnlyTypeHintTokenCodes(),
				[T_TYPE_UNION]
			);
		}

		return $typeHintTokenCodes;
	}

}
