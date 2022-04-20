<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Files\LocalFile;
use PHP_CodeSniffer\Runner;
use function count;
use function get_defined_constants;
use function is_int;
use function sprintf;
use function token_name;
use const T_CLASS;
use const T_CONST;
use const T_FUNCTION;
use const T_INTERFACE;
use const T_STRING;
use const T_TRAIT;
use const T_VARIABLE;

/**
 * @codeCoverageIgnore
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{

	const UNKNOWN_PHP_TOKEN = 'UNKNOWN';

	/**
	 * @param int|string $code
	 * @param int $line
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int|null $tokenPointer
	 * @return void
	 */
	protected function assertTokenPointer($code, $line, $phpcsFile, $tokenPointer = null)
	{
		$token = $this->getTokenFromPointer($phpcsFile, $tokenPointer);
		$expectedTokenName = $this->findTokenName($code);
		self::assertSame(
			$code,
			$token['code'],
			$expectedTokenName !== null ? sprintf('Expected %s, actual token is %s', $expectedTokenName, $token['type']) : ''
		);
		self::assertSame($line, $token['line']);
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param string $name
	 * @return int|null
	 */
	protected function findClassPointerByName($phpcsFile, $name)
	{
		$tokens = $phpcsFile->getTokens();
		for ($i = 0; $i < count($tokens); $i++) {
			if ($tokens[$i]['code'] !== T_STRING || $tokens[$i]['content'] !== $name) {
				continue;
			}

			$classPointer = TokenHelper::findPrevious($phpcsFile, [T_CLASS, T_INTERFACE, T_TRAIT], $i - 1);
			if ($classPointer === null) {
				continue;
			}

			return $classPointer;
		}
		return null;
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param string $name
	 * @return int|null
	 */
	protected function findConstantPointerByName($phpcsFile, $name)
	{
		$tokens = $phpcsFile->getTokens();
		for ($i = 0; $i < count($tokens); $i++) {
			if ($tokens[$i]['code'] !== T_STRING || $tokens[$i]['content'] !== $name) {
				continue;
			}

			$constantPointer = TokenHelper::findPrevious($phpcsFile, T_CONST, $i - 1);
			if ($constantPointer === null) {
				continue;
			}

			return $constantPointer;
		}
		return null;
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param string $name
	 * @return int|null
	 */
	protected function findPropertyPointerByName($phpcsFile, $name)
	{
		$tokens = $phpcsFile->getTokens();
		for ($i = 0; $i < count($tokens); $i++) {
			if ($tokens[$i]['code'] !== T_VARIABLE || $tokens[$i]['content'] !== sprintf('$%s', $name)) {
				continue;
			}

			if (!PropertyHelper::isProperty($phpcsFile, $i)) {
				continue;
			}

			return $i;
		}
		return null;
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param string $name
	 * @return int|null
	 */
	protected function findFunctionPointerByName($phpcsFile, $name)
	{
		$tokens = $phpcsFile->getTokens();
		for ($i = 0; $i < count($tokens); $i++) {
			if ($tokens[$i]['code'] !== T_STRING || $tokens[$i]['content'] !== $name) {
				continue;
			}

			$functionPointer = TokenHelper::findPrevious($phpcsFile, T_FUNCTION, $i - 1);
			if ($functionPointer === null) {
				continue;
			}

			return $functionPointer;
		}
		return null;
	}

	/**
	 * @param int|string $tokenCode
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $line
	 * @return int|null
	 */
	protected function findPointerByLineAndType($phpcsFile, $line, $tokenCode)
	{
		$tokens = $phpcsFile->getTokens();
		for ($i = 0; $i < count($tokens); $i++) {
			if ($tokens[$i]['line'] > $line) {
				return null;
			}

			if ($tokens[$i]['line'] < $line) {
				continue;
			}

			if ($tokens[$i]['code'] !== $tokenCode) {
				continue;
			}

			return $i;
		}
		return null;
	}

	/**
	 * @param string $filename
	 */
	protected function getCodeSnifferFile($filename): File
	{
		$codeSniffer = new Runner();
		$codeSniffer->config = new Config([
			'-s',
		]);
		$codeSniffer->init();

		$phpcsFile = new LocalFile($filename, $codeSniffer->ruleset, $codeSniffer->config);

		$phpcsFile->process();

		return $phpcsFile;
	}

	/**
	 * @param int|string $code
	 * @return string|null
	 */
	private function findTokenName($code)
	{
		if (is_int($code)) {
			$tokenName = token_name($code);
			if ($tokenName !== self::UNKNOWN_PHP_TOKEN) {
				return $tokenName;
			}
		}

		// \PHP_CodeSniffer defines more token constants
		$constants = get_defined_constants(true);
		foreach ($constants['user'] as $name => $value) {
			if ($value !== $code) {
				continue;
			}

			return $name;
		}

		return null;
	}

	/**
	 * @return mixed[]
	 * @param int|null $tokenPointer
	 */
	private function getTokenFromPointer(File $phpcsFile, $tokenPointer = null): array
	{
		if ($tokenPointer === null) {
			throw new NullTokenPointerException();
		}

		$tokens = $phpcsFile->getTokens();
		if (!isset($tokens[$tokenPointer])) {
			throw new TokenPointerOutOfBoundsException(
				$tokenPointer,
				TokenHelper::getLastTokenPointer($phpcsFile)
			);
		}

		return $tokens[$tokenPointer];
	}

}
