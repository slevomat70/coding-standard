<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use SlevomatCodingStandard\Sniffs\TestCase;

class EarlyExitSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/earlyExitNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/earlyExitErrors.php');

		self::assertSame(73, $report->getErrorCount());

		foreach ([6, 15, 24, 33, 42, 50, 58, 66, 74, 82, 90, 98, 108, 149, 157, 165, 191, 199, 207] as $line) {
			self::assertSniffError($report, $line, EarlyExitSniff::CODE_EARLY_EXIT_NOT_USED, 'Use early exit instead of "else".');
		}

		foreach ([115, 122, 129, 135, 141, 213, 222, 229, 235, 241, 247, 256, 262, 271, 287, 305, 361, 368, 380, 407, 417, 442, 495, 504, 519, 528, 535, 542, 549, 556, 564] as $line) {
			self::assertSniffError($report, $line, EarlyExitSniff::CODE_EARLY_EXIT_NOT_USED, 'Use early exit to reduce code nesting.');
		}

		foreach ([173, 182, 328, 353, 390, 432, 454, 467, 572] as $line) {
			self::assertSniffError($report, $line, EarlyExitSniff::CODE_USELESS_ELSE, 'Remove useless "else" to reduce code nesting.');
		}

		foreach ([322, 324, 326, 336, 338, 340, 351, 388, 398, 428, 452, 465] as $line) {
			self::assertSniffError($report, $line, EarlyExitSniff::CODE_USELESS_ELSEIF, 'Use "if" instead of "elseif".');
		}

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testIfWithoutCurlyBraces()
	{
		$report = self::checkFile(__DIR__ . '/data/earlyExitIfWithoutCurlyBraces.php', [], [EarlyExitSniff::CODE_EARLY_EXIT_NOT_USED]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testElseifWithoutCurlyBraces()
	{
		$report = self::checkFile(__DIR__ . '/data/earlyExitElseifWithoutCurlyBraces.php', [], [EarlyExitSniff::CODE_USELESS_ELSEIF]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testElseifWithSpace()
	{
		$report = self::checkFile(__DIR__ . '/data/earlyExitElseifWithSpace.php', [], [EarlyExitSniff::CODE_USELESS_ELSEIF]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testElseWithoutCurlyBraces()
	{
		$report = self::checkFile(__DIR__ . '/data/earlyExitElseWithoutCurlyBraces.php', [], [EarlyExitSniff::CODE_USELESS_ELSE]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testInvalidElseif()
	{
		$report = self::checkFile(__DIR__ . '/data/earlyExitInvalidElseif.php', [], [EarlyExitSniff::CODE_USELESS_ELSEIF]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testAlternativeSyntax()
	{
		$report = self::checkFile(__DIR__ . '/data/earlyExitAlternativeSyntax.php', [], [EarlyExitSniff::CODE_USELESS_ELSEIF]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testIgnoredStandaloneIfInScopeNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/earlyExitIgnoredStandaloneIfInScope.php', [
			'ignoreStandaloneIfInScope' => true,
		]);

		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testNotIgnoredStandaloneIfInScopeErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/earlyExitIgnoredStandaloneIfInScope.php', [
			'ignoreStandaloneIfInScope' => false,
		]);

		self::assertSame(2, $report->getErrorCount());

		self::assertSniffError($report, 4, EarlyExitSniff::CODE_EARLY_EXIT_NOT_USED, 'Use early exit to reduce code nesting.');
		self::assertSniffError($report, 11, EarlyExitSniff::CODE_EARLY_EXIT_NOT_USED, 'Use early exit to reduce code nesting.');
	}

	/**
	 * @return void
	 */
	public function testIgnoredOneLineTrailingIfNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/earlyExitIgnoredOneLineTrailingIfNoErrors.php', [
			'ignoreOneLineTrailingIf' => true,
		]);

		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testIgnoredOneLineTrailingIfErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/earlyExitIgnoredOneLineTrailingIfErrors.php', [
			'ignoreOneLineTrailingIf' => true,
		]);

		self::assertSame(2, $report->getErrorCount());

		self::assertSniffError($report, 7, EarlyExitSniff::CODE_USELESS_ELSEIF);
		self::assertSniffError($report, 17, EarlyExitSniff::CODE_USELESS_ELSEIF);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testIgnoredTrailingIfWithOneInstructionNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/earlyExitIgnoredTrailingIfWithOneInstructionNoErrors.php', [
			'ignoreTrailingIfWithOneInstruction' => true,
		]);

		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testIgnoredTrailingIfWithOneInstructionErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/earlyExitIgnoredTrailingIfWithOneInstructionErrors.php', [
			'ignoreTrailingIfWithOneInstruction' => true,
		]);

		self::assertSame(4, $report->getErrorCount());

		self::assertSniffError($report, 7, EarlyExitSniff::CODE_USELESS_ELSEIF);
		self::assertSniffError($report, 17, EarlyExitSniff::CODE_USELESS_ELSEIF);
		self::assertSniffError($report, 26, EarlyExitSniff::CODE_EARLY_EXIT_NOT_USED);
		self::assertSniffError($report, 36, EarlyExitSniff::CODE_EARLY_EXIT_NOT_USED);

		self::assertAllFixedInFile($report);
	}

}
