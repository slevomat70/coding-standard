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

class SuperfluousAbstractClassNamingSniff implements Sniff
{

	const CODE_SUPERFLUOUS_PREFIX = 'SuperfluousPrefix';
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
		if ($phpcsFile->getTokens()[$previousPointer]['code'] !== T_ABSTRACT) {
			return;
		}

		$this->checkPrefix($phpcsFile, $classPointer, $className);
		$this->checkSuffix($phpcsFile, $classPointer, $className);
	}

	/**
	 * @return void
	 */
	private function checkPrefix(File $phpcsFile, int $classPointer, string $className)
	{
		$prefix = substr($className, 0, 8);

		if (strtolower($prefix) !== 'abstract') {
			return;
		}

		$phpcsFile->addError(sprintf('Superfluous prefix "%s".', $prefix), $classPointer, self::CODE_SUPERFLUOUS_PREFIX);
	}

	/**
	 * @return void
	 */
	private function checkSuffix(File $phpcsFile, int $classPointer, string $className)
	{
		$suffix = substr($className, -8);

		if (strtolower($suffix) !== 'abstract') {
			return;
		}

		$phpcsFile->addError(sprintf('Superfluous suffix "%s".', $suffix), $classPointer, self::CODE_SUPERFLUOUS_SUFFIX);
	}

}
