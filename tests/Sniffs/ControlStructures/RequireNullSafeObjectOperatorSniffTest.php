<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use SlevomatCodingStandard\Sniffs\TestCase;

class RequireNullSafeObjectOperatorSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireNullSafeObjectOperatorNoErrors.php', [
			'enable' => true,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireNullSafeObjectOperatorErrors.php', [
			'enable' => true,
		]);

		self::assertSame(16, $report->getErrorCount());

		foreach ([3, 4, 6, 7, 9, 10, 12, 13, 15, 18, 27, 34] as $line) {
			self::assertSniffError($report, $line, RequireNullSafeObjectOperatorSniff::CODE_REQUIRED_NULL_SAFE_OBJECT_OPERATOR);
		}

		self::assertCount(2, $report->getErrors()[12]);
		self::assertCount(2, $report->getErrors()[13]);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testShouldNotReportIfSniffIsDisabled()
	{
		$report = self::checkFile(__DIR__ . '/data/requireNullSafeObjectOperatorErrors.php', [
			'enable' => false,
		]);

		self::assertNoSniffErrorInFile($report);
	}

}
