<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use SlevomatCodingStandard\Sniffs\TestCase;
use function range;

class RequireYodaComparisonSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireYodaComparisonNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/requireYodaComparisonErrors.php');
		foreach (range(3, 37) as $lineNumber) {
			self::assertSniffError($report, $lineNumber, RequireYodaComparisonSniff::CODE_REQUIRED_YODA_COMPARISON);
		}

		self::assertSniffError($report, 41, RequireYodaComparisonSniff::CODE_REQUIRED_YODA_COMPARISON);
	}

	/**
	 * @return void
	 */
	public function testFixable()
	{
		$report = self::checkFile(
			__DIR__ . '/data/fixableRequireYodaComparisons.php',
			[],
			[RequireYodaComparisonSniff::CODE_REQUIRED_YODA_COMPARISON]
		);
		self::assertAllFixedInFile($report);
	}

	/**
	 * @return void
	 */
	public function testAlwaysVariableOnRight()
	{
		$report = self::checkFile(__DIR__ . '/data/requireYodaComparisonsAlwaysVariableOnRightErrors.php', [
			'alwaysVariableOnRight' => true,
		], [RequireYodaComparisonSniff::CODE_REQUIRED_YODA_COMPARISON]);

		self::assertSame(1, $report->getErrorCount());
		self::assertSniffError($report, 3, RequireYodaComparisonSniff::CODE_REQUIRED_YODA_COMPARISON);

		self::assertAllFixedInFile($report);
	}

}
