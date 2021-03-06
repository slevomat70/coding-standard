<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use const T_NULLSAFE_OBJECT_OPERATOR;

class DisallowNullSafeObjectOperatorSniff implements Sniff
{

	const CODE_DISALLOWED_NULL_SAFE_OBJECT_OPERATOR = 'DisallowedNullSafeObjectOperator';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_NULLSAFE_OBJECT_OPERATOR,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param int $operatorPointer
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @return void
	 */
	public function process(File $phpcsFile, $operatorPointer)
	{
		$phpcsFile->addError('Operator ?-> is disallowed.', $operatorPointer, self::CODE_DISALLOWED_NULL_SAFE_OBJECT_OPERATOR);
	}

}
