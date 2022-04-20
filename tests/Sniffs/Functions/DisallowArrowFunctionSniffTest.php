<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Functions;

use SlevomatCodingStandard\Sniffs\TestCase;

class DisallowArrowFunctionSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowArrowFunctionNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowArrowFunctionErrors.php');

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 3, DisallowArrowFunctionSniff::CODE_DISALLOWED_ARROW_FUNCTION);
	}

}
