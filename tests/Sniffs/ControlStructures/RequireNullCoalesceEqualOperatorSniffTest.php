<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use SlevomatCodingStandard\Sniffs\TestCase;

class RequireNullCoalesceEqualOperatorSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireNullCoalesceEqualOperatorNoErrors.php', [
			'enable' => true,
		]);
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(
			__DIR__ . '/data/requireNullCoalesceEqualOperatorErrors.php',
			['enable' => true],
			[RequireNullCoalesceEqualOperatorSniff::CODE_REQUIRED_NULL_COALESCE_EQUAL_OPERATOR]
		);

		self::assertSame(11, $report->getErrorCount());

		foreach ([3, 5, 7, 9, 10, 12, 14, 15, 17, 21, 23] as $line) {
			self::assertSniffError($report, $line, RequireNullCoalesceEqualOperatorSniff::CODE_REQUIRED_NULL_COALESCE_EQUAL_OPERATOR);
		}

		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testShouldNotReportIfSniffIsDisabled()
	{
		$report = self::checkFile(
			__DIR__ . '/data/requireNullCoalesceEqualOperatorErrors.php',
			['enable' => false],
			[RequireNullCoalesceEqualOperatorSniff::CODE_REQUIRED_NULL_COALESCE_EQUAL_OPERATOR]
		);

		self::assertNoSniffErrorInFile($report);
	}

}
