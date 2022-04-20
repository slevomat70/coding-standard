<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\IndentationHelper;
use SlevomatCodingStandard\Helpers\SniffSettingsHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function in_array;
use function preg_replace;
use function rtrim;
use function sprintf;
use function trim;
use const T_ELSEIF;
use const T_IF;
use const T_OPEN_CURLY_BRACKET;
use const T_WHILE;

abstract class AbstractLineCondition implements Sniff
{

	const IF_CONTROL_STRUCTURE = 'if';
	const WHILE_CONTROL_STRUCTURE = 'while';
	const DO_CONTROL_STRUCTURE = 'do';

	/** @var string[] */
	public $checkedControlStructures = [
		self::IF_CONTROL_STRUCTURE,
		self::WHILE_CONTROL_STRUCTURE,
		self::DO_CONTROL_STRUCTURE,
	];

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		$this->checkedControlStructures = SniffSettingsHelper::normalizeArray($this->checkedControlStructures);

		$register = [];

		if (in_array(self::IF_CONTROL_STRUCTURE, $this->checkedControlStructures, true)) {
			$register[] = T_IF;
			$register[] = T_ELSEIF;
		}

		if (in_array(self::WHILE_CONTROL_STRUCTURE, $this->checkedControlStructures, true)) {
			$register[] = T_WHILE;
		}

		if (in_array(self::DO_CONTROL_STRUCTURE, $this->checkedControlStructures, true)) {
			$register[] = T_WHILE;
		}

		return $register;
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $controlStructurePointer
	 */
	protected function shouldBeSkipped($phpcsFile, $controlStructurePointer): bool
	{
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$controlStructurePointer]['code'] === T_WHILE) {
			$isPartOfDo = $this->isPartOfDo($phpcsFile, $controlStructurePointer);

			if ($isPartOfDo && !in_array(self::DO_CONTROL_STRUCTURE, $this->checkedControlStructures, true)) {
				return true;
			}

			if (!$isPartOfDo && !in_array(self::WHILE_CONTROL_STRUCTURE, $this->checkedControlStructures, true)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $controlStructurePointer
	 */
	protected function getControlStructureName($phpcsFile, $controlStructurePointer): string
	{
		$tokens = $phpcsFile->getTokens();

		return $tokens[$controlStructurePointer]['code'] === T_WHILE && $this->isPartOfDo($phpcsFile, $controlStructurePointer)
			? 'do-while'
			: $tokens[$controlStructurePointer]['content'];
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $whilePointer
	 */
	protected function isPartOfDo($phpcsFile, $whilePointer): bool
	{
		$tokens = $phpcsFile->getTokens();

		$parenthesisCloserPointer = $tokens[$whilePointer]['parenthesis_closer'];
		$pointerAfterParentesisCloser = TokenHelper::findNextEffective($phpcsFile, $parenthesisCloserPointer + 1);

		return $tokens[$pointerAfterParentesisCloser]['code'] !== T_OPEN_CURLY_BRACKET;
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 */
	protected function getLineStart($phpcsFile, $pointer): string
	{
		$firstPointerOnLine = TokenHelper::findFirstTokenOnLine($phpcsFile, $pointer);

		return IndentationHelper::convertTabsToSpaces($phpcsFile, TokenHelper::getContent($phpcsFile, $firstPointerOnLine, $pointer));
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $parenthesisOpenerPointer
	 * @param int $parenthesisCloserPointer
	 */
	protected function getCondition($phpcsFile, $parenthesisOpenerPointer, $parenthesisCloserPointer): string
	{
		$condition = TokenHelper::getContent($phpcsFile, $parenthesisOpenerPointer + 1, $parenthesisCloserPointer - 1);

		return trim(preg_replace(sprintf('~%s[ \t]*~', $phpcsFile->eolChar), ' ', $condition));
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 */
	protected function getLineEnd($phpcsFile, $pointer): string
	{
		$firstPointerOnNextLine = TokenHelper::findFirstTokenOnNextLine($phpcsFile, $pointer);

		return rtrim(TokenHelper::getContent($phpcsFile, $pointer, $firstPointerOnNextLine - 1));
	}

}
