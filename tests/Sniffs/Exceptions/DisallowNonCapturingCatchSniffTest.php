<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Exceptions;

use SlevomatCodingStandard\Sniffs\TestCase;

class DisallowNonCapturingCatchSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testNoErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowNonCapturingCatchNoErrors.php');
		self::assertNoSniffErrorInFile($report);
	}

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowNonCapturingCatchErrors.php');

		self::assertSame(3, $report->getErrorCount());

		self::assertSniffError($report, 5, DisallowNonCapturingCatchSniff::CODE_DISALLOWED_NON_CAPTURING_CATCH);
		self::assertSniffError($report, 10, DisallowNonCapturingCatchSniff::CODE_DISALLOWED_NON_CAPTURING_CATCH);
		self::assertSniffError($report, 16, DisallowNonCapturingCatchSniff::CODE_DISALLOWED_NON_CAPTURING_CATCH);
	}

}
