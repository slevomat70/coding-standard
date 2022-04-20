<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\ControlStructures;

use SlevomatCodingStandard\Sniffs\TestCase;

class DisallowNullSafeObjectOperatorSniffTest extends TestCase
{

	/**
	 * @return void
	 */
	public function testErrors()
	{
		$report = self::checkFile(__DIR__ . '/data/disallowNullSafeObjectOperatorErrors.php');

		self::assertSame(1, $report->getErrorCount());

		self::assertSniffError($report, 3, DisallowNullSafeObjectOperatorSniff::CODE_DISALLOWED_NULL_SAFE_OBJECT_OPERATOR);
	}

}
