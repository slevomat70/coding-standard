<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use const T_EMPTY;

class DisallowEmptySniff implements Sniff
{

	const CODE_DISALLOWED_EMPTY = 'DisallowedEmpty';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_EMPTY,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param int $emptyPointer
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @return void
	 */
	public function process(File $phpcsFile, $emptyPointer)
	{
		$phpcsFile->addError('Use of empty() is disallowed.', $emptyPointer, self::CODE_DISALLOWED_EMPTY);
	}

}
