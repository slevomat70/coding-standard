<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs;

use PHP_CodeSniffer\Config;
use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Files\LocalFile;
use PHP_CodeSniffer\Runner;
use PHP_CodeSniffer\Sniffs\Sniff;
use ReflectionClass;
use function array_map;
use function array_merge;
use function count;
use function define;
use function defined;
use function implode;
use function in_array;
use function preg_replace;
use function sprintf;
use function strlen;
use function strpos;
use function substr;
use const PHP_EOL;

/**
 * @codeCoverageIgnore
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{

	/**
	 * @param (string|int|bool|array<int|string, (string|int|bool|null)>)[] $sniffProperties
	 * @param string[] $codesToCheck
	 * @param string[] $cliArgs
	 * @param string $filePath
	 */
	protected static function checkFile($filePath, $sniffProperties = [], $codesToCheck = [], $cliArgs = []): File
	{
		if (defined('PHP_CODESNIFFER_CBF') === false) {
			define('PHP_CODESNIFFER_CBF', false);
		}
		$codeSniffer = new Runner();
		$codeSniffer->config = new Config(array_merge(['-s'], $cliArgs));
		$codeSniffer->init();

		if (count($sniffProperties) > 0) {
			$codeSniffer->ruleset->ruleset[self::getSniffName()]['properties'] = $sniffProperties;
		}

		$sniffClassName = self::getSniffClassName();
		/** @var Sniff $sniff */
		$sniff = new $sniffClassName();

		$codeSniffer->ruleset->sniffs = [$sniffClassName => $sniff];

		if (count($codesToCheck) > 0) {
			foreach (self::getSniffClassReflection()->getConstants() as $constantName => $constantValue) {
				if (strpos($constantName, 'CODE_') !== 0 || in_array($constantValue, $codesToCheck, true)) {
					continue;
				}

				$codeSniffer->ruleset->ruleset[sprintf('%s.%s', self::getSniffName(), $constantValue)]['severity'] = 0;
			}
		}

		$codeSniffer->ruleset->populateTokenListeners();

		$file = new LocalFile($filePath, $codeSniffer->ruleset, $codeSniffer->config);
		$file->process();

		return $file;
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @return void
	 */
	protected static function assertNoSniffErrorInFile($phpcsFile)
	{
		$errors = $phpcsFile->getErrors();
		self::assertEmpty($errors, sprintf('No errors expected, but %d errors found.', count($errors)));
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $line
	 * @param string $code
	 * @param string|null $message
	 * @return void
	 */
	protected static function assertSniffError($phpcsFile, $line, $code, $message = null)
	{
		$errors = $phpcsFile->getErrors();
		self::assertTrue(isset($errors[$line]), sprintf('Expected error on line %s, but none found.', $line));

		$sniffCode = sprintf('%s.%s', self::getSniffName(), $code);

		self::assertTrue(
			self::hasError($errors[$line], $sniffCode, $message),
			sprintf(
				'Expected error %s%s, but none found on line %d.%sErrors found on line %d:%s%s%s',
				$sniffCode,
				$message !== null
					? sprintf(' with message "%s"', $message)
					: '',
				$line,
				PHP_EOL . PHP_EOL,
				$line,
				PHP_EOL,
				self::getFormattedErrors($errors[$line]),
				PHP_EOL
			)
		);
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $line
	 * @return void
	 */
	protected static function assertNoSniffError($phpcsFile, $line)
	{
		$errors = $phpcsFile->getErrors();
		self::assertFalse(
			isset($errors[$line]),
			sprintf(
				'Expected no error on line %s, but found:%s%s%s',
				$line,
				PHP_EOL . PHP_EOL,
				isset($errors[$line]) ? self::getFormattedErrors($errors[$line]) : '',
				PHP_EOL
			)
		);
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @return void
	 */
	protected static function assertAllFixedInFile($phpcsFile)
	{
		$phpcsFile->disableCaching();
		$phpcsFile->fixer->fixFile();
		self::assertStringEqualsFile(preg_replace('~(\\.php)$~', '.fixed\\1', $phpcsFile->getFilename()), $phpcsFile->fixer->getContents());
	}

	private static function getSniffName(): string
	{
		return preg_replace(
			[
				'~\\\~',
				'~\.Sniffs~',
				'~Sniff$~',
			],
			[
				'.',
				'',
				'',
			],
			self::getSniffClassName()
		);
	}

	/**
	 * @return class-string
	 */
	private static function getSniffClassName(): string
	{
		/** @var class-string $sniffClassName */
		$sniffClassName = substr(static::class, 0, -strlen('Test'));

		return $sniffClassName;
	}

	private static function getSniffClassReflection(): ReflectionClass
	{
		static $reflections = [];

		$className = self::getSniffClassName();

		return $reflections[$className] ?? $reflections[$className] = new ReflectionClass($className);
	}

	/**
	 * @param (string|int)[][][] $errorsOnLine
	 * @param string|null $message
	 */
	private static function hasError(array $errorsOnLine, string $sniffCode, $message): bool
	{
		$hasError = false;

		foreach ($errorsOnLine as $errorsOnPosition) {
			foreach ($errorsOnPosition as $error) {
				/** @var string $errorSource */
				$errorSource = $error['source'];
				/** @var string $errorMessage */
				$errorMessage = $error['message'];

				if (
					$errorSource === $sniffCode
					&& (
						$message === null
						|| strpos($errorMessage, $message) !== false
					)
				) {
					$hasError = true;
					break;
				}
			}
		}

		return $hasError;
	}

	/**
	 * @param (string|int|bool)[][][] $errors
	 */
	private static function getFormattedErrors(array $errors): string
	{
		return implode(PHP_EOL, array_map(static function (array $errors): string {
			return implode(PHP_EOL, array_map(static function (array $error): string {
				return sprintf("\t%s: %s", $error['source'], $error['message']);
			}, $errors));
		}, $errors));
	}

}
