<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Classes;

use SlevomatCodingStandard\Sniffs\TestCase;

class ParentCallSpacingSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/parentCallSpacingNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/parentCallSpacingErrors.php');

		self::assertSame(2, $report->getErrorCount());

		self::assertSniffError(
			$report,
			6,
			ParentCallSpacingSniff::CODE_INCORRECT_LINES_COUNT_AFTER_CONTROL_STRUCTURE,
			'Expected 1 line after "parent", found 0.'
		);
		self::assertSniffError(
			$report,
			12,
			ParentCallSpacingSniff::CODE_INCORRECT_LINES_COUNT_BEFORE_CONTROL_STRUCTURE,
			'Expected 1 line before "parent", found 0.'
		);

		self::assertAllFixedInFile($report);
	}

}
