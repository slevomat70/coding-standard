<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Arrays;

use SlevomatCodingStandard\Sniffs\TestCase;

class TrailingArrayCommaSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testCheckFile()
	{
		$report = self::checkFile(__DIR__ . '/data/trailingCommas.php', [
			'enableAfterHeredoc' => true,
		]);

		self::assertSame(3, $report->getErrorCount());

		self::assertSniffError($report, 18, TrailingArrayCommaSniff::CODE_MISSING_TRAILING_COMMA);
		self::assertSniffError($report, 26, TrailingArrayCommaSniff::CODE_MISSING_TRAILING_COMMA);
		self::assertSniffError($report, 44, TrailingArrayCommaSniff::CODE_MISSING_TRAILING_COMMA);
	}

	/**
	 * @return void
	 */
	public function testFixable()
	{
		$report = self::checkFile(__DIR__ . '/data/fixableTrailingCommas.php', [
			'enableAfterHeredoc' => true,
		], [TrailingArrayCommaSniff::CODE_MISSING_TRAILING_COMMA]);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testDisabledAfterHeredoc()
	{
		$report = self::checkFile(__DIR__ . '/data/trailingCommasDisabledAfterHeredoc.php', [
			'enableAfterHeredoc' => false,
		]);
		self::assertNoSniffErrorInFile($report);
	}

}
