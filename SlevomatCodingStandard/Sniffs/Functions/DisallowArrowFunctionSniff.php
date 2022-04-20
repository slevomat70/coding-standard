<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Functions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use const T_FN;

class DisallowArrowFunctionSniff implements Sniff
{

	const CODE_DISALLOWED_ARROW_FUNCTION = 'DisallowedArrowFunction';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_FN,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param int $arrowFunctionPointer
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @return void
	 */
	public function process(File $phpcsFile, $arrowFunctionPointer)
	{
		$phpcsFile->addError('Use of arrow function is disallowed.', $arrowFunctionPointer, self::CODE_DISALLOWED_ARROW_FUNCTION);
	}

}
