<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Exceptions;

use SlevomatCodingStandard\Sniffs\TestCase;

class RequireNonCapturingCatchSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireNonCapturingCatchNoErrors.php', [
			'enable' => true,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireNonCapturingCatchErrors.php', [
			'enable' => true,
		]);

		self::assertSame(3, $report->getErrorCount());

		self::assertSniffError($report, 5, RequireNonCapturingCatchSniff::CODE_NON_CAPTURING_CATCH_REQUIRED);
		self::assertSniffError($report, 11, RequireNonCapturingCatchSniff::CODE_NON_CAPTURING_CATCH_REQUIRED);
		self::assertSniffError($report, 19, RequireNonCapturingCatchSniff::CODE_NON_CAPTURING_CATCH_REQUIRED);

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testShouldNotReportIfSniffIsDisabled()
	{
		$report = self::checkFile(__DIR__ . '/data/requireNonCapturingCatchWhenDisabled.php', [
			'enable' => false,
		]);

		self::assertNoSniffErrorInFile($report);
	}

}
