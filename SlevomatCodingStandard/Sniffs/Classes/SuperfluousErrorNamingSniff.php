<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\ClassHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;
use function sprintf;
use function strtolower;
use function substr;
use const T_ABSTRACT;
use const T_CLASS;

class SuperfluousErrorNamingSniff implements Sniff
{

	const CODE_SUPERFLUOUS_SUFFIX = 'SuperfluousSuffix';

	/**
	 * @return array<int, (int|string)>
	 */
	public function register(): array
	{
		return [
			T_CLASS,
		];
	}

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
	 * @param int $classPointer
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @return void
	 */
	public function process(File $phpcsFile, $classPointer)
	{
		$className = ClassHelper::getName($phpcsFile, $classPointer);

		$previousPointer = TokenHelper::findPreviousEffective($phpcsFile, $classPointer - 1);
		if ($phpcsFile->getTokens()[$previousPointer]['code'] === T_ABSTRACT) {
			return;
		}

		if (strtolower($className) === 'error') {
			return;
		}

		$suffix = substr($className, -5);
		if (strtolower($suffix) !== 'error') {
			return;
		}

		$phpcsFile->addError(sprintf('Superfluous suffix "%s".', $suffix), $classPointer, self::CODE_SUPERFLUOUS_SUFFIX);
	}

}
