<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use function in_array;
use function ltrim;
use function preg_replace_callback;
use function rtrim;
use function str_repeat;
use function strlen;
use function substr;
use const T_END_HEREDOC;
use const T_END_NOWDOC;
use const T_START_HEREDOC;
use const T_START_NOWDOC;

/**
 * @internal
 */
class IndentationHelper
{

	const DEFAULT_INDENTATION_WIDTH = 4;

	const TAB_INDENT = "\t";
	const SPACES_INDENT = '    ';

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 */
	public static function getIndentation($phpcsFile, $pointer): string
	{
		$firstPointerOnLine = TokenHelper::findFirstTokenOnLine($phpcsFile, $pointer);

		return TokenHelper::getContent($phpcsFile, $firstPointerOnLine, $pointer - 1);
	}

	/**
	 * @param string $identation
	 * @param int $level
	 */
	public static function addIndentation($identation, $level = 1): string
	{
		$whitespace = self::getOneIndentationLevel($identation);

		return $identation . str_repeat($whitespace, $level);
	}

	/**
	 * @param string $identation
	 */
	public static function getOneIndentationLevel($identation): string
	{
		return $identation === ''
			? self::TAB_INDENT
			: ($identation[0] === self::TAB_INDENT ? self::TAB_INDENT : self::SPACES_INDENT);
	}

	/**
	 * @param int[] $codePointers
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param string $defaultIndentation
	 */
	public static function fixIndentation($phpcsFile, $codePointers, $defaultIndentation): string
	{
		$tokens = $phpcsFile->getTokens();

		$eolLength = strlen($phpcsFile->eolChar);

		$code = '';
		$inHeredoc = false;

		foreach ($codePointers as $no => $codePointer) {
			$content = $tokens[$codePointer]['content'];

			if (
				!$inHeredoc
				&& (
					$no === 0
					|| substr($tokens[$codePointer - 1]['content'], -$eolLength) === $phpcsFile->eolChar
				)
			) {
				if ($content === $phpcsFile->eolChar) {
					// Nothing
				} elseif ($content[0] === self::TAB_INDENT) {
					$content = substr($content, 1);
				} elseif (substr($content, 0, self::DEFAULT_INDENTATION_WIDTH) === self::SPACES_INDENT) {
					$content = substr($content, self::DEFAULT_INDENTATION_WIDTH);
				} else {
					$content = $defaultIndentation . ltrim($content);
				}
			}

			if (in_array($tokens[$codePointer]['code'], [T_START_HEREDOC, T_START_NOWDOC], true)) {
				$inHeredoc = true;
			} elseif (in_array($tokens[$codePointer]['code'], [T_END_HEREDOC, T_END_NOWDOC], true)) {
				$inHeredoc = false;
			}

			$code .= $content;
		}

		return rtrim($code);
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param string $code
	 */
	public static function convertTabsToSpaces($phpcsFile, $code): string
	{
		return preg_replace_callback('~^(\t+)~', static function (array $matches) use ($phpcsFile): string {
			$indentation = str_repeat(
				' ',
				$phpcsFile->config->tabWidth !== 0 ? $phpcsFile->config->tabWidth : self::DEFAULT_INDENTATION_WIDTH
			);
			return str_repeat($indentation, strlen($matches[1]));
		}, $code);
	}

}
