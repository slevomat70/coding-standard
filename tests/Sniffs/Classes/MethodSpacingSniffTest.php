<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use SlevomatCodingStandard\Sniffs\TestCase;

class MethodSpacingSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/methodSpacingNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/methodSpacingErrors.php');

		self::assertSame(5, $report->getErrorCount());

		self::assertSniffError(
			$report,
			6,
			MethodSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_METHODS,
			'Expected 1 blank line after method, found 0.'
		);
		self::assertSniffError(
			$report,
			10,
			MethodSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_METHODS,
			'Expected 1 blank line after method, found 2.'
		);
		self::assertSniffError(
			$report,
			16,
			MethodSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_METHODS,
			'Expected 1 blank line after method, found 0.'
		);
		self::assertSniffError(
			$report,
			24,
			MethodSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_METHODS,
			'Expected 1 blank line after method, found 0.'
		);
		self::assertSniffError(
			$report,
			35,
			MethodSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_METHODS,
			'Expected 1 blank line after method, found 2.'
		);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrorsDifferentSettingsAndSameLinesCount()
	{
		$report = self::checkFile(__DIR__ . '/data/methodSpacingErrors.php', [
			'minLinesCount' => 2,
			'maxLinesCount' => 2,
		]);

		self::assertSame(4, $report->getErrorCount());

		self::assertSniffError(
			$report,
			6,
			MethodSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_METHODS,
			'Expected 2 blank lines after method, found 0.'
		);
		self::assertSniffError(
			$report,
			16,
			MethodSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_METHODS,
			'Expected 2 blank lines after method, found 0.'
		);
		self::assertSniffError(
			$report,
			19,
			MethodSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_METHODS,
			'Expected 2 blank lines after method, found 1.'
		);
		self::assertSniffError(
			$report,
			24,
			MethodSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_METHODS,
			'Expected 2 blank lines after method, found 0.'
		);
	}

	/**
	 * @return void
	 */
	public function testWithDifferentSettingsNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/methodSpacingWithDifferentSettingsNoErrors.php', [
			'minLinesCount' => 2,
			'maxLinesCount' => 4,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testWithDifferentSettingsErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/methodSpacingWithDifferentSettingsErrors.php', [
			'minLinesCount' => 2,
			'maxLinesCount' => 4,
		]);

		self::assertSame(3, $report->getErrorCount());

		self::assertSniffError(
			$report,
			6,
			MethodSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_METHODS,
			'Expected 2 to 4 blank lines after method, found 0.'
		);
		self::assertSniffError(
			$report,
			10,
			MethodSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_METHODS,
			'Expected 2 to 4 blank lines after method, found 5.'
		);
		self::assertSniffError(
			$report,
			19,
			MethodSpacingSniff::CODE_INCORRECT_LINES_COUNT_BETWEEN_METHODS,
			'Expected 2 to 4 blank lines after method, found 0.'
		);

		self::assertAllFixedInFile($report);
	}

}
