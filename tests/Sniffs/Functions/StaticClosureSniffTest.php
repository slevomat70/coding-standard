<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Functions;

use SlevomatCodingStandard\Sniffs\TestCase;

class StaticClosureSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/staticClosureNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/staticClosureErrors.php');

		self::assertSame(4, $report->getErrorCount());

		self::assertSniffError($report, 3, StaticClosureSniff::CODE_CLOSURE_NOT_STATIC);
		self::assertSniffError($report, 8, StaticClosureSniff::CODE_CLOSURE_NOT_STATIC);
		self::assertSniffError($report, 15, StaticClosureSniff::CODE_CLOSURE_NOT_STATIC);
		self::assertSniffError($report, 16, StaticClosureSniff::CODE_CLOSURE_NOT_STATIC);

		self::assertAllFixedInFile($report);
	}

}
