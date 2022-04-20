<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Functions;

use SlevomatCodingStandard\Sniffs\TestCase;

class StrictCallSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/strictCallNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/strictCallErrors.php');

		self::assertSame(7, $report->getErrorCount());

		self::assertSniffError($report, 3, StrictCallSniff::CODE_STRICT_PARAMETER_MISSING);
		self::assertSniffError($report, 4, StrictCallSniff::CODE_STRICT_PARAMETER_MISSING);
		self::assertSniffError($report, 5, StrictCallSniff::CODE_STRICT_PARAMETER_MISSING);

		self::assertSniffError($report, 10, StrictCallSniff::CODE_NON_STRICT_COMPARISON);
		self::assertSniffError($report, 11, StrictCallSniff::CODE_NON_STRICT_COMPARISON);
		self::assertSniffError($report, 16, StrictCallSniff::CODE_NON_STRICT_COMPARISON);
		self::assertSniffError($report, 18, StrictCallSniff::CODE_NON_STRICT_COMPARISON);
	}

}
