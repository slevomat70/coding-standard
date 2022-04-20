<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use SlevomatCodingStandard\Sniffs\TestCase;
use function range;

class DisallowYodaComparisonSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowYodaComparisonNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowYodaComparisonErrors.php');

		foreach (range(3, 37) as $lineNumber) {
			self::assertSniffError($report, $lineNumber, DisallowYodaComparisonSniff::CODE_DISALLOWED_YODA_COMPARISON);
		}

		self::assertSniffError($report, 41, DisallowYodaComparisonSniff::CODE_DISALLOWED_YODA_COMPARISON);
	}

	/**
	 * @return void
	 */
	public function testFixable()
	{
		$report = self::checkFile(
			__DIR__ . '/data/fixableDisallowYodaComparisons.php',
			[],
			[DisallowYodaComparisonSniff::CODE_DISALLOWED_YODA_COMPARISON]
		);
		self::assertAllFixedInFile($report);
	}

}
