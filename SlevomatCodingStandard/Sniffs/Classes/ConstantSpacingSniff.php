<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function array_keys;
use function count;
use function in_array;
use function sprintf;
use const T_ANON_CLASS;
use const T_CLASS;
use const T_CONST;
use const T_FUNCTION;
use const T_INTERFACE;
use const T_USE;
use const T_VARIABLE;

class ConstantSpacingSniff extends AbstractPropertyAndConstantSpacing
{

	const CODE_INCORRECT_COUNT_OF_BLANK_LINES_AFTER_CONSTANT = 'IncorrectCountOfBlankLinesAfterConstant';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [T_CONST];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param int $constantPointer
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 */
	public function process(File $phpcsFile, $constantPointer): int
	{
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$constantPointer]['conditions'] === []) {
			return $constantPointer;
		}

		/** @var int $classPointer */
		$classPointer = array_keys($tokens[$constantPointer]['conditions'])[count($tokens[$constantPointer]['conditions']) - 1];
		if (!in_array($tokens[$classPointer]['code'], [T_CLASS, T_INTERFACE, T_ANON_CLASS], true)) {
			return $constantPointer;
		}

		return parent::process($phpcsFile, $constantPointer);
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 */
	protected function isNextMemberValid($phpcsFile, $pointer): bool
	{
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$pointer]['code'] === T_CONST) {
			return true;
		}

		$nextPointer = TokenHelper::findNext($phpcsFile, [T_FUNCTION, T_CONST, T_VARIABLE, T_USE], $pointer + 1);

		return $tokens[$nextPointer]['code'] === T_CONST;
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 * @param int $minExpectedLines
	 * @param int $maxExpectedLines
	 * @param int $found
	 */
	protected function addError($phpcsFile, $pointer, $minExpectedLines, $maxExpectedLines, $found): bool
	{
		if ($minExpectedLines === $maxExpectedLines) {
			$errorMessage = $minExpectedLines === 1
				? 'Expected 1 blank line after constant, found %3$d.'
				: 'Expected %2$d blank lines after constant, found %3$d.';
		} else {
			$errorMessage = 'Expected %1$d to %2$d blank lines after constant, found %3$d.';
		}
		$error = sprintf($errorMessage, $minExpectedLines, $maxExpectedLines, $found);

		return $phpcsFile->addFixableError($error, $pointer, self::CODE_INCORRECT_COUNT_OF_BLANK_LINES_AFTER_CONSTANT);
	}

}
